<?php
/**
 * ALIPAY API: alipay.open.public.matchuser.label.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-11 19:07:28
 */

namespace Alipay\Request;

class AlipayOpenPublicMatchuserLabelCreateRequest extends AbstractAlipayRequest
{
    /**
     * 匹配用户标签添加接口
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
