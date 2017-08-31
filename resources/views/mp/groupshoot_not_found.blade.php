<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TOSEE-群拍神器，大家来群拍！</title>
    <link rel="stylesheet" href="http://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">

    <style>
        *{margin:0;padding: 0}
        body{
            background-color: rgb(37,39,45);
            position: relative;
            min-height:6.67rem;
        }

        #footer{
            z-index:11;height:.50rem;background:rgba(255,255,255,0.87);position: fixed;top:0;width:100%;
        }
        #con{
            position: absolute;
            left: 50%;
            top:50%;
            transform: translate(-50%,-50%);
            text-align: center;
            color:rgb(106,108,115);
        }
        #con img{
            width:1.19rem;
            height:.73rem;
        }
        #con #refresh{
            margin: 0 auto;
            width:.85rem;
            height:.29rem;
            border:1px solid rgb(237,73,86);;
            border-radius: .03rem;
            text-align: center;
            line-height: .29rem;
            color:rgb(237,73,86);
        }
    </style>
</head>
<body>
{{--Header of the page--}}

<div id="con">
    <img src="/img/page.png" />
    <p style="margin:.16rem 0 .20rem 0">很遗憾,这个网页打不开了</p>

</div>

<div id="footer">
    <img src="/img/logo.png" style="width:.38rem;height:.38rem;margin:.06rem 0 .06rem .1rem;float:left">
    <div style="margin-left:.09rem;font-size:.12rem;padding: 0;float:left"><span style="font-size: .18rem;line-height: .30rem;">Tosee</span><br/>一键拍摄可与朋友互动的视频
    </div>
    <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.tosee.android" style="margin:.07rem 0 0 2.65rem;display: block">
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
<script>
    function getRem(pwidth,prem){
        var html=document.getElementsByTagName('html')[0];
        var owidth=document.body.clientWidth || document.documentElement.clientWidth;
        html.style.fontSize=owidth/pwidth*prem+'px';
    }

    window.onload=function () {
        getRem(375,100)
    };
    window.onResize=function(){
        getRem(375,100)
    }

</script>

</body>
</html>
