<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <style id="style-1-cropbar-clipper">/* Copyright 2014 Evernote Corporation. All rights reserved. */
        .en-markup-crop-options {
            top: 18px !important;
            left: 50% !important;
            margin-left: -100px !important;
            width: 200px !important;
            border: 2px rgba(255, 255, 255, .38) solid !important;
            border-radius: 4px !important;
        }

        .en-markup-crop-options div div:first-of-type {
            margin-left: 0px !important;
        }
    </style>
</head>
<script src="http://v0.toseeapp.com/css/jquery-latest.min.js"></script>
<script src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script>
<script>
    function isWeiXin() {
        var ua = window.navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == 'micromessenger') {
            return true;
        } else {
            return false;
        }
    }
    function autoPlayAudio1(video_key) {
        if (isWeiXin()) {
            wx.ready(function () {
                $.get('/mp/video_next/' + video_key, function (res) {
                    if (res.video_key == "") {
                        window.location = 'http://' + window.location.hostname + '/mp/groupshoots/' + res.parent_id;
                    }
                    if (res.msg == 'end') {
                        $('#video_id').attr('value', res.id);
                        $("#video_key").attr('src', "http://v0.toseeapp.com/" + res.video_key);
                        $('#video_').load();
                        $('body').animate({opacity: 1}, 1500, function () {
                            document.getElementById('video_').play();
                        });
                    } else {
                        $('#video_').pause();
                    }
                });
            });
        } else {
            $.get('/mp/video_next/' + video_key, function (res) {
                if (res.video_key == '') {
                    window.location = 'http://' + window.location.hostname + '/mp/groupshoots/' + res.parent_id;
                }
                if (res.msg == 'end') {
                    $('#video_id').attr('value', res.id);
                    if (res.type == 2) {
                        $("#video_key").attr('src', "http://v0.toseeapp.com/merge/" + res.video_key);
                    } else {
                        $("#video_key").attr('src', "http://v0.toseeapp.com/" + res.video_key);
                    }
                    $('#video_').attr('poster', 'http://s.toseeapp.com/gif/' + res.video_key);
                    $('#video_').load();
                    $('body').animate({opacity: 1}, 1500, function () {
                        document.getElementById('video_').play();
                    });
                }
            });
        }
    }
    function play_next() {
        var video_id = $('#video_id').val();
        $('body').animate({opacity: 0}, 1000, function () {
            autoPlayAudio1(video_id);
        });
    }
    function autoPlayStartVideo() {
        $('body').animate({opacity: 1}, 1500, function () {
            console.log("video_key:" + video_key);
            wx.config({
                debug: false,
                appId: 'wx59498094aabe079b',
                timestamp: 1486606305,
                nonceStr: 'EnEkeVS1ajrYKSl1',
                signature: '7882eaf994ba3fe4af071a429c7f70be',
                jsApiList: []
            });
            if (isWeiXin()) {
                wx.ready(function () {
                    document.getElementById('video_').play();
                });
            } else {
                document.getElementById('video_').play();
            }
        });
    }
</script>

<body style="margin: 0px;background-color: #1c1b21;opacity:0;width:auto;height:100%"
      onload="autoPlayStartVideo('start');">
<video id="video_"
       controls="controls"
       autoplay name="media"
       onended="javascript:play_next();"
       webkit-playsinline="true"
       x-webkit-airplay="true"
       x5-video-player-type="h5"
       x5-video-player-fullscreen="true"
       style="object-fit:fill;" width="100%" height="100%" preload="metadata" poster="">
    <source id="video_key" @if($group_shoot->type == 1) src="http://v0.toseeapp.com/{{$group_shoot->video_key}}"
            @else  src="http://v0.toseeapp.com/{{$group_shoot->video_key}}" @endif type="video/mp4">
    <input type="hidden" id="video_id" value="{{$group_shoot->id}}"/>
</video>
</body>
</html>

