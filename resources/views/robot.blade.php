<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI机器人</title>
    <link rel="stylesheet" href="/css/home.css">
    <script src="http://cdn.static.runoob.com/libs/jquery/1.10.2/jquery.min.js">
    </script>
</head>
<body>

<div class="container">

    <div class="box show">
        <video class="c1" id="c1id" width="1080" controls>
            <source src="http://www.runoob.com/try/demo_source/movie.mp4" type="video/mp4">
        </video>
        <video class="c2" id="c2id" width="1080" controls>
            <source src="http://www.runoob.com/try/demo_source/mov_bbb.mp4" type="video/mp4">
        </video>
        <video class="c3" id="c3id" width="1080" controls>
            <source src="http://www.runoob.com/try/demo_source/movie.mp4" type="video/mp4">
        </video>
        <video class="c4" id="c4id" width="1080" controls>
            <source src="http://www.runoob.com/try/demo_source/mov_bbb.mp4" type="video/mp4">
        </video>
        <img class="c5" id="c5id" src="/images/robot.png" alt="">
        <img class="c6" id="c6id" src="/images/robot.png" alt="">

        <div id="tip" style="display: none;">
            <div class="profile">
                <img src="/images/touxiang.png" alt="" style="width: 150px;border-radius: 75px;">
                <span class="username">泡泡小斌</span>
            </div>
            <span class="greeting">Hello!很高兴认识您，请输入以下关键字试试：</span>
            <ul class="commands">
                <li class="command" id="c1"><span>早餐</span></li>
                <li class="command" id="c2"><span>安静大海</span></li>
                <li class="command" id="c3"><span>打发点咯</span></li>
                <li class="command" id="c4"><span>我稀罕你</span></li>
                <li class="command" id="c5"><span>点我试试</span></li>
            </ul>
        </div>
    </div>

    <div class="box console">
        <img class="column is-narrow robot" src="/images/little_robot.png" alt="" style="height: 150px;">
        <input id="keyword" class="column" type="text">
    </div>

</div>
</body>
<script src="/js/home.js"></script>
</html>
