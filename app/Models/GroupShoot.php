<?php

namespace App\Models;

use App\Jobs\GenerateVideoFrames;
use App\Models\GroupShootTraits\Judgments;
use App\Models\GroupShootTraits\Mutators;
use App\Models\GroupShootTraits\Notifications;
use App\Models\GroupShootTraits\RelationShips;
use App\Models\GroupShootTraits\Scopes;
use App\Models\GroupShootTraits\Statistics;
use App\Services\GroupShootService;
use App\Services\QiniuService;
use App\Traits\ModelFinder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * App\Models\GroupShoot
 *
 * @property mixed          canvas_direction
 * @property mixed          video_key
 * @property mixed          merge_status
 * @property mixed          title
 * @property mixed          id
 * @property mixed          music_key
 * @property int            status
 * @property int            owner_id
 * @property int            parent_id
 * @property GroupShoot     parent
 * @property mixed          type
 * @property mixed          thumbnail_share_title
 * @property int            verify_code
 * @property string         video_url
 * @property string         webp_cover_url
 * @property string         gif_cover_url
 * @property Collection     moneyGifts
 * @property int            taken_money
 * @property User           owner
 * @property Collection     childGroupShoots
 * @property int            rewardedMoney
 * @property mixed          merge_video_url
 * @property mixed          original_video_url
 * @property mixed          text_share_title
 * @property mixed          mobile_url
 * @property mixed          merged_covers_image_url
 * @property GroupShootRule rule
 * @property int            taken_largest_money
 */
class GroupShoot extends Model
{
    /**
     * Query scopes.
     */
    use Scopes;

    /**
     * Getters and setters.
     */
    use Mutators;

    /**
     * Things for judgements.
     */
    use Judgments;

    /**
     * Relationships.
     */
    use RelationShips;

    /**
     * Things about notification.
     * Such as push when new groupshoot created,or someone joined.
     */
    use Notifications;

    /**
     * Things about statictics.
     */
    use Statistics;

    /**
     * Eloquent model static find method,for IDE auto complettion.
     */
    use ModelFinder;

    const STATUS_NOT_DELETED                       = 1;  // Not deleted shoots.
    const STATUS_DELETED                           = 3;  // Deleted shoots.
    const TYPE_NOT_MERGED                          = 1;  //Not merged shoots.
    const TYPE_MERGED                              = 2;  //Merged shoots.
    const PARENT_SHOOT                             = 0; //The parent shoot.
    const IOS_JUMP_FROM_NOTIFICATION_TO_GROUPSHOOT = "tosee://groupshoot?id=";

    /**
     * Rules for creating new group shoot.
     * @var array
     */
    public static $storeRules = [
        'video_key'          => 'required',
        'original_video_key' => 'string',
        'parent_id'          => 'required|numeric',
    ];

    public $guarded = [];

    /**
     * Listent the mode event.
     * 1.When group shoot created,dispatch the viedeo job into queue.
     */
    public static function boot()
    {
        parent::boot();

        /**
         * 1.Generate the video sticker.
         * 2.Create the share thumbnail.
         * 3.Generate the verify code for the parent group shoot.
         */
        static::created(function (GroupShoot $groupShoot) {
            dispatch((new GenerateVideoFrames($groupShoot))->onQueue('video-frames'));

            QiniuService::makeShareThumbnail($groupShoot->video_key);

            if ($groupShoot->isParentShoot()) {
                $groupShoot->update(['verify_code' => GroupShootService::makeVerifyCode()]);
            }

            $groupShoot->pushAccordingToType();
        });
    }

    /**
     * @return array
     */
    public function formatItem()
    {
        $formatItem = array(
            'parent_id'      => $this->parent_id,
            'video_url'      => $this->video_url,
            'webp_cover_url' => $this->webp_cover_url,
            'gif_cover_url'  => $this->gif_cover_url,
        );

        $money                    = MoneyGift::where('group_shoot_id', $this->id)->pluck('money');
        $formatItem['money_gift'] = (int)$money;
        $formatItem['join_count'] = GroupShoot::where('parent_id', $this->id)->where('status', 1)->count();
        return $formatItem;
    }

    /**
     * Set the status to be deleted.
     */
    private function deleteShoot()
    {
        $this->update(['status' => self::STATUS_DELETED]);
    }

    /**
     * Delete the shoot, and all possible child shoots.
     */
    public function deleteWithChildGroupShoots()
    {
        $this->deleteShoot();
        $this->childGroupShoots()->update(['status' => self::STATUS_DELETED]);
    }

    /**
     * Determines whether the groupshoot has a shared money gift.
     * @return  MoneyGift|null
     */
    public function sharedMoneyGift()
    {
        return MoneyGift::where('group_shoot_id', $this->id)->where('status', MoneyGift::STATUS_SHARE_GIFT_PAID)->first();
    }

    /**
     * All joined group shoot's users.
     * The users ordered by the time they joined the group shoot.
     * The owner of the parent groupshoot should always be in the first place.
     *
     * @param bool         $withOwner
     *
     * @param null|integer $count
     *
     * @return Collection
     */
    public function joinedUsers($withOwner = true, $count = null)
    {
        //Let the owner be the first place of the memebers.
        $joinedUserIds = $this->childGroupShoots()->where('owner_id', '!=', $this->owner_id)->orderBy('created_at')->pluck('owner_id')->unique()->prepend($this->owner_id);

        //remove the owner of the shoot from the result.
        if (!$withOwner) {
            $ownerId       = $this->owner_id;
            $joinedUserIds = $joinedUserIds->filter(function ($value) use ($ownerId) {
                return $value != $ownerId;
            });
        }

        if ($joinedUserIds->count() == 0) {
            return collect();
        }

        $usersQueryBuilder = User::whereIn('id', $joinedUserIds->all())->orderByRaw("field(id," . $joinedUserIds->implode(',') . ")");

        if (!is_null($count)) {
            $usersQueryBuilder = $usersQueryBuilder->take($count);
        }

        return $usersQueryBuilder->get();
    }

    /**
     * Get groupshoot canvas direction.
     */
    public function getCanvasDirectionAttribute()
    {
        $rule = $this->rule;
        if (!$rule) {
            return GroupShootRule::CANVAS_DIRECTION_VERTICAL;
        }

        return $rule->canvas_direction;
    }

    /**
     * Taken largest money.
     * @return int
     */
    public function getTakenLargestMoneyAttribute()
    {
        $moneyGift = $this->sharedMoneyGift();
        if (!$moneyGift) {
            return 0;
        }

        $childMoneyGift = $moneyGift->childMoneyGifts()->takenMoney()->orderBy('money', 'desc')->first();

        return $childMoneyGift ? $childMoneyGift->money : 0;
    }

    /**
     * Determines whether the children groupshoot is the luckiest,which taken the largest money of the parent's money gifts.
     * Remember, the luckiest should only be true, after all money gifts have been taken.
     *
     * @param null $parentShootLargestTakenMoney
     *
     * @return bool
     */
    public function getIsLuckiestAttribute($parentShootLargestTakenMoney = null)
    {
        //The parent shoot is always NOT be the luckiest,because the money gifts only avaible after parent groupshoot created.
        if ($this->isParentShoot()) {
            return false;
        }

        $parentMoneyGift = $this->parent->sharedMoneyGift();

        //If the parent groupshoot has not share the money gifts, there is no red bags.
        if (!($parentMoneyGift instanceof MoneyGift)) {
            return false;
        }

        //If there is still money gift NOT taken, there is not luckiest.
        if (!$parentMoneyGift->allTaken()) {
            return false;
        }

        //We pass the parent groupshoot's largest taken money, because this method will be called on every loop of the chidren group shoots,
        //when formatter the groupshoots,So we can save the time.
        if ($parentShootLargestTakenMoney) {
            return $this->rewardedMoney == $parentShootLargestTakenMoney;
        }

        return $this->rewardedMoney == $this->parent->taken_largest_money;
    }
}
