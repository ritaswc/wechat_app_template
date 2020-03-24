<?php
/**
 * ALIPAY API: alipay.mobile.public.label.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-07-29 20:00:13
 */

namespace Alipay\Request;

class AlipayMobilePublicLabelQueryRequest extends AbstractAlipayRequest
{
    /**
     * json串，<a href="https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7386797.0.0.1l7WMo&treeId=53&articleId=103505&docType=1">详情请见</a>
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
