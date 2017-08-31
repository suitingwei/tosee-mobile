<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Mobile_Detect;

class HomeController extends Controller
{

    public function home()
    {
        $detect = new Mobile_Detect;

        $is_mobile = $detect->isMobile();
        if ($is_mobile) {
            return view('indexMobile');
        } else {
            return view('indexDesktop');
        }
    }


    public function enhome()
    {
        $detect = new Mobile_Detect;

        $is_mobile = $detect->isMobile();
        if ($is_mobile) {
            return view('english.indexMobile');
        } else {
            return view('english.indexDesktop');
        }
    }


    public function robot($msgId)
    {

        return view('robot');
    }


    public function download()
    {

        $detect = new Mobile_Detect;

        $isiOS = $detect->isiOS();

        $android_url = 'http://android.myapp.com/myapp/detail.htm?apkName=com.tosee.android';
        $ios_url     = 'https://itunes.apple.com/cn/app/tosee-wan-pai-shi-jie!/id1135326885';

        if ($isiOS) {
            header("location:" . $ios_url);
        } else {
            header("location:" . $android_url);
        }
        exit;
    }


    //展示分享内容详情
    public function shareShow($msgId, $sign)
    {

        $client = new \GuzzleHttp\Client();

        $response = $client->get(env('API_HOST') . '/msg/info?msgId=' . $msgId . '&sign=' . $sign);

        $final_data = $response->getBody();

        if (empty( $final_data )) {
            return 'data empty';
        }

        $final_info        = json_decode($final_data, true);
        $last_info         = [ ];
        $media_type        = $final_info['Result']['MediaType'];
        $last_info['text'] = $final_info['Result']['Text'];
        $last_info['type'] = $media_type == 3 ? $final_info['Result']['Robot'][0]['mediaType'] : $media_type;
        $last_info['url']  = $media_type == 3 ? $final_info['Result']['Robot'][0]['MediaUrl'] : $final_info['Result']['MediaUrl'];

        return view('shareShow', compact([ 'last_info' ]));
    }

    public function agreement()
    {
        return view('agreement');
    }
}
