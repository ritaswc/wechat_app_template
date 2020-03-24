<?php
/**
 * ALIPAY API: alipay.daowei.sp.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-10 16:06:09
 */

namespace Alipay\Request;

class AlipayDaoweiSpModifyRequest extends AbstractAlipayRequest
{
    /**
     * 创建或更新服务者信息接口
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
