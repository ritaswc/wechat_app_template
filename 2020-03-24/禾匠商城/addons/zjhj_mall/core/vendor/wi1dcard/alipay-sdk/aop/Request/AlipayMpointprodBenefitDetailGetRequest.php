<?php
/**
 * ALIPAY API: alipay.mpointprod.benefit.detail.get request
 *
 * @author auto create
 *
 * @since  1.0, 2015-01-29 15:46:36
 */

namespace Alipay\Request;

class AlipayMpointprodBenefitDetailGetRequest extends AbstractAlipayRequest
{
    /**
     * 消息体内容，JSON格式，不含换行、空格
     * 参数:
     * userId: 支付用户ID, 可以直接传递openId
     * benefitType: 权益类型,支持(MemberGrade:会员等级)
     * benefitStatus: 状态只支持(VALID:生效、WAIT:待生效、INVALID:失效), 默认:全部
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
