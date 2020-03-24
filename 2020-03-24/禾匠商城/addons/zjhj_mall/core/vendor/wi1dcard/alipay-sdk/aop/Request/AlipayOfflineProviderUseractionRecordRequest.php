<?php
/**
 * ALIPAY API: alipay.offline.provider.useraction.record request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-24 11:32:42
 */

namespace Alipay\Request;

class AlipayOfflineProviderUseractionRecordRequest extends AbstractAlipayRequest
{
    /**
     * isv 回传的用户操作行为信息调用接口
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
