<?php

namespace App\Services;

use Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;
use Log;
use Session;

class UserService
{

    const ACCESS_TOKEN_KEY = 'mp-user-info';

    public static function set($openid, $info)
    {

    }

    public static function get($openid)
    {

    }
}
