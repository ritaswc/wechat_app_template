<?php
/**
 * ALIPAY API: alipay.mobile.shake.user.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-03 16:35:37
 */

namespace Alipay\Request;

class AlipayMobileShakeUserQueryRequest extends AbstractAlipayRequest
{
    /**
     * 动态ID
     **/
    private $dynamicId;
    /**
     * 动态ID类型：
     * wave_code：声波
     * qr_code：二维码
     * bar_code：条码
     **/
    private $dynamicIdType;

    public function setDynamicId($dynamicId)
    {
        $this->dynamicId = $dynamicId;
        $this->apiParams['dynamic_id'] = $dynamicId;
    }

    public function getDynamicId()
    {
        return $this->dynamicId;
    }

    public function setDynamicIdType($dynamicIdType)
    {
        $this->dynamicIdType = $dynamicIdType;
        $this->apiParams['dynamic_id_type'] = $dynamicIdType;
    }

    public function getDynamicIdType()
    {
        return $this->dynamicIdType;
    }
}
