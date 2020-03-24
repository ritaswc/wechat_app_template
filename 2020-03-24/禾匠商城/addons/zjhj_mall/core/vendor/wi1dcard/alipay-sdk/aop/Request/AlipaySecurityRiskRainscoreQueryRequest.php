<?php
/**
 * ALIPAY API: alipay.security.risk.rainscore.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-08 14:08:31
 */

namespace Alipay\Request;

class AlipaySecurityRiskRainscoreQueryRequest extends AbstractAlipayRequest
{
    /**
     * RAIN （Risk of Activity, Identity and Network）是蚁盾旗下产品，专业提供风险评分服务，是一套能够对手机号进行风险预测、风险解释的评分体系。
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
