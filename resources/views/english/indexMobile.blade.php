<!doctype html>
<html  lang="">
<head>
    <meta charset="utf-8">

    <title>Tosee - 玩拍世界！一键拍摄视频、图片变成可与朋友互动的游戏。</title>
    <meta name="keywords" content="小视频,社交,视频聊天,视频游戏,美颜相机,游戏,美女,帅哥,美女主播,两性,恋爱,相亲,赚钱,约会,快手,映客,美拍,女神,视频机器人,多人合拍,红包,广告相机,直播,交友,手机视频,自拍,陌陌">
    <meta name="Description" content=" Tosee - 玩拍世界！一键拍摄视频、图片变成可与朋友互动的游戏。让大家在游戏中找到快乐，一起来玩拍吧！" />
    <meta name="viewport" content="width=400,initial-scale=0.3,user-scalable=no">
    <link rel="icon" href="/images/mobile/favicon.ico" type="image/x-icon">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->

    <script>
        //平台、设备和操作系统
        var system = {
            win: false,
            mac: false,
            xll: false,
            ipad: false
        };
        //检测平台
        var p = navigator.platform;
        system.win = p.indexOf("Win") == 0;
        system.mac = p.indexOf("Mac") == 0;
        system.x11 = (p == "X11") || (p.indexOf("Linux") == 0);
        system.ipad = (navigator.userAgent.match(/iPad/i) != null) ? true : false;
        //跳转语句，如果是手机访问就自动跳转到wap.baidu.com页面
        if (system.win || system.mac || system.xll || system.ipad) {

        } else {

        }
    </script>
    <style>
        body {
            font-family: 'PingFang SC', 'Helvetica Neue', 'Helvetica', 'STHeitiSC-Light', sans-serif, 'Arial';
            margin: 0;
            padding: 0;
            background-color: white;
            /*min-width: 1200px;*/
        }

        #header {
            padding-top: 10px;
            background-color: #15161a;
            text-align: center;
            height: 1120px;
        }

        #header-container {
            background-color: #15161a;
        }

        .container {
            margin: 0 auto;
            text-align: center;
        }

        #header-contents {
            margin: 0 auto;
            background: #15161a url(images/mobile/phone.png) no-repeat;
            background-position: -220px 0;
        }


        #head-content-phone img {
            left: -20px;
        }

        #app-details {
            padding-top: 140px;
            padding-bottom: 124px;
            padding-left: 350px;
            margin: 0 auto;
            width: 55%;
            height: 100%;
            text-align: center;
            color: white;
        }

        h1, h2, h3, h4, h5 {
            font-weight: normal;
            margin: 0;
            padding: 0;
        }

        h2 {
            font-family: PingFangSC-Thin, sans-serif, 'Arial';
        }

        #appname {
            margin-top: -2px;
            font-size: 72px;
            font-family: PingFangSC-Thin, sans-serif, 'Arial';
        }

        #desc1-1 {
            font-size: 100px;
            font-family: PingFangSC-Ultralight;
            margin-top: 100px;
            line-height: 1.1;
        }

        #desc1-2 {
            font-size: 120px;
            font-family: PingFangSC-Ultralight;
            margin-top: -33px;
        }


        #desc2 {
            font-size: 28px;
            font-family: PingFangSC-Ultralight;
            margin-top: 34px;

        }




        #download {
            position: fixed;
            bottom: 0;
            right: 0;
            height: 210px;
            width: 100%;
            opacity: 0.5;
            background-color: white;
        }


        #btn-download {

            font-family: PingFangSC-Thin;
            position: fixed;
            margin: 0 auto;

            height: 172px;
            width: 100%;
            bottom: 0;

            text-align: center;
        }

        #btn-download a {
            display: inline-block;
            width: 520px;
            height: 134px;
            font-size: 46px;
            border-radius: 72px;
            color: white;
            text-decoration: none;
            background-color: #15161a;


        }

        #btn-download-container {
            padding-top: 37px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #btn-download-container * {
            margin: 0;
            padding: 0;
        }

        #btn-download-container img {
            margin-right: 10px;
        }

        #btn-download a:hover {
            color: white;
            margin: 0 auto;
            background-color: #0a0c0f;
        }


        #details h2 {
            font-size: 43px;
            margin-top: 160px;
            border-radius: 80px;
            width: 96%;
        }

        .underline {
            height: 4px;
            width: 32px;
            background-color: #957bdc;
            display: inline-block;
        }

        #footer {
            height: 340px;

            font-family: PingFangSC-Thin;
        }

        .detail-part {
            margin-top: 60px;
        }

        #part1,#part3 {
            display: flex;
            justify-content: center;
        }

        #part1>:not(:last-child) {
            margin-right: 1%;
        }

        #part3>:not(:last-child) {
            margin-right: 1%;
        }

        #part1>img {
            width: 30.666%;
            height: 100%;
        }

        #part2>img {
            width: 94%;
            height: 100%;
        }

        #part3>img {
            width: 18%;
            height: 100%;
        }

    </style>
</head>
<body>

<div id="header">
    <div id="header-container" class="container">
        <div id="header-contents">
            <div id="app-details">
                <div><img src="/images/mobile/logo.png"/></div>
                <h1 id="appname">Tosee</h1>
                <h1 id="desc1-1" class="desc1">Play the world<br/>Quick play</h1>
                <!--<h1 id="desc1-2" class="desc1"></h1>-->
                <h1 id="desc2">A key to shoot video, pictures into a friend<br>to interact with the game</h1>
            </div>

        </div>
    </div>
</div>

<div id="details">

    <div class="container">

        <h2 id="part1-title">See their world</h2>
        <span class="underline"></span>
        <div id="part1" class="detail-part">
            <img src="/images/mobile/show-1-1.png"/>
            <img src="/images/mobile/show-1-2.png"/>
            <img src="/images/mobile/show-1-3.png"/>
        </div>

        <h2>Direct point to point to interact with him</h2>
        <span class="underline"></span>
        <div id="part2" class="detail-part">
            <img src="/images/mobile/show-2-1.png" />
        </div>

        <h2>A key to shoot video, pictures into a friend to<br>interact with the game</h2>
        <span class="underline"></span>

        <div id="part3" class="detail-part">
            <img src="/images/mobile/show-3-1.png" />
            <img src="/images/mobile/show-3-2.png" />
            <img src="/images/mobile/show-3-3.png" />
            <img src="/images/mobile/show-3-4.png" />
            <img src="/images/mobile/show-3-5.png" />

        </div>
    </div>
</div>

<div id="footer">
    &nbsp;
</div>



<div id="download">
</div>
<div id="btn-download" >
    <a class="download" href="http://a.app.qq.com/o/simple.jsp?pkgname=com.tosee.android" target="_blank">
        <div id="btn-download-container">
            <img src="/images/mobile/download.png " /> <span>Download</span>
        </div>
    </a>
</div>




</body>
</html>
