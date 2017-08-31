<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TOSEE - 玩拍世界</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    @if(env('APP_DEBUG'))
    <link rel="stylesheet" href="{{ url('assest/mp/css/play.css') }}">
    @else
    <link rel="stylesheet" href="http://s0.toseeapp.com{{ elixir('css/play.css') }}">
    @endif
</head>
<body>
    <div id="join-user" style="top: 0px; padding-top: 0px;">
        <div class="users">
            @if($joinUsers[0] != null)
            @foreach( $joinUsers as $user)
            @php
                $user = json_decode($user);
            @endphp
            <div class="user"><img src="{{ $user->headimgurl }}" width="50px" height="50px" class="img-circle"><br /><div class="name">{{ $user->nickname }}</div></div>
            @endforeach
            @endif
        </div>
    </div>
</body>
</html>
