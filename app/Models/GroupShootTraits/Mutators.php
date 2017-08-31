<?php

namespace App\Models\GroupShootTraits;


use App\Models\MoneyGift;
use App\Services\Helper;
use App\Services\QiniuService;
use App\Services\ShareService;
use Illuminate\Support\Facades\Redis;

trait Mutators
{

    /**
     * @return string
     */
    public function getGifCoverUrlAttribute()
    {
        if ($this->attributes['gif_cover_url']) {
            return Helper::url('' . $this->attributes['gif_cover_url'], env('QINIU_APP_DOMAIN'));
        }
        return Helper::url('webp/' . $this->video_key, env('QINIU_APP_DOMAIN'));
    }

    /**
     * @return string
     */
    public function getWebpCoverUrlAttribute()
    {
        return Helper::url('gif/' . $this->video_key, env('QINIU_APP_DOMAIN'));
    }

    /**
     * @param int $type
     *
     * @return string
     */
    public function getVideoUrlAttribute($type = 0)
    {
        if ($type == 1) {
            return Helper::url($this->video_key, env('QINIU_VIDEO_DOMAIN'));
        }

        if (Redis::sismember(QiniuService::STICKER_Id_KEY, $this->video_key)) {
            $path = 'sticker/' . $this->video_key;
        }
        elseif (!Redis::sismember(QiniuService::PERSISTENT_Id_KEY, $this->video_key)) {
            $path = 'watermark/' . $this->video_key;
        }
        else {
            $path = $this->video_key;
        }

        return Helper::url($path, env('QINIU_VIDEO_DOMAIN'));
    }

    /**
     * @return string
     */
    public function getOriginalVideoUrlAttribute()
    {
        return QiniuService::generateVideoUrlFromKey($this->attributes['original_video_key']);
    }

    /**
     * Get this parent group shoot's all taken money.
     * -----------------------------------------------
     * @return int
     */
    public function getTakenMoneyAttribute()
    {
        if (!$this->hadShareRedBags()) {
            return 0;
        }

        return $this->moneyGifts()->where('parent_id', 0)->where('status', MoneyGift::STATUS_SHARE_GIFT_PAID)->first()->childMoneyGifts()->takenMoney()->sum('money');
    }

    /**
     * Get rewarded money from the parent group shoot.
     * @return int
     */
    public function getRewardedMoneyAttribute()
    {
        if ($this->isParentShoot()) {
            return 0;
        }

        $moneyGift = $this->moneyGifts->first();

        return $moneyGift ? (int)$moneyGift->money : 0;
    }

    /**
     * Get the merge video url.
     * @return string
     */
    public function getMergeVideoUrlAttribute()
    {
        return Helper::url('' . $this->video_key, env('QINIU_APP_DOMAIN'));
    }

    /**
     * Get the text share title.
     * @return string
     */
    public function getTextShareTitleAttribute()
    {
        return '一起玩群拍：' . $this->title;
    }

    /**
     * Get the thumbnail share title,used for when  share a parent group shoot.
     * @return string
     */
    public function getThumbnailShareTitleAttribute()
    {
        $joinedUserCount = $this->joinedUsersCount();
        $moneyGift       = $this->sharedMoneyGift();

        if ($moneyGift instanceof MoneyGift) {
            return "一起群拍领红包：{$this->title}\n{$moneyGift->rmb_money}元红包等你来领！";
        }

        return "一起玩群拍：{$this->title}\n已有{$joinedUserCount}人一起群拍！";
    }

    /**
     * Get the share text.
     * @return string
     */
    public function getShareTextAttribute()
    {
        $joinCount = $this->joinedUsersCount();
        $moneyGift = $this->sharedMoneyGift();

        if ($moneyGift instanceof MoneyGift) {
            return "玩拍领红包：{$moneyGift->rmb_money}元红包等你来领取，已有{$joinCount}人领取，赶快抢！";
        }

        return '一起玩群拍，已有' . $joinCount . '人一起群拍,赶快参加？';
    }

    /**
     * Get the mobile h5 url.
     * @return string
     */
    public function getMobileUrlAttribute()
    {
        return Helper::url("mp/groupshoots/{$this->id}", env('MOBILE_HOST'));
    }

    /**
     * @return string
     */
    public function getTextShareThumbnailUrlAttribute()
    {
        return QiniuService::getTextShareThumbnailUrl($this->video_key);
    }

    /**
     * This url will generate a merged covers image,used for share the groupshoot thunmbnail
     * @see https://app.zeplin.io/project/58c7ffdcff98945ac51ca72a/screen/58f72ffa2f0a014982be9eec
     *
     * @param null $userId
     *
     * @return string
     */
    public function getMergedCoversImageUrlAttribute($userId = null)
    {
        return Helper::url('v1/groupshoots/' . $this->id . '/merged-covers?user_id=' . $userId);
    }

    /**
     * Get the groupshoot thunmbnail share url.
     * @see https://app.zeplin.io/project/58c7ffdcff98945ac51ca72a/screen/58f72ffa2f0a014982be9eec
     *
     * @param null $shareUserId
     *
     * @return string
     */
    public function getThumbnailShareUrlAttribute($shareUserId = null)
    {
        return ShareService::generateGroupShootShareImageUrl($this, $shareUserId);
    }

    /**
     * @return bool
     */
    public function isFrameGenerated()
    {
        $frameUrl = QiniuService::getVideoFrameUrl($this->video_key);

        $handle = curl_init($frameUrl);

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if ($httpCode == 404) {
            return false;
        }
        curl_close($handle);
        return true;
    }
}
