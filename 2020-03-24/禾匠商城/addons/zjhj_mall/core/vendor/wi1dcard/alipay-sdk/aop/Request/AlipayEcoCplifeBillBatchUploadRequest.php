<?php
/**
 * ALIPAY API: alipay.eco.cplife.bill.batch.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:14:19
 */

namespace Alipay\Request;

class AlipayEcoCplifeBillBatchUploadRequest extends AbstractAlipayRequest
{
    /**
     * 物业费账单数据批量上传
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
