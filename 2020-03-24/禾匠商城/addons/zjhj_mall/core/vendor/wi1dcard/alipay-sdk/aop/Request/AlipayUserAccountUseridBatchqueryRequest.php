<?php
/**
 * ALIPAY API: alipay.user.account.userid.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2016-06-06 21:37:53
 */

namespace Alipay\Request;

class AlipayUserAccountUseridBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 业务统一入参，目前会传入用户的手机号作为查询参数
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
