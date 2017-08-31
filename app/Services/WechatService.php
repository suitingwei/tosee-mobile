<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class WechatService
{

    const ACCESS_TOKEN_KEY = 'mp-user-access-token';

    public static function getWechatMpAccessToken()
    {
        $appid  = env('MP_APP_ID');
        $secret = env('MP_APP_SECRET');
        $code   = Input::get('code');
        $url    = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';

        $response = self::httpRequest('GET', $url);
        if (is_array($response) && isset($response['access_token'])) {
            session(['access_token' => $response]);
            return $response;
        }
        else {
            Log::error(__CLASS__ . " [Get Wechat Openid Error] error: " . json_encode($response) . ' url: ' . $url);
            return false;
        }
    }

    public static function getWechatMpUserInfo($accessToken, $openId)
    {
        if ($userInfo = session('userInfo')) {
            return $userInfo;
        }

        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $accessToken . '&openid=' . $openId . '&lang=zh_CN';

        $response = self::httpRequest('GET', $url);
        if (is_array($response) && !isset($response['errcode'])) {
            session(['userInfo' => $response]);
            return $response;
        }
        else {
            Log::error(__CLASS__ . " [Get Wechat Openid Error] errcode:{$response['errcode']} errmsg:{$response['errmsg']}");
            return false;
        }
    }

    public static function httpRequest($method, $url, $return_array = true)
    {
        $client   = new Client();
        $response = null;
        try {
            $response = $client->request($method, $url);
        } catch (Exception $e) {
            Log::error(__CLASS__ . ' [Http Request] ' . $e->getMessage());
            if (!is_null($response)) {
                Log::error(__CLASS__ . ' [Http Request] raw_body:' . $response->getBody());
            }
        }

        $body = '';
        if (!empty($response)) {
            $body = $response->getBody();
        }

        return json_decode($body, $return_array);
    }

    public static function getWechatJsApiSignPackage()
    {
        $appid = env('MP_APP_ID');

        $ticket    = self::getWechatJsApiTicket();
        $timestamp = time();
        $nonceStr  = md5(time() . uniqid());
        $url       = urldecode(URL::full());

        $string    = "jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);

        $signPackage = [
            'appId'     => $appid,
            'nonceStr'  => $nonceStr,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ];

        return $signPackage;
    }

    public static function getWechatJsApiTicket()
    {
        $cacheKey = 'wechatJsApiTicket';
        if ($ticket = Cache::get($cacheKey)) {
            return $ticket;
        }
        else {
            $token = self::getWechatMpClientAccessToken();

            $url    = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $token . '&type=jsapi';
            $client = new Client();
            $r      = $client->request('GET', $url);
            $body   = $r->getBody();
            $data   = json_decode($body);
            if (isset($data->ticket)) {
                Cache::put($cacheKey, $data->ticket, 100);
                return $data->ticket;
            }

            return null;
        }
    }

    public static function getWechatMpClientAccessToken()
    {
        $appid  = env('MP_APP_ID');
        $secret = env('MP_APP_SECRET');

        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appid . '&secret=' . $secret;

        $client = new Client();
        $r      = $client->request('GET', $url);
        $body   = $r->getBody();
        $data   = json_decode($body, true);
        if ($data && isset($data['access_token'])) {
            $cacheKey = 'wechatMpAccessToken';
            Cache::put($cacheKey, $data['access_token'], 100);
            return $data['access_token'];
        }

        Log::error(sprintf('try to get wechat token failed ' . $body));
        return null;
    }
}
