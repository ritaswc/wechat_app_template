<?php
/**
 * ALIPAY API: alipay.micropay.order.unfreeze request
 *
 * @author auto create
 *
 * @since  1.0, 2016-06-06 17:54:23
 */

namespace Alipay\Request;

class AlipayMicropayOrderUnfreezeRequest extends AbstractAlipayRequest
{
    /**
     * 冻结资金流水号,在创建资金订单时支付宝返回的流水号
     **/
    private $alipayOrderNo;
    /**
     * 冻结备注
     **/
    private $memo;

    public function setAlipayOrderNo($alipayOrderNo)
    {
        $this->alipayOrderNo = $alipayOrderNo;
        $this->apiParams['alipay_order_no'] = $alipayOrderNo;
    }

    public function getAlipayOrderNo()
    {
        return $this->alipayOrderNo;
    }

    public function setMemo($memo)
    {
        $this->memo = $memo;
        $this->apiParams['memo'] = $memo;
    }

    public function getMemo()
    {
        return $this->memo;
    }
}
