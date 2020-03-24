<?php
/**
 * ALIPAY API: koubei.advert.delivery.item.apply request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-02 10:02:06
 */

namespace Alipay\Request;

class KoubeiAdvertDeliveryItemApplyRequest extends AbstractAlipayRequest
{
    /**
     * 口碑外部投放授权领券接口
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
