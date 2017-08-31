<?php

namespace App\Services;

use App\Models\GroupShoot;
use App\Models\MoneyGift;
use function Qiniu\base64_urlSafeEncode;

class ShareService
{
    public static $instance;

    private $imageUrl;
    private $groupShoot;
    private $shareUserId;

    /**
     * ShareService constructor.
     *
     * @param GroupShoot $groupShoot
     * @param null       $shareUserId
     */
    public function __construct(GroupShoot $groupShoot, $shareUserId = null)
    {
        $this->groupShoot  = $groupShoot;
        $this->shareUserId = $shareUserId;
        $this->fontColor   = base64_urlSafeEncode('#FFF');
        $this->chineseFont = base64_urlSafeEncode('微软雅黑');
    }

    /**
     * @param GroupShoot $groupShoot
     *
     * @param            $shareUserId
     *
     * @return string
     */
    public static function generateGroupShootShareImageUrl(GroupShoot $groupShoot, $shareUserId)
    {
        $instance = new self($groupShoot, $shareUserId);

        return $instance->init()
                        ->enableWatermark3()
                        ->appendMergedCovers()
                        ->appendOwnerInfo()
                        ->appendGroupShootInfo()
                        ->appendQrCode()
                        ->appendAppInfo()
                        ->get();
    }

    /**
     * @return $this
     */
    private function appendQrCode()
    {
        $qrcodeUrl      = base64_urlSafeEncode(Helper::url('qiniu/qrcode/' . base64_urlSafeEncode($this->groupShoot->mobile_url)));
        $this->imageUrl .= '/image/aHR0cDovL3MudG9zZWVhcHAuY29tL2xvZ28vcXJjb2RlLWJhY2tncm91bmQucG5n/gravity/SouthWest/dx/210/dy/50/ws/0.16';
        $this->imageUrl .= '/image/' . $qrcodeUrl . '/gravity/SouthWest/dx/214/dy/54/ws/0.15';
        $this->imageUrl .= '/image/aHR0cDovL3MudG9zZWVhcHAuY29tL2xvZ28vaWNvbng0Ni5wbmc=/gravity/SouthWest/dx/255/dy/90/ws/0.05';
        return $this;
    }

    /**
     * @return $this
     */
    private function init()
    {
        $this->imageUrl = QiniuService::getHttpsVideoUrl('share-background.png');
        return $this;
    }

    /**
     * @return $this
     */
    private function enableWatermark3()
    {
        $this->imageUrl .= '?watermark/3';
        return $this;
    }

    /**
     * @return $this
     */
    private function appendOwnerInfo()
    {
        $ownerName         = base64_urlSafeEncode($this->groupShoot->owner->nickname);
        $ownerAvatar       = base64_urlSafeEncode($this->groupShoot->owner->avatar);
        $avatarToCircleUrl = Helper::url('v1/share/avatar-to-circle?avatar=' . $ownerAvatar);

        $this->imageUrl .= '/image/' . base64_urlSafeEncode($avatarToCircleUrl) . '/gravity/South/dx/0/dy/490/ws/0.11';
        $this->imageUrl .= '/text/' . $ownerName . '/fill/' . $this->fontColor . '/font/' . $this->chineseFont . '/fontsize/558/gravity/South/dx/0/dy/430';

        $createGroupShootBtnImageUrl = base64_urlSafeEncode('http://v0.toseeapp.com/created-groupshoot-btn.png');
        $this->imageUrl              .= '/image/' . $createGroupShootBtnImageUrl . '/gravity/South/dx/0/dy/380/ws/0.09';
        return $this;
    }

    /**
     * @return $this
     */
    private function appendGroupShootInfo()
    {
        $title          = base64_urlSafeEncode($this->groupShoot->title);
        $this->imageUrl .= '/text/' . $title . '/fill/' . $this->fontColor . '/font/' . $this->chineseFont . '/fontsize/760/gravity/South/dx/0/dy/310';

        $moneyGift = $this->groupShoot->sharedMoneyGift();
        if ($moneyGift instanceof MoneyGift) {
            $receiveInfo    = base64_urlSafeEncode($moneyGift->receivedCount() . '人已领红包');
            $color          = base64_urlSafeEncode('#ffffff');
            $this->imageUrl .= '/text/' . $receiveInfo . '/fill/' . $color . '/font/' . $this->chineseFont . '/fontsize/560/gravity/South/dx/0/dy/260/dissolve/80';

            $redbagImageUrl = base64_urlSafeEncode('http://v0.toseeapp.com/red-bag.png');
            $this->imageUrl .= '/image/' . $redbagImageUrl . '/gravity/SouthWest/dx/250/dy/260/';
        }

        return $this;
    }

    /**
     * Append the app info,such as the logo,appname.
     * @return $this
     */
    private function appendAppInfo()
    {
        $title          = base64_urlSafeEncode('长按参与群拍');
        $this->imageUrl .= '/text/' . $title . '/fill/' . $this->fontColor . '/font/' . $this->chineseFont . '/fontsize/460/gravity/South/dx/50/dy/140';

        $title          = base64_urlSafeEncode('抢红包');
        $color          = base64_urlSafeEncode('#ea4956');
        $this->imageUrl .= '/text/' . $title . '/fill/' . $color . '/font/' . $this->chineseFont . '/fontsize/520/gravity/South/dx/20/dy/100';

        $title          = base64_urlSafeEncode('视频由·TOSEE生成');
        $color          = base64_urlSafeEncode('#ffffff');
        $this->imageUrl .= '/text/' . $title . '/fill/' . $color . '/font/' . $this->chineseFont . '/fontsize/460/gravity/South/dx/80/dy/50/dissolve/40';

        return $this;
    }

    /**
     * Get the result
     * @return string
     */
    private function get()
    {
        return $this->imageUrl;
    }

    /**
     * @return $this
     */
    private function appendMergedCovers()
    {
        //Get the merged covers image,Notice that this image will be generated by our application server,in this route.
        //Qiniu will cache the image based on the url,so we should update the url in 60s,incase the user get the newest cover.
        $mergedCoversUrl            = $this->groupShoot->getMergedCoversImageUrlAttribute($this->shareUserId);
        $groupShootsMergedCoversUrl = base64_urlSafeEncode($mergedCoversUrl . '?timestamp=' . time());
        $this->imageUrl             .= '/image/' . $groupShootsMergedCoversUrl . '/gravity/North/dx/0/dy/60';

        //Appent the play button image on the merged covers.
        $playButtonUrl  = base64_urlSafeEncode('http://v0.toseeapp.com/share-play-button.png');
        $this->imageUrl .= '/image/' . $playButtonUrl . '/gravity/North/dx/0/dy/310';
        return $this;
    }
}
