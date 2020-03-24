<?php
/**
 * ALIPAY API: alipay.boss.prod.arrangement.offline.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 21:06:47
 */

namespace Alipay\Request;

class AlipayBossProdArrangementOfflineQueryRequest extends AbstractAlipayRequest
{
    /**
     * 签约销售方案的主站产品码，目前只支持ONLINE_TRADE_PAY（在线购买签约）和FACE_TO_FACE_PAYMENT（当面付）两个常量值，不允许传入其他值，否则报SYSTEM_ERROR异常。不传值时，默认查询FACE_TO_FACE_PAYM（当面付产品）。
     **/
    private $productCode;

    public function setProductCode($productCode)
    {
        $this->productCode = $productCode;
        $this->apiParams['product_code'] = $productCode;
    }

    public function getProductCode()
    {
        return $this->productCode;
    }
}
