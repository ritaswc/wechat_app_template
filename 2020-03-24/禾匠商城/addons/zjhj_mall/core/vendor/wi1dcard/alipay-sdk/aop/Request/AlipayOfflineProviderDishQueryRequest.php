<?php
/**
 * ALIPAY API: alipay.offline.provider.dish.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-03 14:41:31
 */

namespace Alipay\Request;

class AlipayOfflineProviderDishQueryRequest extends AbstractAlipayRequest
{
    /**
     * 口碑菜品热度查询
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
