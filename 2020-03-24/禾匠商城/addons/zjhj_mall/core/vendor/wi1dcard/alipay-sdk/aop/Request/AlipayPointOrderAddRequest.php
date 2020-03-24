<?php
/**
 * ALIPAY API: alipay.point.order.add request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-11 11:00:47
 */

namespace Alipay\Request;

class AlipayPointOrderAddRequest extends AbstractAlipayRequest
{
    /**
     * 向用户展示集分宝发放备注
     **/
    private $memo;
    /**
     * isv提供的发放订单号，由数字和字母组成，最大长度为32位，需要保证每笔订单发放的唯一性，支付宝对该参数做唯一性校验。如果订单号已存在，支付宝将返回订单号已经存在的错误
     **/
    private $merchantOrderNo;
    /**
     * 发放集分宝时间
     **/
    private $orderTime;
    /**
     * 发放集分宝的数量
     **/
    private $pointCount;
    /**
     * 用户标识符，用于指定集分宝发放的用户，和user_symbol_type一起使用，确定一个唯一的支付宝用户
     **/
    private $userSymbol;
    /**
     * 用户标识符类型，现在支持ALIPAY_USER_ID:表示支付宝用户ID,ALIPAY_LOGON_ID:表示支付宝登陆号
     **/
    private $userSymbolType;

    public function setMemo($memo)
    {
        $this->memo = $memo;
        $this->apiParams['memo'] = $memo;
    }

    public function getMemo()
    {
        return $this->memo;
    }

    public function setMerchantOrderNo($merchantOrderNo)
    {
        $this->merchantOrderNo = $merchantOrderNo;
        $this->apiParams['merchant_order_no'] = $merchantOrderNo;
    }

    public function getMerchantOrderNo()
    {
        return $this->merchantOrderNo;
    }

    public function setOrderTime($orderTime)
    {
        $this->orderTime = $orderTime;
        $this->apiParams['order_time'] = $orderTime;
    }

    public function getOrderTime()
    {
        return $this->orderTime;
    }

    public function setPointCount($pointCount)
    {
        $this->pointCount = $pointCount;
        $this->apiParams['point_count'] = $pointCount;
    }

    public function getPointCount()
    {
        return $this->pointCount;
    }

    public function setUserSymbol($userSymbol)
    {
        $this->userSymbol = $userSymbol;
        $this->apiParams['user_symbol'] = $userSymbol;
    }

    public function getUserSymbol()
    {
        return $this->userSymbol;
    }

    public function setUserSymbolType($userSymbolType)
    {
        $this->userSymbolType = $userSymbolType;
        $this->apiParams['user_symbol_type'] = $userSymbolType;
    }

    public function getUserSymbolType()
    {
        return $this->userSymbolType;
    }
}
