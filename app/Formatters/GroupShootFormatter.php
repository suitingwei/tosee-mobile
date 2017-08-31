<?php

namespace App\Formatters;

use App\Models\GroupShoot;

class GroupShootFormatter
{
    /**
     * Get parent shoot formatter .
     */
    public static function getShowFormatterWithBriefInfo()
    {
        return function (GroupShoot $parentGroupShoot) {
            $ownerInfo = [
                'id'          => $parentGroupShoot->id,
                'owner'       => $parentGroupShoot->owner,
                'title'       => $parentGroupShoot->title,
                'verify_code' => $parentGroupShoot->verify_code,
                'joinCount'   => $parentGroupShoot->joinedUsersCount(),
                'members'     => $parentGroupShoot->joinedUsers(true, 5),
            ];

            if ($parentGroupShoot->hadShareRedBags()) {
                $ownerInfo['moneyGiftCount'] = $parentGroupShoot->taken_money;
            }
            return $ownerInfo;
        };
    }

    /**
     * @param $parentShootLargestTakenMoney
     *
     * @return \Closure
     */
    public static function getShowFormatterForChildShoot($parentShootLargestTakenMoney = null)
    {
        return function (GroupShoot $groupShoot) use ($parentShootLargestTakenMoney) {
            return [
                'id'                 => $groupShoot->id,
                'title'              => $groupShoot->title,
                'nickname'           => $groupShoot->owner->nickname,
                'avatar_url'         => $groupShoot->owner->avatar,
                'music_key'          => $groupShoot->music_key,
                'webp_cover_url'     => $groupShoot->webp_cover_url,
                'gif_cover_url'      => $groupShoot->gif_cover_url,
                'merge_status'       => $groupShoot->merge_status,
                'video_url'          => $groupShoot->getVideoUrlAttribute(1),
                'original_video_url' => $groupShoot->original_video_url,
                'merge_video_url'    => $groupShoot->merge_video_url,
                'money_gift'         => $groupShoot->rewardedMoney,
                'is_liked'           => $groupShoot->isLikedByUser(app('request')->input('user_id') ?: $groupShoot->owner->id),
                'likes_count'        => $groupShoot->likes_count(),
                'is_luckiest'        => $groupShoot->getIsLuckiestAttribute($parentShootLargestTakenMoney),
            ];
        };
    }
}
