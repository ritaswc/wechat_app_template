<?php
/**
 * ALIPAY API: zhima.data.feedbackurl.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-02-16 15:46:19
 */

namespace Alipay\Request;

class ZhimaDataFeedbackurlQueryRequest extends AbstractAlipayRequest
{
    /**
     * 获取数据反馈模板
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
