<?php
/**
 * ALIPAY API: alipay.open.public.advert.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-09 10:55:00
 */

namespace Alipay\Request;

class AlipayOpenPublicAdvertModifyRequest extends AbstractAlipayRequest
{
    /**
     * 生活号广告位修改接口
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
