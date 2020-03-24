<?php
/**
 * ALIPAY API: alipay.open.mini.version.detail.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-25 00:18:29
 */

namespace Alipay\Request;

class AlipayOpenMiniVersionDetailQueryRequest extends AbstractAlipayRequest
{
    /**
     * 小程序版本详情查询
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
