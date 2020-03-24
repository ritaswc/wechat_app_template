<?php
/**
 * ALIPAY API: alipay.eco.mycar.carmodel.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-25 14:52:20
 */

namespace Alipay\Request;

class AlipayEcoMycarCarmodelModifyRequest extends AbstractAlipayRequest
{
    /**
     * 修改车型信息接口
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
