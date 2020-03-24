<?php
/**
 * ALIPAY API: alipay.open.agent.mobilepay.sign request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-31 21:00:22
 */

namespace Alipay\Request;

class AlipayOpenAgentMobilepaySignRequest extends AbstractAlipayRequest
{
    /**
     * APP demo，格式为.apk；或者应用说明文档, 格式为.doc .docx .pdf格式
     **/
    private $appDemo;
    /**
     * 商户的APP应用名称
     **/
    private $appName;
    /**
     * 代商户操作事务编号，通过alipay.open.isv.agent.create接口进行创建。
     **/
    private $batchNo;
    /**
     * 营业执照授权函图片，个体工商户如果使用总公司或其他公司的营业执照认证需上传该授权函图片，最小50KB，图片格式必须为：png、bmp、gif、jpg、jpeg
     **/
    private $businessLicenseAuthPic;
    /**
     * 营业执照号码。
     **/
    private $businessLicenseNo;
    /**
     * 营业执照图片，最小50KB，图片格式必须为：png、bmp、gif、jpg、jpeg
     **/
    private $businessLicensePic;
    /**
     * 营业期限
     **/
    private $dateLimitation;
    /**
     * 营业期限是否长期有效
     **/
    private $longTerm;
    /**
     * 所属MCCCode，详情可参考
     * <a href="https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.59bgD2&treeId=222&articleId=105364&docType=1#s1
     * ">商家经营类目</a> 中的“经营类目编码”
     **/
    private $mccCode;
    /**
     * 企业特殊资质图片，可参考
     * <a href="https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.59bgD2&treeId=222&articleId=105364&docType=1#s1
     * ">商家经营类目</a> 中的“需要的特殊资质证书”，最小50KB，图片格式必须为：png、bmp、gif、jpg、jpeg
     **/
    private $specialLicensePic;

    public function setAppDemo($appDemo)
    {
        $this->appDemo = $appDemo;
        $this->apiParams['app_demo'] = $appDemo;
    }

    public function getAppDemo()
    {
        return $this->appDemo;
    }

    public function setAppName($appName)
    {
        $this->appName = $appName;
        $this->apiParams['app_name'] = $appName;
    }

    public function getAppName()
    {
        return $this->appName;
    }

    public function setBatchNo($batchNo)
    {
        $this->batchNo = $batchNo;
        $this->apiParams['batch_no'] = $batchNo;
    }

    public function getBatchNo()
    {
        return $this->batchNo;
    }

    public function setBusinessLicenseAuthPic($businessLicenseAuthPic)
    {
        $this->businessLicenseAuthPic = $businessLicenseAuthPic;
        $this->apiParams['business_license_auth_pic'] = $businessLicenseAuthPic;
    }

    public function getBusinessLicenseAuthPic()
    {
        return $this->businessLicenseAuthPic;
    }

    public function setBusinessLicenseNo($businessLicenseNo)
    {
        $this->businessLicenseNo = $businessLicenseNo;
        $this->apiParams['business_license_no'] = $businessLicenseNo;
    }

    public function getBusinessLicenseNo()
    {
        return $this->businessLicenseNo;
    }

    public function setBusinessLicensePic($businessLicensePic)
    {
        $this->businessLicensePic = $businessLicensePic;
        $this->apiParams['business_license_pic'] = $businessLicensePic;
    }

    public function getBusinessLicensePic()
    {
        return $this->businessLicensePic;
    }

    public function setDateLimitation($dateLimitation)
    {
        $this->dateLimitation = $dateLimitation;
        $this->apiParams['date_limitation'] = $dateLimitation;
    }

    public function getDateLimitation()
    {
        return $this->dateLimitation;
    }

    public function setLongTerm($longTerm)
    {
        $this->longTerm = $longTerm;
        $this->apiParams['long_term'] = $longTerm;
    }

    public function getLongTerm()
    {
        return $this->longTerm;
    }

    public function setMccCode($mccCode)
    {
        $this->mccCode = $mccCode;
        $this->apiParams['mcc_code'] = $mccCode;
    }

    public function getMccCode()
    {
        return $this->mccCode;
    }

    public function setSpecialLicensePic($specialLicensePic)
    {
        $this->specialLicensePic = $specialLicensePic;
        $this->apiParams['special_license_pic'] = $specialLicensePic;
    }

    public function getSpecialLicensePic()
    {
        return $this->specialLicensePic;
    }
}
