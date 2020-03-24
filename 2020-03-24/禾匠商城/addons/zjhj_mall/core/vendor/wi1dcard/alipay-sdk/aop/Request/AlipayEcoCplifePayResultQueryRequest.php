<?php
/**
 * ALIPAY API: alipay.eco.cplife.pay.result.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:11:05
 */

namespace Alipay\Request;

class AlipayEcoCplifePayResultQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询单笔物业费交易关联账单详情
     **/
    private $bizContent;

    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParams['biz_content'] = $bizContent;
    }

    public function getBizContent()
    {
        return $this->bizContent;
    }
}
