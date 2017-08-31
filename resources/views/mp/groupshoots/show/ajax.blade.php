<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/mui/dist/css/mui.min.css">
    <link rel="stylesheet" href="http://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .container-fluid {
            padding-left: 0;
            margin: 0 auto;
            width: 100%;
        }

        .cover {
            object-fit: cover;
            /*width: 50px;*/
            /*height: 100px;*/
        }
        .verify-code{
            border: solid 1px black;
            background-color: rgba(0,0,0,0.4);
            -webkit-border-radius:4px;
            -moz-border-radius:4px;
            border-radius:4px;
            text-align:  center;
            font-family: 'PingFangSC-Regular', monospace;
            color: #ffffff;
        }
        .password{
            color: rgb(233,50,50);
            margin-left:auto;
            margin-right:auto;
            display:  block;
        }
        .red-bag {
           width :62px;
            height:79px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row" style="margin-left:0px">
        {{-- 合成视频--}}
        <div class="col-md-12" style="width: 375px;height: 420px;">
            <div class="group-info">
                <img src="" alt="群拍发起者头像" id="parent-group-info-owner-avatar">
                <span id="parent-group-info-owner-name">跟我一起拍</span>
                <span id="parent-group-info-title">祝小明生日快乐</span>
            </div>

            <div class="merged-group">
                <img src="" alt="合成视频封面" id="merged-group-shoot-img" class="cover">
                <span>合成群拍</span>
            </div>

            {{-- 密码--}}
            <div class="verify-code">
                <span class="password">123</span>
                <span>群拍口令</span>
            </div>

            {{--红包--}}
            <div >
                <img src="/images/mobile/groupshoots/red_bag.png" alt="红包" class="red-bag">
            </div>
        </div>
    </div>
    <div class="row">
        {{-- 群拍--}}
        <div class="group-shoots">
            <div class="col-md-6">
                自群拍
            </div>
            <div class="col-md-3">
                自群拍
            </div>
        </div>
    </div>

</div>
</body>
<script src="/mui/dist/js/mui.js"></script>
<script type="text/javascript" src="http://cdn.staticfile.org/jquery/3.1.1/jquery.min.js"></script>
<script>
    var mergedGroupShoot = {};
    var groupShoots = [];
    var hadRedBags = false;
    var members = [];
    var parentGroupShootId = '{{ $groupShootId }}';
    initData();

    function initData() {
        mui.getJSON('http://dev-api.toseeapp.com//v1/mobile/groupshoots/' + parentGroupShootId, {category: 'news'}, function (response) {
                console.log(response)
                hadRedBags = response.data.had_red_bags;
                members = response.data.members;
                mergedGroupShoot = response.data.merge_group_shoots[0];
                drawUI();
            }
        );
    }

    function appendParentGroupShootInfo() {

    }
    function appendMergeShoot() {
        $('#merged-group-shoot-img').attr('src', mergedGroupShoot.gif_cover_url)
    }

    function drawUI() {
        appendParentGroupShootInfo();
        appendMergeShoot();
    }

</script>
</html>
