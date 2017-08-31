<?php

namespace App\Services;

use App\Models\MoneyGift;
use App\Models\MoneyTransfer;
use Pingpp\Charge;
use Pingpp\Pingpp;
use Pingpp\Transfer;

class PingPPService
{
    const CHANNEL_ALIPAY = 'alipay';

    const CHANNEL_WECHAT = 'wx';

    const TRANSFER_CHANNEL_WX_PUB = 'wx_pub';

    const TRANSFER_TYPE = 'b2c';

    /**
     * @param MoneyGift $moneyGift
     *
     * @param string    $channel
     *
     * @return mixed
     */
    public static function createChargeForMoneyGift(MoneyGift $moneyGift)
    {
        Pingpp::setApiKey(env('PINGPP_KEY'));

        return Charge::create([
            'order_no'  => $moneyGift->out_trade_no,
            'app'       => ['id' => env('PINGPP_APP_ID')],
            'channel'   => $moneyGift->channel,
            'amount'    => $moneyGift->money,
            'client_ip' => '127.0.0.1',
            'currency'  => 'cny',
            'subject'   => 'TOSEE红包',
            'body'      => 'TOSEE红包',
            'extra'     => [],
        ]);
    }

    /**
     * @param MoneyGift $moneyGift
     */
    public static function refundMoneyGift(MoneyGift $moneyGift)
    {
        Pingpp::setApiKey(env('PINGPP_KEY'));

        $charge = Charge::retrieve($moneyGift->out_trade_no);

        return $charge->refunds->create(['description' => '超时红包退款']);
    }

    /**
     * Transfor user owned money gift.
     *
     * @param $openId
     * @param $money
     *
     * @return Transfer
     */
    public static function transferEarnedMoneyGift($openId, $money)
    {
        Pingpp::setApiKey(env('PINGPP_KEY'));

        $orderNumber = 'wechattransfer' . time();

        MoneyTransfer::create([
            'transfer_order_no' => $orderNumber,
            'amount'            => $money,
            'channel'           => self::TRANSFER_CHANNEL_WX_PUB,
            'type'              => self::TRANSFER_TYPE,
            'open_id'           => $openId,
        ]);

        return Transfer::create([
            'order_no'    => $orderNumber,
            'app'         => ['id' => env('PINGPP_APP_ID')],
            'recipient'   => $openId,
            'amount'      => $money,
            'currency'    => 'cny',
            'type'        => self::TRANSFER_TYPE,
            'channel'     => self::TRANSFER_CHANNEL_WX_PUB,
            'description' => 'TOSEE红包'
        ]);
    }

}
