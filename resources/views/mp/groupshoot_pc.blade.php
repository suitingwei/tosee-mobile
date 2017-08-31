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
    <link rel="stylesheet" href="{{ url('assest/mp/css/groupshoot.css') }}">
    <style>
        #content li.merge-video {
            overflow: hidden;
        }

        .img-fit {
            width: 100%;
            height: 100%;
            object-position: center center;
            object-fit: cover;
        }

        .col-sm-3 {
            padding-left: 0;
            padding-right: 0;
        }
    </style>
</head>
<body style="width: 100%;">
<div class="container">
    <img src="{{ $avatarUrl }}" width="75px" height="75px" class="img-circle center-block"/>
    <p style="padding-top:5px;color:#fff;" class="text-center"><span>我们一起来群拍：</span></p>
    <p class="text-center" style="color: #fff;">{{$title}}<br/>
        <span style="display:block;">已有
        <span style="color:#ed4956;">{{$join_users_count}}</span>人参与
            @if($delivered_money > 0)，领取红包 <span style="color:#ed4956;">{{$delivered_money}}</span>元@endif
    </span>
    </p>
    <div id="code" style="margin-left:auto; margin-right: auto;">
        <span>红包密码：</span>
        <span>{{ $verifyCode }}</span>
    </div>

    @if ($mergeGroupShootData)
        <div class="row" style="margin-top:2%">
            <div class="col-sm-12 "
                 style="height: 400px;width: 100%;background: url('{{$mergeGroupShootData['videoCoverSrc']}}') no-repeat 0 50%;background-size: 100% auto;"
                 onclick="function jumpToMergeVideo() {
                         window.location.href='/mp/video/{{$mergeGroupShootData['id']}}'; }
                         jumpToMergeVideo()">
                <img src="/img/merge-video.png" style="position:absolute; left:0; top:0" height="42px">
                <span style="color: #ffffff;position:absolute;left: 2%;font-size: 16px;top: 10px;letter-spacing: 1px">合成视频</span>
            </div>
        </div>
    @endif
    <div class="row" style="margin-top: 2%;">
        @foreach( $groupShootData as $index => $groupShoot)
            <div class="col-sm-3">
                <a style="color:transparent;"
                   @if(isset($groupShoot['id'])) href="/mp/video/{{$groupShoot['id']}}"
                   @else href="javascript:void(0);"
                        @endif >
                    <div order="{{ $index }}"></div>
                    @if($hasMoneyGift && $groupShoot['money']>0)
                        <span style="position: absolute;left: 21%;bottom: 0%; color: #FFFFFF;">
                            <img src="/img/money-gift-icon.png" width="17.5px" height="16px"/>
                            <span>领取红包{{ $groupShoot['money']/100 }}元</span>
                        </span>
                    @endif
                    <img class="img-fit" src="{{ $groupShoot['videoCoverSrc'] }}">
                </a>
            </div>
        @endforeach
    </div>
</div>
<div id="footer" style="height :100px">
    <div class="footer-download">
        <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.tosee.android">
            <img src="/img/pc-app-download.jpg" alt="download">
        </a>
    </div>
</div>

<script type="text/javascript" src="http://cdn.staticfile.org/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="http://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">
    var isWechatBrowser = "{{$isWechatBrowser? 1: 0}}";
    var playList = [
        @foreach( $groupShootData as $groupShoot )
            '{{ $groupShoot['videoSrc'] }}',
        @endforeach
    ];
</script>
<script type="text/javascript" src="{{ url('assest/mp/js/groupshoot.js') }}"></script>
</body>
</html>
