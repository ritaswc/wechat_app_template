<?php
/**
 * ALIPAY API: alipay.mobile.public.message.single.send request
 *
 * @author auto create
 *
 * @since  1.0, 2017-06-15 14:45:00
 */

namespace Alipay\Request;

class AlipayMobilePublicMessageSingleSendRequest extends AbstractAlipayRequest
{
    /**
     * 业务内容，其中包括模板template和消息接收人toUserId两大块，具体参见“表1-2 服务窗单发模板消息的biz_content参数说明”。
     * <a href="https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7386797.0.0.eZqycg&treeId=53&articleId=103463&docType=1">详情请见</a>
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
