<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TOSEE 玩拍世界！{{$last_info['text']}}</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
        }

        div {
            width: 100%;
            height: 100%;
            margin: auto;
            display: flex;
        }

        video, img {
            width: 100%;
            height: 100%;
            margin: auto;
        }
    </style>
</head>
<body>
<div>
    @if($last_info['type'] == 1)
        <img src="{{$last_info['url']}}" alt="">
    @else
        <video autoplay controls>
            <source src="{{$last_info['url']}}" type="video/mp4">
        </video>
    @endif
</div>
</body>
</html>