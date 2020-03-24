<?php
/**
 * ALIPAY API: koubei.advert.delivery.discount.send request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-02 10:05:02
 */

namespace Alipay\Request;

class KoubeiAdvertDeliveryDiscountSendRequest extends AbstractAlipayRequest
{
    /**
     * 口碑外部投放授权发券接口
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
