
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tosee</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/info.css">
</head>
<body>
    <video id="tenvideo_video_player_0" width="100%" x-webkit-airplay="true" webkit-playsinline="" playsinline="true" poster="//i.gtimg.cn/qqlive/images/20150608/black.png" src="http://oh2y7vde2.bkt.clouddn.com/pfop/iOS/Video/20161213/2f943baff6e3f2b13d05ffa16335aba1.mp4" controls="control" autoplay="true"></video>
    <video id="tenvideo_video_player_0" width="100%" height="100%" x-webkit-airplay="true" webkit-playsinline="" playsinline="true" preload="none" poster="//i.gtimg.cn/qqlive/images/20150608/black.png" tvp_loadingad_ended="1" src="http://111.202.98.141/vmind.qqvideo.tc.qq.com/h0200lkg92s.p202.1.mp4?vkey=6DC85C229F65FC56FB9E13F726A83EFE044BC94EFDA21FFDFAFD32A60CC976EBCB1F686FCAA7F5FC1F8034E743928A5134B40EE16EEDFB98F329A65E03F979FB67BD12C6A2C23C2CE0D3CAEC40600FD6DD269A765FF6D0F5&amp;platform=&amp;sdtfrom=&amp;fmt=hd&amp;level=0"></video>
    <section id="video">
        <video id="playvideo" autoplay playsinline webkit-playsinline src="http://oh2y7vde2.bkt.clouddn.com/{{$key}}" width="100%" data-time="{{ $time }}" poster="http://oh2y7vde2.bkt.clouddn.com/{{ $key }}?vframe/png/w/720/h/1280/offset/1"></video>
    </section>
    <section>
        <div id="user">
            <img src="/img/user.png" width="50px" height="50px" class="img-circle"/>
        </div>
        @if( $isPraise )
        <div id="praise" style="opacity:1;background:#ed4956;"></div>
        <img id="praise-img" src="/img/shape.png" width="28px" height="24px" data-id="{{ $id }}"/>
        @else
        <div id="praise"></div>
        <img id="praise-img" src="/img/praise.png" width="28px" height="24px" data-id="{{ $id }}"/>
        @endif
        <div id="share"></div>
        <img id="share-img"src="/img/share.png" width="23px" width="23px"/>
    </section>
    <section id="topic">
        <p>{{$title}}</p>
        @foreach( $answers as $answer )
        <div class="answer">
            <img src="/img/choose.png" width="18px" height="18px"><span>{{ $answer }}</span>
            <div></div>
        </div>
        @endforeach
    </section>
    <section id="answer-topic">
        <span>竞猜成功！</span><span id="show-answer">点击查看答案视频</span><hr><span>确定</span>
    </section>
    <section id="join-user">
        <div class="header">
            <div></div><span>他们参与了此竞猜游戏</span>
        </div>
        <div class="users">
            @foreach( $joinUsers as $user)
            @php
                $user = json_decode($user);
            @endphp
            <span class="user"><img src="{{ $user->headimgurl }}" width="50px" height="50px" class="img-circle"><br /><span class="name">{{ $user->nickname }}</span></span>
            @endforeach
            @if( count($joinUsers) >= 11 )
            <span class="user"><div class="more"><a href="{{ url('mp/users') }}"><img src="/img/more.png" width="16px" height="4px"></div><span class="name">更多</span></a></span>
            @endif
        </div>
    </section>
    <section id="about">
        <div class="bar"></div>
        <div class="logo"><img src="/img/logo.png" width="72px" height="72px"/></div>
        <div class="slogn"><span>Tosee玩拍世界<br />一键拍摄游戏化视频</span></div>
        <div class="footer"><span>我也要拍摄视频游戏</span></div>
    </section>
    <div id="share-tip">
        <span>请点击右上角 “<img src="/img/combined-shape.png" width="21.5px" height="5px">” 选择 “发送给朋友”</span>
        <img id="share-tip-point" src="/img/group-14.png" width="31px" height="42.5px">
    </div>
    <div id="share-component">
        <div>
        <span class="component"><img src="/img/weixin.png" width="65px" height="65px"><br /><span class="name">微信</span></span>
        <span class="component"><img src="/img/friend.png" width="65px" height="65px"><br /><span class="name">朋友圈</span></span>
        <span class="component"><img src="/img/qq.png" width="65px" height="65px"><br /><span class="name">QQ</span></span>
        <span class="component"><img src="/img/qzone.png" width="65px" height="65px"><br /><span class="name">QQ空间</span></span>
        </div>
        <div class="cancel">
            <p>取消</p>
        </div>
    </div>
    <script src="https://cdn.staticfile.org/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="/js/mp.js"></script>
    @if( strpos(Request::header('User-Agent'),'MicroMessenger') != false )
    @php
    $signPackage = App\Services\WechatService::getWechatJsApiSignPackage();
    @endphp
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
       //配置微信jssdk信息
       wx.config({
           debug: false,
           appId: "{{ $signPackage['appId'] }}",
           timestamp: "{{ $signPackage['timestamp'] }}",
           nonceStr: "{{ $signPackage['nonceStr'] }}",
           signature: "{{ $signPackage['signature'] }}",
           jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','onMenuShareQZone','startRecord','stopRecord','onVoiceRecordEnd','playVoice','pauseVoice','stopVoice', 'onVoicePlayEnd','uploadVoice', 'downloadVoice', 'chooseImage', 'previewImage', 'uploadImage', 'downloadImage', 'translateVoice', 'getNetworkType', 'openLocation','getLocation', 'hideOptionMenu', 'showOptionMenu', 'hideMenuItems', 'showMenuItems', 'hideAllNonBaseMenuItem', 'showAllNonBaseMenuItem', 'closeWindow', 'scanQRCode', 'chooseWXPay', 'openProductSpecificView']
       });
       wx.ready(function(){
       });
    </script>
    @endif
</body>
</html>
