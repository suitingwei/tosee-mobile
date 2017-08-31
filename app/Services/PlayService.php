<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class PlayService
{
    const PLAY_USERS_KEY   = 'mp-play-users';
    const PLAY_USER_ID_KEY = 'mp-play-users-opneid';
    const PLAY_PRAISE_KEY  = 'mp-play-praise';

    public static function getJoinUser($playId, $offset = 0, $limit = 10)
    {
        $playUserIdKey = self::getPlayUserIdKey($playId);
        if ($openids = Redis::lrange($playUserIdKey, $offset, $limit)) {
            $playUserKey = self::getPlayUserKey($playId);
            return Redis::hmget($playUserKey, $openids);
        }
    }

    public static function setJoinUser($playId, $openid, $userInfo)
    {
        $playUserKey   = self::getPlayUserKey($playId);
        $playUserIdKey = self::getPlayUserIdKey($playId);
        if (!Redis::hexists($playUserKey, $openid)) {
            Redis::rpush($playUserIdKey, $openid);
        }

        return Redis::hset($playUserKey, $openid, json_encode($userInfo));
    }

    public static function setPraise($playId, $openid)
    {
        $praiseKey = self::getPlayPraiseKey($playId);
        if (Redis::sismember($praiseKey, $openid)) {
            Redis::srem($praiseKey, $openid);
        } else {
            Redis::sadd($praiseKey, $openid);
        }

        return true;
    }

    public static function getPraise($playId, $openid)
    {
        $praiseKey = self::getPlayPraiseKey($playId);
        return Redis::sismember($praiseKey, $openid);
    }

    public static function getPlayUserIdKey($playId)
    {
        return self::PLAY_USER_ID_KEY . ':' . $playId;
    }

    public static function getPlayUserKey($playId)
    {
        return self::PLAY_USERS_KEY . ':' . $playId;
    }

    public static function getPlayPraiseKey($playId)
    {
        return self::PLAY_PRAISE_KEY . ':' . $playId;
    }
}
