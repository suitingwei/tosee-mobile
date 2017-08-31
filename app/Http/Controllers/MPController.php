<?php

namespace App\Http\Controllers;

use App\Models\GroupShoot;
use App\Models\MoneyGift;
use App\Models\Play;
use App\Models\User;
use App\Services\PlayService;
use App\Services\QiniuService;
use App\Services\WechatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\URL;

class MPController extends Controller
{

    public function video_next($id)
    {
        $group_shoot = GroupShoot::find($id);
        $parentId    = $group_shoot->parent_id == 0 ? $id : $group_shoot->parent_id;
        $data        = ['id' => '', 'video_key' => '', 'msg' => 'start', 'parent_id' => $parentId];
        if ($group_shoot->parent_id != 0) {
            if ($next_shoot = GroupShoot::where('parent_id', $group_shoot->parent_id)->where('id', '<', $group_shoot->id)->where('type', 1)->orderBy('id', 'desc')->first()) {
                $data['msg']       = 'end';
                $data['id']        = $next_shoot->id;
                $data['video_key'] = $next_shoot->video_key;
                $data['music_key'] = $next_shoot->music_key;
                $data['type']      = $next_shoot->type;
            }
        }
        else {
            if ($next_shoot = GroupShoot::where('parent_id', $group_shoot->id)->where('type', 1)->orderBy('id', 'desc')->first()
            ) {
                $data['msg']       = 'end';
                $data['id']        = $next_shoot->id;
                $data['video_key'] = $next_shoot->video_key;
                $data['music_key'] = $next_shoot->music_key;
                $data['type']      = $next_shoot->type;
            }
        }

        return response()->json($data);
    }

    public function video($id)
    {
        $group_shoot = GroupShoot::find($id);
        return view('mp.video')->with(['group_shoot' => $group_shoot]);
    }

    public function info(Request $request, $id)
    {
        if (!$playInfo = Play::find($id)) {
            abort(404);
        }

        $playId          = $playInfo->id;
        $isPraise        = false;
        $isWechatBrowser = (strpos($request->server('HTTP_USER_AGENT'), 'MicroMessenger') !== false) ? true : false;

        if ($isWechatBrowser) {
            $redirectUrl = urlencode('http://' . env('HOST') . '/mp/share/play/' . $id);
            $appid       = env('MP_APP_ID');
            $accessToken = session('access_token');

            if (!Input::get('code') && !$accessToken) {
                $scope   = 'snsapi_userinfo';
                $authUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . $redirectUrl . '&response_type=code&scope=' . $scope . '&state=STATE#wechat_redirect';
                return redirect($authUrl);
            }

            if (!$accessToken) {
                $accessToken = WechatService::getWechatMpAccessToken();
            }

            $openid = $accessToken['openid'];

            $userInfo = WechatService::getWechatMpUserInfo($accessToken['access_token'], $openid);
            if ($userInfo) {
                PlayService::setJoinUser($playId, $openid, $userInfo);
            }

            $isPraise = PlayService::getPraise($playId, $openid);
        }

        $appDomainUrl   = 'http://' . env('QINIU_APP_DOMAIN') . '/';
        $videoDomainUrl = 'http://' . env('QINIU_VIDEO_DOMAIN') . '/';
        $answers        = json_decode($playInfo->answer);
        $correctAnswer  = $answers[$playInfo->choose - 1];
        shuffle($answers);

        if (!Redis::sismember(QiniuService::PERSISTENT_Id_KEY, $playInfo->video_key)) {
            $videoSrc = $videoDomainUrl . 'watermark/' . $playInfo->video_key;
        }
        else {
            $videoSrc = $videoDomainUrl . $playInfo->video_key;
        }
        $posterSrc = $videoSrc . '?vframe/jpg/w/720/h/1280/offset/1|imageMogr2/interlace/1';

        $joinUsers = PlayService::getJoinUser($playId, 0, 28);
        $ownerInfo = User::find($playInfo->owner_id);

        if (empty($ownerInfo->avatar)) {
            $avatarUrl = 'http://s.toseeapp.com/mp/img/default_user.png';
        }
        elseif (URL::isValidUrl($ownerInfo->avatar)) {
            $avatarUrl = $ownerInfo->avatar;
        }
        else {
            $avatarUrl = $appDomainUrl . $ownerInfo->avatar;
        }

        $shareImgUrl = $appDomainUrl . 'sharethumbnail/' . $playInfo->video_key . '.jpg';

        $data = [
            'avatarUrl'       => $avatarUrl,
            'joinUsers'       => $joinUsers,
            'playId'          => $playId,
            'isPraise'        => $isPraise,
            'videoSrc'        => $videoSrc,
            'posterSrc'       => $posterSrc,
            'title'           => $playInfo->title,
            'answers'         => $answers,
            'time'            => $playInfo->time_frame,
            'choose'          => $playInfo->choose,
            'correctAnswer'   => $correctAnswer,
            'shareImgUrl'     => $shareImgUrl,
            'isWechatBrowser' => $isWechatBrowser,
        ];

        return view('mp.show', $data);
    }

    public function users($playId)
    {
        if (!Play::find($playId)) {
            abort(404);
        }

        $joinUsers = PlayService::getJoinUser($playId, 0, 99);
        $data      = [
            'joinUsers' => $joinUsers,
        ];

        return view('mp.users', $data);
    }

    public function praise()
    {
        $topicId = Input::get('topicId');
        $openid  = session('access_token')['openid'];

        PlayService::setPraise($topicId, $openid);
    }

    public function groupshootV2(Request $request, $id)
    {
        if (!$groupShootInfo = GroupShoot::where('status', 1)->where('id', $id)->first()) {
            return view('mp.groupshoot_not_found');
        }

        $isWechatBrowser = (strpos($request->server('HTTP_USER_AGENT'), 'MicroMessenger') !== false) ? true : false;
        $shareImgUrl     = 'http://' . env('QINIU_APP_DOMAIN') . '/' . 'sharethumbnail/' . $groupShootInfo->video_key . '.jpg';
        $joinCount       = GroupShoot::where('parent_id', $id)->notMerged()
                                     ->notDeleted()
                                     ->selectRaw('distinct owner_id')
                                     ->pluck('owner_id')
                                     ->push($groupShootInfo->owner_id)->unique()->count();
        if ($moneyGiftInfo = MoneyGift::where('group_shoot_id', $id)->where('status', 1)->first()) {
            $receiveCount = MoneyGift::where('parent_id', $moneyGiftInfo->id)->count();
            $shareText    = '玩拍领红包：' . ($moneyGiftInfo->money / 100) . '元红包等你来领取，已有' . $receiveCount . '人领取，赶快抢！';
        }
        else {
            $shareText = '一起玩群拍，已有' . $joinCount . '人一起群拍,赶快参加？';
        }
        $wechatShareData = [
            'is_wechat_browser' => $isWechatBrowser,
            'parent_id'         => $id,
            'shareImgUrl'       => $shareImgUrl,
            'desc'              => $shareText,
            'title'             => $groupShootInfo->title,
        ];

        return view('mp.groupshoot_mobilejs', $wechatShareData);
    }

    /**
     * Get the group shoot info.
     * @param Request $request
     * @param         $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groupshoot(Request $request, $id)
    {
        return view('mp.groupshoots.show.ajax', ['groupShootId'=>$id]);
    }

    public function notfound()
    {
        return view('mp.groupshoot_not_found');
    }
}
