<?php
/**
 * ALIPAY API: alipay.data.bill.downloadurl.get request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 11:43:02
 */

namespace Alipay\Request;

class AlipayDataBillDownloadurlGetRequest extends AbstractAlipayRequest
{
    /**
     * 账单时间：日账单格式为yyyy-MM-dd,月账单格式为yyyy-MM
     **/
    private $billDate;
    /**
     * 账单类型，目前支持的类型由：trade、air、air_b2b；trade指商户通过接口所获取的账单，或商户经开放平台授权后其所属服务商通过接口所获取的账单；air、air_b2b是航旅行业定制的账单，一般商户没有此账单；
     **/
    private $billType;

    public function setBillDate($billDate)
    {
        $this->billDate = $billDate;
        $this->apiParams['bill_date'] = $billDate;
    }

    public function getBillDate()
    {
        return $this->billDate;
    }

    public function setBillType($billType)
    {
        $this->billType = $billType;
        $this->apiParams['bill_type'] = $billType;
    }

    public function getBillType()
    {
        return $this->billType;
    }
}
