<?php
/**
 * ALIPAY API: alipay.eco.cplife.bill.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:12:18
 */

namespace Alipay\Request;

class AlipayEcoCplifeBillDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 删除已上传的物业费账单数据
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
