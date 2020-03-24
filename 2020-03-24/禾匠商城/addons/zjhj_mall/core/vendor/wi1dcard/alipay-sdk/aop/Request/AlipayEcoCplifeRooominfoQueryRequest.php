<?php
/**
 * ALIPAY API: alipay.eco.cplife.rooominfo.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-02-10 18:51:44
 */

namespace Alipay\Request;

class AlipayEcoCplifeRooominfoQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询小区房屋信息列表
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
