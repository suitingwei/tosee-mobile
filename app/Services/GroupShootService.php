<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class GroupShootService
{
    public static function makeVerifyCode()
    {
        $key = 'groupshootverifycodes';

        while (true) {
            $code = rand(100000, 999999);

            if (!Redis::sismember($key, $code)) {
                Redis::sadd($key, $code);

                return $code;
            }
        }
    }

}
