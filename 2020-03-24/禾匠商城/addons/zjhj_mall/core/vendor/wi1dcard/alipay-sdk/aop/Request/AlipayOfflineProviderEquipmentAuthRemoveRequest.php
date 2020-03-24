<?php
/**
 * ALIPAY API: alipay.offline.provider.equipment.auth.remove request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-29 17:01:40
 */

namespace Alipay\Request;

class AlipayOfflineProviderEquipmentAuthRemoveRequest extends AbstractAlipayRequest
{
    /**
     * 天猫机具解绑接口
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
