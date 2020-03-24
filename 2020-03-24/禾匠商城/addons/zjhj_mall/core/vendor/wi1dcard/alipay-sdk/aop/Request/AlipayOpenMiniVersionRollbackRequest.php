<?php
/**
 * ALIPAY API: alipay.open.mini.version.rollback request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-15 15:24:19
 */

namespace Alipay\Request;

class AlipayOpenMiniVersionRollbackRequest extends AbstractAlipayRequest
{
    /**
     * 小程序回滚
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
