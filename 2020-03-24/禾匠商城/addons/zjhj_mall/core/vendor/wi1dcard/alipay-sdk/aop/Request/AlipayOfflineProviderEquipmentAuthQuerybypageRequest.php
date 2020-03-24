<?php
/**
 * ALIPAY API: alipay.offline.provider.equipment.auth.querybypage request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-29 17:00:49
 */

namespace Alipay\Request;

class AlipayOfflineProviderEquipmentAuthQuerybypageRequest extends AbstractAlipayRequest
{
    /**
     * 解绑查询接口
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
