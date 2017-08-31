<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GroupShootRule
 *
 * @package App\Models
 * @property string canvas_direction
 * @mixin \Eloquent
 */
class GroupShootRule extends Model
{
    //拍摄时长
    const TIME_CONFIG_SIX_SECONDS      = 6;
    const TIME_CONFIG_TEN_SECONDS      = 10;
    const TIME_CONFIG_FIVETEEN_SECONDS = 15;

    //画幅方向
    const CANVAS_DIRECTION_VERTICAL   = 'VERTICAL';
    const CANVAS_DIRECTION_HORIZONTAL = 'HORIZONTAL';

    //摄像头方向
    const CANVAS_DIRECTION_ANY    = 'ANY';
    const CAMERA_DIRECTION_FRONT  = 'FRONT';
    const CAMERA_DIRECTION_BEHIND = 'BEHIND';

    //是否有红包
    const ENABLE_RED_BAG  = 1;
    const DISABLE_RED_BAG = 0;

    //是否允许滤镜
    const ENABLE_CAMERA_FILTER  = 1;
    const DISABLE_CAMERA_FILTER = 0;

    //是否允许配乐
    const ENABLE_MUSIC  = 1;
    const DISABLE_MUSIC = 0;

    //是否允许贴纸
    const ENABLE_STICKER  = 1;
    const DISABLE_STICKER = 0;

    public $guarded = [];

    public static $storeRules = [
        'time'                 => '|numeric|in:6,10,15',
        'cavans_direction'     => '|string|in:VERTICAL,HORIZONTAL',
        'camera_direction'     => '|string|in:FRONT,BEHIND,ANY',
        'enable_red_bag'       => '|numeric|in:0,1',
        'enable_camera_filter' => '|numeric|in:0,1',
        'enable_music'         => '|numeric|in:0,1',
        'enable_sticker'       => '|numeric|in:0,1',
    ];

}

