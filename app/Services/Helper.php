<?php

namespace app\Services;

use Validator;
use App\Services\QiniuService;
use Illuminate\Support\Facades\Redis;

class Helper
{
    public static function response($data = [], $code = 200)
    {
        $headers['Content-Type'] = 'application/json; charset=utf-8';

        if ($code == 200) {
            $response = ['code' => $code] + ['data' => $data];
        } else {
            $response = ['code' => $code] + $data;
        }

        return response()->json($response, 200, $headers);
    }

    public static function extendMobileValidator($input)
    {
        Validator::extend('mobile', function ($attribute, $value, $parameters) use ($input) {
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            try {
                $swissNumberStr = $input['code'].' '.$input['mobile'];
                $swissNumberProto = $phoneUtil->parse($swissNumberStr, $input['country']);

                return $phoneUtil->isValidNumber($swissNumberProto);
            } catch (\libphonenumber\NumberParseException $e) {
                return false;
            }
        });
    }

    public static function urlsafe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+','/'),array('-','_'),$data);
        return $data;
    }

    public static function url($uri, $host = '', $scheme = 'http')
    {
        $host = empty($host) ? env('HOST') : $host;

        return $scheme.'://'.$host.'/'.$uri;
    }

    public static function videoUrl($videoKey)
    {
        if (Redis::sismember(QiniuService::STICKER_Id_KEY, $videoKey)) {
            $path = 'sticker/'.$videoKey;
        } elseif (!Redis::sismember(QiniuService::PERSISTENT_Id_KEY, $videoKey)) {
            $path = 'watermark/'.$videoKey;
        } else {
            $path = $videoKey;
        }

        return self::url($path, env('QINIU_VIDEO_DOMAIN'));
    }
}
