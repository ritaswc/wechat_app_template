<?php
/**
 * ALIPAY API: alipay.open.mini.baseinfo.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-25 00:19:21
 */

namespace Alipay\Request;

class AlipayOpenMiniBaseinfoModifyRequest extends AbstractAlipayRequest
{
    /**
     * 11_12;12_13。小程序类目，格式为 第一个一级类目_第一个二级类目;第二个一级类目_第二个二级类目，详细类目可以参考https://docs.alipay.com/isv/10325
     **/
    private $appCategoryIds;
    /**
     * 小程序应用描述，20-200个字
     **/
    private $appDesc;
    /**
     * 小程序应用英文名称
     **/
    private $appEnglishName;
    /**
     * 小程序应用logo图标，图片格式必须为：png、jpeg、jpg，建议上传像素为180*180
     **/
    private $appLogo;
    /**
     * 小程序应用名称
     **/
    private $appName;
    /**
     * 小程序应用简介，一句话描述小程序功能
     **/
    private $appSlogan;
    /**
     * 小程序客服邮箱
     **/
    private $serviceEmail;
    /**
     * 小程序客服电话
     **/
    private $servicePhone;

    public function setAppCategoryIds($appCategoryIds)
    {
        $this->appCategoryIds = $appCategoryIds;
        $this->apiParams['app_category_ids'] = $appCategoryIds;
    }

    public function getAppCategoryIds()
    {
        return $this->appCategoryIds;
    }

    public function setAppDesc($appDesc)
    {
        $this->appDesc = $appDesc;
        $this->apiParams['app_desc'] = $appDesc;
    }

    public function getAppDesc()
    {
        return $this->appDesc;
    }

    public function setAppEnglishName($appEnglishName)
    {
        $this->appEnglishName = $appEnglishName;
        $this->apiParams['app_english_name'] = $appEnglishName;
    }

    public function getAppEnglishName()
    {
        return $this->appEnglishName;
    }

    public function setAppLogo($appLogo)
    {
        $this->appLogo = $appLogo;
        $this->apiParams['app_logo'] = $appLogo;
    }

    public function getAppLogo()
    {
        return $this->appLogo;
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

    public function setAppSlogan($appSlogan)
    {
        $this->appSlogan = $appSlogan;
        $this->apiParams['app_slogan'] = $appSlogan;
    }

    public function getAppSlogan()
    {
        return $this->appSlogan;
    }

    public function setServiceEmail($serviceEmail)
    {
        $this->serviceEmail = $serviceEmail;
        $this->apiParams['service_email'] = $serviceEmail;
    }

    public function getServiceEmail()
    {
        return $this->serviceEmail;
    }

    public function setServicePhone($servicePhone)
    {
        $this->servicePhone = $servicePhone;
        $this->apiParams['service_phone'] = $servicePhone;
    }

    public function getServicePhone()
    {
        return $this->servicePhone;
    }
}
