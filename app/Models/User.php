<?php

namespace App\Models;

use App\Traits\ModelFinder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User
 *
 * @property int            id
 * @property string         $aliyun_token
 * @property string         nickname
 * @property string         mobile
 * @property string         push_name
 * @property string         avatar
 * @property string         $unionid
 * @property string         $openid
 * @property string         $password
 * @property bool           $age
 * @property bool           $sex
 * @property bool           $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereAliyunToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereMobile($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereOpenid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUnionid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Model
{
    use ModelFinder;

    public $guarded = [];

    public static $storeRules = [
        'mobile'   => 'required',
        'password' => 'required|between:6,18',
        'nickname' => 'required',
        'avatar'   => '',
        'sex'      => 'required',
        'code'     => 'required',
    ];

    public static $wechatRegisterRules = [
        'unionid'  => 'required',
        'nickname' => 'required',
        'avatar'   => 'required',
        'sex'      => 'required',
        'openid'   => '',
    ];

    /**
     * All raised group shoots by this user.
     * @return int
     */
    public function raisedGroupShootsCount()
    {
        return GroupShoot::createdBy($this->id)->notMerged()->parentShoot()->notDeleted()->count();
    }

    /**
     * All users count in this user's raised group shoots.
     * @return int
     */
    public function raisedGroupShootsUserCount()
    {
        return GroupShoot::createdBy($this->id)
                         ->notMerged()
                         ->parentShoot()
                         ->notDeleted()
                         ->get()
                         ->map(function (GroupShoot $groupShoot) {
                             return $groupShoot->joinedUsersCount();
                         })
                         ->sum();
    }

    /**
     * @return int
     */
    public function joinedGroupShootCount()
    {
        return GroupShoot::joinedParentShootsCount($this->id);
    }

    /**
     * @return mixed
     */
    public function joinedGroupShootsUserCount()
    {
        return GroupShoot::joinedAllParentShootTotalUsersCount($this->id);
    }

    /**
     * Get user's push name,nickname or encrypted phone number.
     * @return string
     */
    public function getPushNameAttribute()
    {
        return $this->nickname ?: substr_replace($this->mobile, '****', 3, -4);
    }

    /**
     * Get the user's avatar url.
     * @return mixed|string
     */
    public function getAvatarAttribute()
    {
        if (empty($this->attributes['avatar'])) {
            return env('DEFAULT_USER_IMAGE');
        }

        if (filter_var($this->attributes['avatar'], FILTER_VALIDATE_URL)) {
            return $this->attributes['avatar'];
        }

        return $_SERVER['REQUEST_URI'] . $this->attributes['avatar'];
    }

    /**
     * @param GroupShoot $groupShoot
     *
     * @return int
     */
    public function takenMoneyFromGroupShoot(GroupShoot $groupShoot)
    {
        $parentGroupShootMoneyGift = $groupShoot->sharedMoneyGift();

        if (!$parentGroupShootMoneyGift) {
            return 0;
        }

        return $parentGroupShootMoneyGift->childMoneyGifts()->where([
            'owner_id' => $this->id,
            'status'   => MoneyGift::STATUS_TAKE_MONEY_PAID,
        ])->sum('money');
    }
}
