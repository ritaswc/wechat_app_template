<?php
/**
 * ALIPAY API: alipay.mobile.public.message.label.send request
 *
 * @author auto create
 *
 * @since  1.0, 2016-03-31 21:05:48
 */

namespace Alipay\Request;

class AlipayMobilePublicMessageLabelSendRequest extends AbstractAlipayRequest
{
    /**
     * json串，<a href="https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7386797.0.0.2crAC1&treeId=53&articleId=103511&docType=1">详情请见</a>
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
