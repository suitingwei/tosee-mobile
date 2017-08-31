<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TOSEE - 玩拍世界</title>
    <link rel="stylesheet" href="http://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    @if(env('APP_DEBUG'))
        <link rel="stylesheet" href="{{ url('assest/mp/css/play.css') }}">
    @else
        <link rel="stylesheet" href="http://s0.toseeapp.com{{ elixir('css/play.css') }}">
    @endif
</head>
<body>
<div id="navbar">
    <div id="logo"><img src="http://s.toseeapp.com/mp/img/logo.png" width="34px" height="34px"/></div>
    <div id="tip"><span>TOSEE - 玩拍世界</span><br/><span>一键拍摄游戏化视频</span></div>
    <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.tosee.android" id="download">
        <img src="http://s.toseeapp.com/mp/img/download.png" width="17px" height="17px"/>
        <span>下载</span>
    </a>
</div>
<div id="video">
    <video id="playvideo" src="{{ $videoSrc }}" width="100%" data-time="{{ $time }}" webkit-playsinline=""
           playsinline="true" poster="{{ $posterSrc }}" preload="none"></video>
</div>
<div>
    <div id="user">
        <img src="{{ $avatarUrl }}" width="50px" height="50px" class="img-circle"/>
    </div>
    @if($isWechatBrowser)
        @if( $isPraise )
            <div id="praise" style="opacity:1;background:#ed4956;"></div>
            <img id="praise-img" src="http://s.toseeapp.com/mp/img/praise_honest.png" width="27px" height="25px"
                 data-id="{{ $playId }}"/>
        @else
            <div id="praise"></div>
            <img id="praise-img" src="http://s.toseeapp.com/mp/img/praise_hollow.png" width="27px" height="25px"
                 data-id="{{ $playId }}"/>
        @endif
        <div id="share"></div>
        <img id="share-img" src="http://s.toseeapp.com/mp/img/share.png" width="23px" width="23px"/>
    @endif
</div>
<div id="topic">
    <p id="title">{{$title}}</p>
    @foreach( $answers as $k=>$answer )
        @if( $answer == $correctAnswer )
            <div class="answer" data-choose="1">
                @else
                    <div class="answer">
                        @endif
                        <img src="http://s.toseeapp.com/mp/img/choose.png" width="18px"
                             height="18px"><span>{{ $answer }}</span>
                    </div>
                    @endforeach
            </div>
            <div id="answer-tip">
                <span data-dismiss="modal"><img src="http://s.toseeapp.com/mp/img/close.png" width="11px"
                                                height="11px"/></span>
                <span><img id="answer-tip-img" src="" width="33px" height="33px"/><span
                            id="answer-tip-text"></span></span>
                <span id="show-answer">点击查看答案视频</span>
            </div>
</div>
<div id="play">
    <img src="http://s.toseeapp.com/mp/img/video_play.png" width="64px" height="64px"/>
</div>
<div id="join-user">
    <div class="header">
        <div></div>
        <span>他们参与了此竞猜游戏</span>
    </div>
    <div class="users">
        @if($joinUsers[0] != null)
            @foreach( $joinUsers as $user)
                @php
                    $user = json_decode($user);
                @endphp
                <div class="user"><img src="{{ $user->headimgurl }}" width="50px" height="50px" class="img-circle"><br/>
                    <div class="name">{{ $user->nickname }}</div>
                </div>
            @endforeach
            @if( count($joinUsers) >= 29 )
                <div class="user">
                    <div class="more">
                        <a href="{{ url('mp/play/users/'.$playId) }}">
                            <img src="http://s.toseeapp.com/mp/img/more.png" width="16px" height="4px">
                        </a>
                    </div>
                    <span class="name">更多</span>
                </div>
            @endif
        @endif
    </div>
</div>
<div id="share-tip">
    <span>请点击右上角 “<img src="http://s.toseeapp.com/mp/img/combined-shape.png" width="21.5px"
                       height="5px">” 选择 “发送给朋友”</span>
    <img id="share-tip-point" src="http://s.toseeapp.com/mp/img/group-14.png" width="31px" height="42.5px">
</div>
<div id="share-component">
    <div>
        @if($isWechatBrowser)
            <span class="component"><img src="http://s.toseeapp.com/mp/img/weixin.png" width="45px"
                                         height="45px"><br/><span class="name">微信好友</span></span>
            <span class="component"><img src="http://s.toseeapp.com/mp/img/friend.png" width="45px"
                                         height="45px"><br/><span class="name">朋友圈</span></span>
            <span class="component"><img src="http://s.toseeapp.com/mp/img/qq.png" width="45px" height="45px"><br/><span
                        class="name">QQ</span></span>
            <span class="component"><img src="http://s.toseeapp.com/mp/img/qzone.png" width="45px"
                                         height="45px"><br/><span class="name">QQ空间</span></span>
        @endif
    </div>
    <div class="cancel">
        <a href="javascript:void(0);">取消</a>
    </div>
</div>
<script type="text/javascript" src="http://cdn.staticfile.org/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="http://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">
    var isWechatBrowser = "{{$isWechatBrowser? 1: 0}}";
    var hasJoinUser = "{{ ($joinUsers[0] == null) ? 0 : 1 }}";
</script>
@if(env('APP_DEBUG'))
    <script type="text/javascript" src="{{ url('assest/mp/js/play.js') }}"></script>
@else
    <script type="text/javascript" src="http://s0.toseeapp.com{{ elixir('js/play.js') }}"></script>
@endif
@if($isWechatBrowser)
    @php
        $signPackage = App\Services\WechatService::getWechatJsApiSignPackage();
    @endphp
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        //配置微信jssdk信息
        wx.config({
            debug: false,
            appId: "{{ $signPackage['appId'] }}",
            timestamp: "{{ $signPackage['timestamp'] }}",
            nonceStr: "{{ $signPackage['nonceStr'] }}",
            signature: "{{ $signPackage['signature'] }}",
            jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']
        });
        wx.ready(function () {
            var title = "{{ trim($title) }}";
            var desc = "点击链接查看答案，你猜对了吗？";
            var link = "{{ url()->current() }}";
            var imgUrl = "{{ $shareImgUrl }}";
            wx.onMenuShareAppMessage({
                title: title, // 分享标题
                desc: desc, // 分享描述
                link: link, // 分享链接
                imgUrl: imgUrl, // 分享图标
            });
            wx.onMenuShareTimeline({
                title: title, // 分享标题
                link: link, // 分享链接
                imgUrl: imgUrl, //
            });
            wx.onMenuShareQQ({
                title: title, // 分享标题
                desc: desc, // 分享描述
                link: link, // 分享链接
                imgUrl: imgUrl, // 分享图标
            });
            wx.onMenuShareQZone({
                title: title, // 分享标题
                desc: desc, // 分享描述
                link: link, // 分享链接
                imgUrl: imgUrl, // 分享图标
            });
        });
    </script>
@endif
</body>
</html>
