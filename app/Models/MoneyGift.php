<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

/**
 * App\Models\MoneyGift
 *
 * @property int                                                                   owner_id
 * @property int                                                                   group_shoot_id
 * @property int                                                                   money
 * @property int                                                                   rmb_money
 * @property int                                                                   numbers
 * @property string                                                                out_trade_no
 * @property int                                                                   status
 * @property int                                                                   type
 * @property int                                                                   id
 * @property int                                                                   left_money
 * @property string                                                                out_refund_no
 * @property mixed                                                                 refunded
 * @property int                                                                   $parent_id
 * @property \Carbon\Carbon                                                        $created_at
 * @property \Carbon\Carbon                                                        $updated_at
 * @property int                                                                   $refund_money
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MoneyGift[] $childMoneyGifts
 * @property-read \App\Models\User                                                 $owner
 * @property mixed                                                                 pay_type
 * @property mixed                                                                 channel
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereGroupShootId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereMoney($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereNumbers($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereOutRefundNo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereOutTradeNo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereRefundMoney($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereRefunded($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MoneyGift whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MoneyGift extends Model
{
    const STATUS_SHARE_GIFT_PAID    = 1;  //Share money gift paid.
    const STATUS_SHARE_GIFT_CREATED = 2;  //Share money have benn shared.
    const STATUS_TAKE_MONEY_CREATED = 3;  //Taken money have been taken(not paid)
    const STATUS_TAKE_MONEY_PAID    = 5; //Money paid

    const REFUNDED     = 1;
    const NOT_REFUNDED = 0;

    const TYPE_LUCKY  = 1;   //lucky the money gift.
    const TYPE_AVARGE = 2;   //avarge split the money gift.

    public $guarded = [];

    public $casts = ['money' => 'int'];

    /**
     * Rules for create new money gift.
     * @var array
     */
    public static $storeRules = [
        'pay_type'       => 'required|in:wechat,alipay,applepay,wx',
        'money'          => 'required|numeric',
        'group_shoot_id' => 'required|numeric',
        'type'           => 'required|numeric|in:1,2',
        'numbers'        => 'required|numeric',
        'channel'        => 'in:wx,alipay'
    ];


    /**
     * Is money gift taken by user.
     *
     * @param $userId
     *
     * @return bool
     */
    public function hadTakenByUser($userId)
    {
        return self::where('parent_id', $this->id)->where('owner_id', $userId)->count() > 0;
    }

    /**
     * @param GroupShoot $childGroupShoot
     *
     * @return int
     */
    public function sendMoneyToUser(GroupShoot $childGroupShoot)
    {
        if (!Redis::keys('moneygifts:' . $this->id)) {
            return 0;
        }

        if (!$this->hadTakenByUser($childGroupShoot->owner_id) &&
            ($moneyGiftNumber = Redis::lpop('moneygifts:' . $this->id)) &&
            ($this->refunded == false)
        ) {
            self::create([
                'owner_id'       => $childGroupShoot->owner_id,
                'group_shoot_id' => $childGroupShoot->id,
                'money'          => $moneyGiftNumber,
                'parent_id'      => $this->id,
                'status'         => MoneyGift::STATUS_TAKE_MONEY_CREATED,
            ]);

            return $moneyGiftNumber;
        }
        return 0;
    }

    /**
     * Get all left money.
     * @return int
     */
    public function getLeftMoneyAttribute()
    {
        $paidMoney = $this->childMoneyGifts()->where('status', self::STATUS_TAKE_MONEY_PAID)->sum('money');

        return $this->money - $paidMoney;
    }

    /**
     * Generate the out refunde number.
     * @return string
     */
    public function generateOutRefundNo()
    {
        $outRefundNo = $this->out_trade_no . '-refund';
        $this->update(['out_refund_no' => $outRefundNo]);
        return $outRefundNo;
    }

    /**
     * A money gift may have many child money gifts.
     */
    public function childMoneyGifts()
    {
        return $this->hasMany(MoneyGift::class, 'parent_id', 'id');
    }

    /**
     * Set money gift at refunded status.
     */
    public function setToRefunded()
    {
        $this->update([
            'refunded'     => self::REFUNDED,
            'refund_money' => $this->left_money,
        ]);

        Redis::del('moneygifts:' . $this->id);
    }

    /**
     * A money gift belongs to a user.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function isLucky()
    {
        return $this->type == self::TYPE_LUCKY;
    }

    public function isAvarage()
    {
        return $this->type == self::TYPE_AVARGE;

    }

    public function getRmbMoneyAttribute()
    {
        return $this->money / 100;
    }

    /**
     * Get this money gift's all taken count.
     * Attention,this return the count,not the total money.
     * @return int
     */
    public function receivedCount()
    {
        return self::where('parent_id', $this->id)->count();
    }

    public function scopeTakenMoneyPaid($query)
    {
        return $query->where('status', self::STATUS_TAKE_MONEY_PAID);
    }

    public function scopeTakenMoney($query)
    {
        return $query->whereIn('status', [self::STATUS_TAKE_MONEY_PAID, self::STATUS_TAKE_MONEY_CREATED]);
    }

    /**
     * Determines whether the money gift has benn all taken.
     * @return bool
     */
    public function allTaken()
    {
        return $this->numbers == $this->childMoneyGifts()->takenMoney()->count();
    }
}
