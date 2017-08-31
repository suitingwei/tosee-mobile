<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TOSEE - 群拍神器，大家来群拍！</title>
    <link rel="stylesheet" href="http://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">

    <style>
        * {
            margin: 0;
            padding: 0
        }

        body {
            background-color: rgb(16, 16, 17);
        }

        #header a {
            width: .34rem;
            height: .44rem;
            position: absolute;
            right: .39rem;
            top: .5rem;
        }

        #content .merge-video {
            overflow: hidden;
            position: relative;
            width: 2.5rem;
            height: 3.33rem;
        }

        #content .merge-video .merge-video-btn {
            position: absolute;
            left: .1rem;
            top: .10rem;
            height: .26rem;
            /*background: url('/img/merge-video.png') no-repeat;*/
            color: white;
            background-color: rgba(0, 0, 0, 0.4);
            background-size: 100% 100%;
            line-height: .26rem;
            width: .64rem;
            text-align: center;
            font-size: .10rem;
            border-radius: 3px;
            font-weight: 400;
        }

        .img-fit {
            width: 100%;
            height: 1.66rem;
            object-position: center center;
            object-fit: center;
        }

        #content li {
            /*border-bottom: 1px solid rgb(14,15,17);*/
            border-left: 1px solid rgb(14, 15, 17);
            width: 1.25rem;
            height: 1.67rem;
            float: left;
            position: relative;
            border-top: 1px solid rgb(14, 15, 17);
            box-sizing: border-box;
        }

        #forheader {
            box-sizing: border-box;
            -webkit-filter: blur(15px);
            -moz-filter: blur(15px);
            -ms-filter: blur(15px);
            filter: blur(15px);
            top: 0;
            /*background-color: #2E2E2E;*/
            background-size: 100% auto;
            position: absolute;
            width: 109%;
            height: 112%;
            margin: -15px;
            z-index: -2;
        }

        #forheaderBorder {

            background-size: 100% auto;
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -3;
            top: 0
        }

        #canvasForHeader {
            background-color: rgba(30, 33, 38, 0.75);
            background-size: 100% 100%;
            position: absolute;
            width: 100%;
            height: 2.75rem;
            z-index: -1;
            top: 0;
            /* background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 1));
             background: -moz-linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 1));*/
        }

        .owner {
            font-weight: 400;
            font-size: 0.12rem;
            padding: 0.07rem 0 0.05rem 0;
            margin-bottom: 0px;
            color: #fff;
            line-height: .14rem;
        }

        .avatar {
            vertical-align: middle;
            border-radius: .22rem;
            width: .44rem;
            height: .44rem;
            margin-top: .5rem;
        }

        #header {
            text-align: center;
            position: relative;
            height: 2.75rem;
            overflow: hidden;
        }

        #nav {
            padding-bottom: 0.3rem;
            text-align: center;
            /* background-color: rgb(30, 33, 38);*/
        }

        .receiver {
            width: .24rem;
            height: .24rem;
            margin-right: .12rem;
            border-radius: 50%;

        }

        #nav img {
            margin-top: .15rem;

        }

        #nav h3 {
            color: white;
            margin: 0 0 .14rem 0;
            font-size: .20rem;
            font-weight: 500;
        }

        .forCarema {
            background-color: rgb(30, 33, 38);
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

            padding-right: .03rem;
            height: .20rem;
            background-color: rgba(255, 0, 0, 0.76);
            line-height: .20rem;
            text-align: center;
            padding-left: .18rem;
            font-weight: 400;
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
            color: rgb(10, 10, 11);
            line-height: .20rem;
            text-align: center;
            color: red;
            font-weight: 400;
        }

        /*#content .luckist .imgForDetailBottom:before {
            content: '手气最佳';
            position: absolute;
        }*/

        #content .normal .imgForDetailTop {
            height: .20rem;
            background-color: rgba(255, 0, 0, 0.76);
            line-height: .20rem;
            text-align: center;
            padding-left: .18rem;
            font-weight: 400;
        }

        #content .normal .TopIcon {
            width: .12rem;
            height: .14rem;
            position: absolute;
            left: .06rem;
            top: .03rem;
            background: url('/img/white.png') no-repeat;
            background-size: 100% 100%;
            font-weight: 400;
        }

        #content .normal .imgForDetailBottom {
            height: .20rem;
            background-color: rgba(255, 255, 255, 0.9);
            color: rgb(10, 10, 11);
            line-height: .20rem;
            text-align: center;
            font-weight: 400;
        }

        /*#content .normal .imgForDetailBottom:before {
            content: '已领取';
            position: absolute;
        }*/

        #con {
            overflow: hidden;
            /*padding-bottom: .5rem;*/

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
            /*position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            text-align: center;*/
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
            left: 0;
            top: 0;
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
            width: .68rem;
            height: 0.44rem;
            color: rgb(212, 212, 212);
            text-align: center;
            background: rgb(60, 60, 60);
            font-size: 0.12rem;
            position: absolute;
            left: .2rem;
            top: .5rem;
            border-radius: 3px;
        }

        .verify_code p {
            margin-bottom: 0;
            line-height: .22rem;
        }

        .dropload-up, .dropload-down {
            position: relative;
            height: 0;
            overflow: hidden;
            font-size: 12px;
            /* 开启硬件加速 */
            margin-bottom: .5rem;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
        }

        .dropload-down {
            height: 30px;
            background-color: rgb(31, 31, 33);
            background: rgb(31, 31, 33);
            border-top: 1px solid rgb(16, 16, 17);
        }

        .dropload-refresh, .dropload-update, .dropload-load, .dropload-noData {
            height: 30px;
            line-height: 30px;
            text-align: center;
        }

        .dropload-load .loading {
            display: inline-block;
            height: 15px;
            width: 15px;
            border-radius: 100%;
            margin: 6px;
            border: 2px solid #666;
            border-bottom-color: transparent;
            vertical-align: middle;
            -webkit-animation: rotate 0.75s linear infinite;
            animation: rotate 0.75s linear infinite;
        }

        @-webkit-keyframes rotate {
            0% {
                -webkit-transform: rotate(0deg);
            }
            50% {
                -webkit-transform: rotate(180deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            50% {
                transform: rotate(180deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body id="kong" style="display: none;overflow: auto">

<div id="header">
    <div id="forheader">
    </div>
    <div id="forheaderBorder">
    </div>
    <div id="canvasForHeader"></div>
    {{--<img src="/img/shadow.png "  style="position: absolute;bottom: -1px;left:0;width: 100%;"/>--}}
    <div id="nav">

    </div>
</div>


<div id="con" style="min-height: 4rem;">
    <ul class="list-unstyled cf" id="content" style="margin-bottom:0px;">

    </ul>
</div>


<div id="footer">
    <img src="/img/logo.png" style="width:.38rem;height:.38rem;margin:.06rem 0 .06rem .1rem;float:left">
    <div style="margin-left:.09rem;font-size:.12rem;padding-top: 0.05rem;float:left">
        <span style="font-size: .16rem;">TOSEE</span><br/>聚会、活动、趴、多人拍摄神器。
    </div>
    <a onclick="JumpAppOrAppStore()" style="margin:.07rem 0 0 2.65rem;display: block">
        <img src="/img/buynow.png" alt="download" style="z-index:.11;width:.96rem;height:.36rem;">
    </a>
</div>


<script type="text/javascript" src="http://cdn.staticfile.org/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="http://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    function JumpAppOrAppStore() {
        {{--var u = navigator.userAgent, app = navigator.appVersion;--}}
        {{--var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端--}}
        {{--if (isiOS) {--}}
            {{--var appUrl = "tosee://groupshoot?id=" + '{{ $parent_id }}';--}}
            {{--var appstoreUrl = "http://a.app.qq.com/o/simple.jsp?pkgname=com.tosee.android";--}}
            {{--window.location.href = appUrl;--}}
            {{--window.setTimeout(function () {--}}
                {{--window.location.href = appstoreUrl;--}}
            {{--}, 500)--}}
        {{--}--}}

        JumpIOS('{{ $parent_id }}');
    }
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


    window.onResize = function () {
        getRem(375, 100)
    };
    var content = document.getElementById('content');
    var num = content.getElementsByTagName('li');
    var bigli = document.getElementById('bigli');
    if (bigli) {
        num[1].style.borderRight = '1px solid rgb(14,15,17)';
        num[2].style.borderRight = '1px solid rgb(14,15,17)';
    }
    for (var i = 0; i < num.length - 1; i++) {
        if (i % 3 == 0) {
            num[i].style.borderLeft = '1px solid rgb(14,15,17)';
        }
        if ((i + 1) % 3 == 0) {
            num[i].style.borderRight = '1px solid rgb(14,15,17)';
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
        //$('#con').css({'height': 'auto'})
        $('#kong').css({'height': 'auto', 'overflow': 'scroll'})
        console.log(1);
        clearInterval();
    }


</script>
<script src="/js/dropload.min.js"></script>
<script>

    var userId = location.href.split('/')[5];
    userId = userId.split('?')[0];
    var biggestid = 0;
    getajax(userId, 0);
    function getajax(userId, startId) {
        $.ajax({
            type: 'get',
            url: 'http://dev-api.toseeapp.com//v1/mobile/groupshoots/' + userId,
            data: {'start_id': startId},
            xhrFields: {withCredentials: true},
            success: function (res) {
                console.log(res);
                var content = document.getElementById('content');
                var group_shoots = res.data.group_shoots;
                if (startId == 0) {
                    //gif 动画
                    if (res.data.had_visited == false && res.data.had_red_bags == true) {
                        var divforani = document.createElement('div');
                        divforani.innerHTML = '<div class="forAnimate"></div><img src="http://v0.toseeapp.com/groupshoot-effect.gif" style="position:absolute;z-index:199;width:100%;top:-0.5rem;" id="gif"/>'
                        kong.appendChild(divforani);
                    }

                    //合成视频
                    var merge_group_shoot = res.data.merge_group_shoots[0];
                    if (merge_group_shoot) {
                        var mergeLi = document.createElement('a');
                        mergeLi.innerHTML =
                            '<a href="/mp/video/' + merge_group_shoot.id + '" style="color:transparent;"><li class="merge-video" id="bigli"> <img class="img-fit" src="' + merge_group_shoot.gif_cover_url + '" style="height:3.34rem;width:100%"> <div class="merge-video-btn">合成视频 </div></li></a>';
                        content.appendChild(mergeLi);
                    }

                    //拍摄那个按钮
                    var caremLi = document.createElement('a');
                    caremLi.innerHTML = '<li onclick="JumpIOS(' + userId + ')" class="forCarema"> <img src="/img/shape@2x.png"/> </li>';
                    content.appendChild(caremLi);

                    //header 里面的东西
                    var own = res.data.owner;
                    var had_red_bags = res.data.had_red_bags;
                    var group_code = '';
                    if (had_red_bags) {
                        had_red_bags = '<a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.tosee.android"> <img src="/img/qianghongbao.png" alt="coupon-button" style="width:100%;height:100%"></a>';

                    }
                    else {
                        had_red_bags = '';
                    }
                    group_code = '<div class="verify_code"><p style="border-bottom:1px solid rgba(27,27,27,0.45)">群拍密码</p><p>' + res.data.verify_code + '</p></div>';
                    var imgforheader = document.createElement('div');
                    imgforheader.innerHTML = ' <img src="' + own.avatar + '" class="avatar"/> <p class="owner">' + own.nickname + '</p><p><img src="/img/bitmap@2x.png" style="width:.29rem;height:auto"></p>' + had_red_bags + group_code;
                    $('#header').prepend(imgforheader);
                    $('#forheader').css({
                        'background': 'url(' + own.avatar + ') no-repeat left center',
                        'background-size': '100% auto'
                    });
                    $('#forheaderBorder').css({
                        'background': 'url(' + own.avatar + ') no-repeat left center',
                        'background-size': '100% auto'
                    });

                    //nav 里的东西
                    var divfornav = document.createElement('div');
                    var who_take = document.createElement('div');
                    var memebers = res.data.members;
                    divfornav.innerHTML = '<h3>' + res.data.title + '</h3>';
                    nav.appendChild(divfornav);

                    if (res.data.joinCount >= 0) {
                        var p = document.createElement('p');
                        p.setAttribute('style', 'text-align: center;color:lightgray;margin: .12rem 0 0 0;font-size:.13rem;');
                        var moneyGiftCount = '';
                        if (res.data.moneyGiftCount) {
                            moneyGiftCount = '，领取红包<span style="color: #D73030;">' + res.data.moneyGiftCount / 100 + '</span>元</span>'
                        }
                        p.innerHTML = '<span> 已有' + res.data.joinCount + '人参与了群拍' + moneyGiftCount;
                        nav.appendChild(p)
                    }
                    if (memebers.length > 0 && memebers.length < 5) {
                        for (var j = 0; j < memebers.length; j++) {
                            var img = document.createElement('img');
                            img.setAttribute('src', res.data.members[j].avatar);
                            img.setAttribute('class', 'receiver');
                            nav.appendChild(img);
                        }
                    }
                    if (res.data.joinCount >= 5) {
                        for (var j = 0; j < 5; j++) {
                            var img = document.createElement('img');
                            img.setAttribute('src', res.data.members[j].avatar);
                            img.setAttribute('class', 'receiver');
                            nav.appendChild(img);
                        }
                        var shenglve = document.createElement('img');
                        shenglve.setAttribute('src', '/img/shenglve.png');
                        shenglve.setAttribute('style', 'height:4px; width: auto');
                        nav.appendChild(shenglve);
                    }
                }

                //下面的li
                filled(group_shoots);

                //window.onload 里的东西
                getRem(375, 100);

                if (document.getElementById('gif')) {
                    $('#kong').css({'height': '6.67rem', 'overflow': 'hidden'})
                    setTimeout('removeS()', 1);
                }

                $('#kong').css('display', 'block');
                $('#kong').dropload({

                    scrollArea: window,
                    domDown: {
                        domClass: 'dropload-down',
                        domRefresh: '<div class="dropload-refresh">↑上拉加载更多</div>',
                        domLoad: '<div class="dropload-load"><span class="loading"></span>加载中...</div>',
                        domNoData: ''
                    },

                    loadDownFn: function (me) {
                        $.ajax({
                            type: 'GET',
                            url: 'http://dev-api.toseeapp.com//v1/mobile/groupshoots/' + userId,
                            data: {'start_id': biggestid},
                            success: function (res) {

                                var group_shoots = res.data.group_shoots;
                                if (group_shoots == '') {
                                    filled(group_shoots);
                                    me.lock();
                                    // 无数据
                                    me.noData();
                                }
                                filled(group_shoots);
                                // 每次数据加载完，必须重置

                                me.resetload();
                            },
                            error: function (xhr, type) {

                                // 即使加载出错，也得重置
                                me.resetload();
                            }
                        });
                    },
                    threshold: 50
                });

                if (num.length < 9) {
                    var howmanyadd = Number(9 - num.length);
                    for (var i = 0; i < howmanyadd; i++) {
                        var newLi = document.createElement("li");
                        newLi.innerHTML = "<div class='defaultStyle'><img src='/img/default.png' style='width: 100%;height:100%'/></div>";
                        /*"<div class='defaultStyle'><img src='/img/tosee.png' /><p>TOSEE</p></div>"    */
                        content.appendChild(newLi);
                    }
                }
            }
        });
    }
    function filled(group_shoots) {
        //console.log(group_shoots);
        if (group_shoots == '') {

            var howmanyli = content.getElementsByTagName('li');
            howmanyli = howmanyli.length;
            if (howmanyli % 3 == 1) {
                for (var i = 0; i < 2; i++) {
                    console.log(1);
                    var kongli = document.createElement('li');
                    kongli.innerHTML = "<div class='defaultStyle'><img src='/img/default.png' style='width: 100%;height:100%'/></div>"
                    content.appendChild(kongli);
                }
            }
            if (howmanyli % 3 == 2) {
                var kongli = document.createElement('li');
                kongli.innerHTML = "<div class='defaultStyle'><img src='/img/default.png' style='width: 100%;height:100%'/></div>"
                content.appendChild(kongli);
            }
            return '';
        }
        biggestid = group_shoots[0].id;
        if (group_shoots[0].id == userId) {
            biggestid = 999999999;
        }
        var text = '已领取';
        for (var i = 0; i < group_shoots.length; i++) {

            var li = document.createElement('li');
            var status = '';

            if (i >= 1) {
                if (group_shoots[i].id < biggestid) {
                    biggestid = group_shoots[i].id
                }
            }
            if (group_shoots[i].is_luckiest) {
                li.setAttribute('class', 'luckist');
                text = '手气最佳';
            }
            else {
                li.setAttribute('class', 'normal');
                text = '已领取';
            }

            if (group_shoots[i].money_gift > 0) {
                var money = Number(group_shoots[i].money_gift / 100);
                var MoneyB = Number(Math.floor(money));
                var MoneyA = (money - MoneyB).toFixed(2);
                MoneyA = MoneyA.split('.')[1];
                status = '<div class="imgForDetail">' +
                    '<div class="imgForDetailTop">' +
                    '<div class="TopIcon"></div>' +
                    '<span style="font-size:.16rem;">' + MoneyB + '</span>.<span style="font-size:.10rem;">' + MoneyA + '</span></div>' +
                    '<div class="imgForDetailBottom">' + text + '</div>' +
                    '</div>'
            }

            li.innerHTML =
                '<a href="/mp/video/' + group_shoots[i].id + '" class="VideoPlayer">' +
                '<img class="img-fit" src="' + group_shoots[i].gif_cover_url + '?imageView2/1/w/136/h/183/interlace/1/q/75" />' +
                '<div class="imgForPlay"></div>' + status +

                '</a>';
            content.appendChild(li);

        }


    }
    //$('#footer').click(function(){console.log(kong.scrollTop);})
</script>
</body>
</html>
