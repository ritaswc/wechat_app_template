<?php
/**
 * ALIPAY API: alipay.pass.sync.add request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-16 16:35:12
 */

namespace Alipay\Request;

class AlipayPassSyncAddRequest extends AbstractAlipayRequest
{
    /**
     * alipass文件Base64编码后的内容。
     **/
    private $fileContent;
    /**
     * 商户外部交易号，由商户生成并确保其唯一性
     **/
    private $outTradeNo;
    /**
     * 商户与支付宝签约时，分配的唯一ID。
     **/
    private $partnerId;
    /**
     * 支付宝用户ID，即买家用户ID
     **/
    private $userId;

    public function setFileContent($fileContent)
    {
        $this->fileContent = $fileContent;
        $this->apiParams['file_content'] = $fileContent;
    }

    public function getFileContent()
    {
        return $this->fileContent;
    }

    public function setOutTradeNo($outTradeNo)
    {
        $this->outTradeNo = $outTradeNo;
        $this->apiParams['out_trade_no'] = $outTradeNo;
    }

    public function getOutTradeNo()
    {
        return $this->outTradeNo;
    }

    public function setPartnerId($partnerId)
    {
        $this->partnerId = $partnerId;
        $this->apiParams['partner_id'] = $partnerId;
    }

    public function getPartnerId()
    {
        return $this->partnerId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        $this->apiParams['user_id'] = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
