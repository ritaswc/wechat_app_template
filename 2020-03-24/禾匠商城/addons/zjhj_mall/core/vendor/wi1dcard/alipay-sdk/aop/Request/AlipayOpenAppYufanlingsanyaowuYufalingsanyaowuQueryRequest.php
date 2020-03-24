<?php
/**
 * ALIPAY API: alipay.open.app.yufanlingsanyaowu.yufalingsanyaowu.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-25 09:12:04
 */

namespace Alipay\Request;

class AlipayOpenAppYufanlingsanyaowuYufalingsanyaowuQueryRequest extends AbstractAlipayRequest
{
    /**
     * 预发03150725
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
