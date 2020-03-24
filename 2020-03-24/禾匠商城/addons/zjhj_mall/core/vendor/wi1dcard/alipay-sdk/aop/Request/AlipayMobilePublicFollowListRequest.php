<?php
/**
 * ALIPAY API: alipay.mobile.public.follow.list request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-31 17:29:51
 */

namespace Alipay\Request;

class AlipayMobilePublicFollowListRequest extends AbstractAlipayRequest
{
    /**
     * 当nextUserId为空时,代表查询第一组,如果有值时以当前值为准查询下一组
     * <a href="https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7386797.0.0.eZqycg&treeId=53&articleId=103525&docType=1">详情请见</a>
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
