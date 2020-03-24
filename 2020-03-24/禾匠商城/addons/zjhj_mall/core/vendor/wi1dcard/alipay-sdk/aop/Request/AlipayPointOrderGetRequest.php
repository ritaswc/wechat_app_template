<?php
/**
 * ALIPAY API: alipay.point.order.get request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 18:56:51
 */

namespace Alipay\Request;

class AlipayPointOrderGetRequest extends AbstractAlipayRequest
{
    /**
     * isv提供的发放号订单号，由数字和组成，最大长度为32为，需要保证每笔发放的唯一性，支付宝会对该参数做唯一性控制。如果使用同样的订单号，支付宝将返回订单号已经存在的错误
     **/
    private $merchantOrderNo;
    /**
     * 用户标识符，用于指定集分宝发放的用户，和user_symbol_type一起使用，确定一个唯一的支付宝用户
     **/
    private $userSymbol;
    /**
     * 用户标识符类型，现在支持ALIPAY_USER_ID:表示支付宝用户ID,ALIPAY_LOGON_ID:表示支付宝登陆号
     **/
    private $userSymbolType;

    public function setMerchantOrderNo($merchantOrderNo)
    {
        $this->merchantOrderNo = $merchantOrderNo;
        $this->apiParams['merchant_order_no'] = $merchantOrderNo;
    }

    public function getMerchantOrderNo()
    {
        return $this->merchantOrderNo;
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
