<?php
/**
 * ALIPAY API: alipay.eco.edu.kt.schoolinfo.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-18 12:27:03
 */

namespace Alipay\Request;

class AlipayEcoEduKtSchoolinfoModifyRequest extends AbstractAlipayRequest
{
    /**
     * 教育缴费学校信息录入接口
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
