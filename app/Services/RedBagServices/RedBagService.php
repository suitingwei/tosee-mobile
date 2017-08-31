<?php
namespace App\Services\RedBagServices;

/**
 * Class RedBagService
 * @package App\Services\RedBagService
 */
class RedBagService
{
    /**
     * @param $total
     * @param $num
     *
     * @return array
     */
    public static function generateRandomRedBags($total, $num)
    {
        $max = $total / 3;
        $min = 0.01;

        #总共要发的红包金额，留出一个最大值;
        $total        = $total - $max;
        $reward       = new BasicLuckRedBagAlgorithmService();
        $result_merge = $reward->splitReward($total, $num, $max, $min);
        sort($result_merge);
        $result_merge[1] = $result_merge[1] + $result_merge[0];
        $result_merge[0] = $max * 100;
        foreach ($result_merge as &$v) {
            $v = floor($v) / 100;
        }
        Shuffle($result_merge);
        return $result_merge;
    }

    /**
     * @param $totalInYuan
     * @param $number
     * @linke http://www.helloweba.com/view-blog-313.html
     *
     * @return array
     * @return array
     */
    public static function generateRandomRedBagsUseRand($totalInYuan, $number)
    {
        $min    = 1;
        $moneys = [];
        for ($i = 1; $i < $number; $i++) {
            $safe_total  = ($totalInYuan - ($number - $i) * $min) / ($number - $i);//随机安全上限
            $money       = mt_rand($min, $safe_total);
            $totalInYuan = $totalInYuan - $money;

            $moneys[] = $money;
            echo '第' . $i . '个红包：' . $money . ' 分 ，余额：' . $totalInYuan . ' 分 ';
        }
        $moneys [] = $totalInYuan;
        echo '第' . $number . '个红包：' . $totalInYuan . ' 分,余额：' . ' 0分 ';
        return $moneys;
    }
}
