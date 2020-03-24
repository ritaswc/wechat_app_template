<?php
/**
 * ALIPAY API: koubei.member.data.isv.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-05 16:38:18
 */

namespace Alipay\Request;

class KoubeiMemberDataIsvCreateRequest extends AbstractAlipayRequest
{
    /**
     * isv 会员CRM数据回流
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
