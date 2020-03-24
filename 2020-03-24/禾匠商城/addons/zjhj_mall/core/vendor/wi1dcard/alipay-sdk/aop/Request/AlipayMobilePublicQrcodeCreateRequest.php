<?php
/**
 * ALIPAY API: alipay.mobile.public.qrcode.create request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-19 20:53:20
 */

namespace Alipay\Request;

class AlipayMobilePublicQrcodeCreateRequest extends AbstractAlipayRequest
{
    /**
     * json串，<a href="https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7386797.0.0.1l7WMo&treeId=53&articleId=103490&docType=1">详情请见</a>
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
