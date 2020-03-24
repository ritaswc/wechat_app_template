<?php
/**
 * ALIPAY API: alipay.data.dataservice.bill.downloadurl.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-07 15:50:00
 */

namespace Alipay\Request;

class AlipayDataDataserviceBillDownloadurlQueryRequest extends AbstractAlipayRequest
{
    /**
     * 无授权模式的查询对账单下载地址
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
