<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TOSEE - 群拍神器，大家来群拍！</title>
    <link rel="stylesheet" href="http://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    {{--<link rel="stylesheet" type="text/css" href="/css/swiper-3.3.1.min.css"/>--}}
    {{--<link rel="stylesheet" type="text/css" href="/css/animate.min.css" />--}}
    {{--<script src="/js/swiper.animate.min.js"></script>--}}
    {{--<script src="/js/swiper.min.js"></script>--}}
    <style>
        * {
            margin: 0;
            padding: 0
        }

        body {
            background-color: rgb(16, 16, 17);
        }

        #header a {
            width: .88rem;
            height: .51rem;
            position: absolute;
            right: .21rem;
            top: .36rem;
        }

        #content li.merge-video {
            overflow: hidden;
            position: relative;
            width: 2.5rem;
            height: 3.33rem;
        }

        #content .merge-video .merge-video-btn {
            position: absolute;
            left: 0%;
            top: 0%;
            height: .42rem;
            background: url('/img/merge-video.png') no-repeat;
            color: white;
            background-size: 100% 100%;
            line-height: .42rem;
            width: 1.2rem;
            text-align: center;
            font-size: .16rem;
        }

        .img-fit {
            width: 100%;
            height: 1.66rem;
            object-position: center center;
            object-fit: center;
        }

        #content li {
            border-bottom: 1px solid black;
            border-left: 1px solid black;
            width: 1.25rem;
            height: 1.67rem;
            float: left;
            position: relative;
            box-sizing: border-box;
        }

        #forheader {
            box-sizing: border-box;
            /*-webkit-filter: blur(1px);
            -moz-filter: blur(1px);
            -ms-filter: blur(1px);
            filter: blur(1px);*/
            background: url({{$data['owner']->avatar}}) no-repeat left center;
            /*background-color: #2E2E2E;*/
            background-size: 100% auto;
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -2;
        }

        #canvasForHeader {
            background-color: rgba(16, 16, 17, 0.5);
            background-size: 100% 100%;
            position: absolute;
            width: 100%;
            height: 1.37rem;
            z-index: -1;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 1));
            background: -moz-linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 1));
        }

        .owner {
            font-weight: 100;
            font-size: 0.12rem;
            padding: 0.07rem 0 0.05rem 0;
            margin-bottom: 0px;
            color: #fff;
            line-height: .14rem;
        }

        .avatar {
            vertical-align: middle;
            border-radius: 50%;
            width: .44rem;
            height: .44rem;
            margin-top: .27rem;
        }

        #header {
            text-align: center;
            position: relative;
            height: 1.36rem;
        }

        #nav {
            padding-bottom: 0.22rem;
            text-align: center;
            background: black;
        }

        .receiver {
            width: .24rem;
            height: .24rem;
            margin-right: .08rem;
            border-radius: 50%;

        }

        #nav img {
            margin-top: .15rem;

        }

        #nav h3 {
            color: white;
            margin-top: 0;
            font-size: .22rem;
            font-weight: 500;
        }

        .forCarema {
            background-color: rgb(31, 31, 33);
            position: relative;
        }

        .forCarema img {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: .37rem;
            height: .32rem;
        }

        #content .VideoPlayer li {
            position: relative;
        }

        #content .VideoPlayer .imgForPlay {
            position: absolute;
            left: .07rem;
            bottom: .06rem;
            width: .26rem;
            height: .26rem;
            background: url('/img/video_play.png') no-repeat 100% 100%;
            background-size: 100% 100%;
        }

        #content .VideoPlayer .imgForDetail {
            position: absolute;
            top: .07rem;
            right: .07rem;
            height: .4rem;
            width: .60rem;
            color: white;
            background: none;
            font-size: .10rem;
            border-radius: .02rem;
            overflow: hidden;
            font-weight: 700;
        }

        #content .luckist .imgForDetailTop {
            height: .20rem;
            background-color: rgba(255, 0, 0, 0.76);
            line-height: .20rem;
            text-align: right;
            padding-right: .03rem;
        }

        #content .luckist .TopIcon {
            width: .11rem;
            height: .08rem;
            position: absolute;
            left: .06rem;
            top: .06rem;
            background: url('/img/crown.png') no-repeat;
            background-size: 100% 100%;
        }

        #content .luckist .imgForDetailBottom {
            height: .20rem;
            background-color: rgba(255, 255, 255, 0.9);
            color: black;
            line-height: .20rem;
            text-align: center;
            color: red;
        }

        /*#content .luckist .imgForDetailBottom:before {
            content: '手气最佳';
            position: absolute;
        }*/

        #content .normal .imgForDetailTop {
            height: .20rem;
            background-color: rgba(255, 0, 0, 0.76);
            line-height: .20rem;
            text-align: right;
            padding-right: .03rem;
        }

        #content .normal .TopIcon {
            width: .12rem;
            height: .14rem;
            position: absolute;
            left: .06rem;
            top: .02rem;
            background: url('/img/white.png') no-repeat;
            background-size: 100% 100%;
        }

        #content .normal .imgForDetailBottom {
            height: .20rem;
            background-color: rgba(255, 255, 255, 0.9);
            color: black;
            line-height: .20rem;
            text-align: center;
        }

        /*#content .normal .imgForDetailBottom:before {
            content: '已领取';
            position: absolute;
        }*/

        #con {
            overflow: hidden;
            /*padding-bottom: .5rem;*/
            height: 3rem;
        }

        #footer {
            z-index: 11;
            height: .50rem;
            background: rgba(255, 255, 255, 0.87);
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .defaultStyle {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .defaultStyle img {
            height: .54rem;
            width: .54rem;
            margin: 0 auto;
        }

        .defaultStyle p {
            color: rgb(50, 50, 50);
            text-align: center;
            font-size: .16rem;
            margin: .05rem 0 0 0;
        }

        .swiper-container {
            width: 100%;
            height: 100%;
        }

        .circle {
            border-radius: 50%;
            background-color: rgba(255, 0, 0, 0.3);
        }

        .page1_img {
            width: 2.96rem;
            height: 2.14rem;
            margin: 1.95rem 0 0 .27rem;
            z-index: 99;
            position: relative;
        }

        .page1_img div {
            position: absolute;
        }

        .forAnimate {
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 20;
            position: absolute;
        }

        .merge-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            font-size: .16rem;
            word-break: break-all;
            width: 1.4rem;
        }

        .verify_code {
            width: 1.6rem;
            height: 0.33rem;
            border-radius: 50px;
            border: solid 1px #ed4956;
            color: rgb(215, 48, 48);
            text-align: center;
            margin: 0 auto 21px;
            line-height: 0.33rem;
            font-size: 0.14rem;
        }
    </style>
</head>
<body id="kong" style="display: none">
{{--Header of the page--}}
@if($had_red_bags && (request()->session()->get('had_visited') == 0))
    {{--<div class="forAnimate">

    </div>
    <div class="swiper-container" style="overflow: visible;z-index: 99">
        <div class="swiper-wrapper">
            <div class="swiper-slide page1" style="height:0px;">
                <div class="page1_img">
                    <div style="top: .75rem; left: .58rem;z-index:100;background:url('/img/12X@2x.png') no-repeat;background-size: 100% 100%;width: 2.03rem; height: 1.6rem;" swiper-animate-effect="fadeInLeft" swiper-animate-duration="1s" swiper-animate-delay="1s" class="ani">

                    </div>
                    <div class="ani circle" swiper-animate-effect="zoomOut1" swiper-animate-duration="1.2s" swiper-animate-delay="1.2s" style="width: 1.7rem; height: 1.7rem; top: 0px; left: 0px;"></div>

                    <div class="ani circle" swiper-animate-effect="zoomOut1" swiper-animate-duration="1.2s" swiper-animate-delay="1.2s" style="width: .53rem; height: .53rem; top: 0.26rem; right: .47rem;"></div>

                    <div class="ani circle" swiper-animate-effect="zoomOut1" swiper-animate-duration="2.5s" swiper-animate-delay="1.5s" src="img/yq0KXFbc6DqAAQCNAAAEYzLJJkw588.gif" style="width: .75rem; height: .75rem; top: 0.5rem; right: 0rem;"></div>

                    <div class="ani circle" swiper-animate-effect="fadeInDown" swiper-animate-duration="1.7s" swiper-animate-delay="1.7s" style="width: .36rem; height: .36rem; bottom: 0rem; right: 0.36rem;"></div>
                    <div class="ani circle" swiper-animate-effect="fadeInDown" swiper-animate-duration="1.7s" swiper-animate-delay="1.7s" style="width: .14rem; height: .14rem; bottom: 0rem; right: 1.2rem;"></div>

                </div>
            </div>
        </div>
    </div>--}}
    <div class="forAnimate"></div>
    <img src="http://v0.toseeapp.com/groupshoot-effect.gif"
         style="position:absolute;z-index:199;width:100%;top:-0.5rem;" id="gif"/>
@endif

<div id="header">
    <div id="forheader">
    </div>
    <div id="canvasForHeader"></div>
    <img src="{{ $data['owner']->avatar}}" class="avatar"/>
    <p class="owner">
        {{ $data['owner']->nickname }}
    </p>
    <p><img src="/img/bitmap@2x.png" style="width:.29rem;height:auto"></p>
    @if($had_red_bags)
        <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.tosee.android">
            <img src="/img/12X@2x.png" alt="coupon-button" style="width:100%;height:100%">
        </a>
    @endif
</div>
<div id="nav">
    <div class="verify_code">
        群拍密码： {{ $parent->verify_code }}
    </div>
    <h3>{{$data['title']}} </h3>
    @if($data['members']->count()>0)
        @foreach($data['members'] as $member)
            <img src="{{ $member['avatar'] }}" class="receiver"/>
        @endforeach

        @if($data['members']->count() >=5 )
            <img src="/img/shenglve.png" style="height:4px; width: auto"/>
        @endif
    @endif
    @if($receive_red_bags_count)
        <p style="text-align: center;color:lightgray;margin-top: .12rem">
            <span> 已有{{ $data['joinCount']  }}人参与了群拍，领取红包 <span
                        style="color: #D73030;">{{ $parent->taken_money/100  }}</span>元</span>
        </p>
    @endif
</div>

<div id="con">
    <ul class="list-unstyled cf" id="content" style="margin-bottom:0px;">
        @if ($data['merge_group_shoots']->count() >0 )
            <a href="/mp/video/{{ $data['merge_group_shoots']->first()['id'] }}" style="color:transparent;">
                <li class="merge-video" id="bigli">
                    <img class="img-fit" src="{{$data['merge_group_shoots']->first()['gif_cover_url'] }}"
                         style="height:3.34rem;width:100%">
                    <div class="merge-video-btn">
                        合成视频
                    </div>
                    <p class="merge-content">{{ $parent->merge_shoots_title }}</p>
                </li>
            </a>
        @endif

        <li onclick="JumpIOS('{{ $data['id']}}')" class="forCarema">
            <img src="/img/shape@2x.png"/>
        </li>

        @foreach($data['group_shoots'] as $video)
            <a @if($video['is_luckiest']) class="luckist VideoPlayer" @else class="normal VideoPlayer" @endif
            href="/mp/video/{{ $video['id'] }}">
                <li>
                    <img class="img-fit" src="{{$video['gif_cover_url']}}?imageView2/1/w/136/h/183/interlace/1/q/75"/>
                    <div class="imgForPlay"></div>
                    @if($video['money_gift']>0)
                        <div class="imgForDetail">
                            <div class="imgForDetailTop">
                                <div class="TopIcon">
                                </div>
                                {{$video['money_gift']/100}}元
                            </div>
                            <div class="imgForDetailBottom">
                                @if($video['is_luckiest']) 手气最佳 @else 已领取 @endif
                            </div>
                        </div>
                    @endif
                    {{--<span style="position: absolute;right:7px;bottom:7px;text-align: right;color:#fff;">{{$video['nickname']}}</span>--}}
                </li>
            </a>
        @endforeach
    </ul>
</div>

<div id="footer">
    <img src="/img/logo.png" style="width:.38rem;height:.38rem;margin:.06rem 0 .06rem .1rem;float:left">
    <div style="margin-left:.09rem;font-size:.12rem;padding-top: 0.05rem;float:left">
        <span style="font-size: .16rem;">TOSEE</span><br/>聚会、活动、趴、多人拍摄神器。
    </div>
    <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.tosee.android"
       style="margin:.07rem 0 0 2.65rem;display: block">
        <img src="/img/buynow.png" alt="download" style="z-index:.11;width:.96rem;height:.36rem;">
    </a>
</div>

<script type="text/javascript" src="http://cdn.staticfile.org/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="http://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    function JumpIOS(parentId) {
        window.location.href = 'https://isa.toseeapp.com/groupshoot?id=' + parentId;
    }
</script>
@if($is_wechat_browser)
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
            var title = "一起玩群拍:{{ $title }}";
            var desc = "{{ $desc }}";
            var link = "{{ url() ->current()}}";
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
<script type="text/javascript">

    window.onload = function () {
        getRem(375, 100);

        @if(!$had_red_bags)
            $('#con').css({'padding-bottom': '0.5rem', 'height': 'auto'});
        @endif
        /*var mySwiper = new Swiper('.swiper-container', {
         direction: 'vertical',
         loop: false,
         onInit: function (swiper) { //Swiper2.x的初始化是onFirstInit
         swiperAnimateCache(swiper); //隐藏动画元素
         swiperAnimate(swiper); //初始化完成开始动画
         },
         onSlideChangeEnd: function (swiper) {
         swiperAnimate(swiper); //每个slide切换结束时也运行当前slide动画
         }
         });*/
        $('#kong').css('display', 'block');
        setInterval('removeS()', 4500);
    };
    window.onResize = function () {
        getRem(375, 100)
    };
    var isWechatBrowser = "{{$is_wechat_browser? 1: 0}}";
            {{--var playList = [--}}
            {{--@foreach( $groupShootData as $groupShoot )--}}
            {{--'{{ $groupShoot['videoSrc'] }}',--}}
            {{--@endforeach--}}
            {{--];--}}
    var content = document.getElementById('content');
    var num = content.getElementsByTagName('li');
    var bigli = document.getElementById('bigli');
    if (bigli) {
        num[1].style.borderRight = '1px solid rgb(16,16,17)';
        num[2].style.borderRight = '1px solid rgb(16,16,17)';
    }
    for (var i = 0; i < num.length - 1; i++) {
        if (i % 3 == 0) {
            num[i].style.borderLeft = '1px solid rgb(16,16,17)';
        }
        if ((i + 1) % 3 == 0) {
            num[i].style.borderRight = '1px solid rgb(16,16,17)';
        }
    }
    if (num.length < 9) {
        var howmanyadd = Number(9 - num.length);
        for (var i = 0; i < howmanyadd; i++) {
            var newLi = document.createElement("li");
            newLi.innerHTML = "<div class='defaultStyle'><img src='/img/tosee.png' /><p>TOSEE</p></div>"
            content.appendChild(newLi);
        }
    }
    function getRem(pwidth, prem) {
        var html = document.getElementsByTagName('html')[0];
        var owidth = document.body.clientWidth || document.documentElement.clientWidth;
        html.style.fontSize = owidth / pwidth * prem + 'px';
    }

    function removeS() {
        /* $('.swiper-container').css('display','none');*/
        $('#gif').css('display', 'none');
        $('.forAnimate').css('display', 'none');
        $('#con').css({'padding-bottom': '0.5rem', 'height': 'auto'})
    }
</script>
</body>
</html>
