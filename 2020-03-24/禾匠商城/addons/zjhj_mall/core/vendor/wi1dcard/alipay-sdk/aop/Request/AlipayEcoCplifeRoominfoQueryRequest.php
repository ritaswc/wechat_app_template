<?php
/**
 * ALIPAY API: alipay.eco.cplife.roominfo.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:17:57
 */

namespace Alipay\Request;

class AlipayEcoCplifeRoominfoQueryRequest extends AbstractAlipayRequest
{
    /**
     * 商户根据需要调用该接口查询小区房屋信息列表。
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
