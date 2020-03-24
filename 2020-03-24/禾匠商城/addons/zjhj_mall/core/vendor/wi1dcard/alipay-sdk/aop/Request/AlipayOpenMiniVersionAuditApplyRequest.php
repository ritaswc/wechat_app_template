<?php
/**
 * ALIPAY API: alipay.open.mini.version.audit.apply request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-04 19:40:00
 */

namespace Alipay\Request;

class AlipayOpenMiniVersionAuditApplyRequest extends AbstractAlipayRequest
{
    /**
     * 小程序类目，格式为 第一个一级类目_第一个二级类目;第二个一级类目_第二个二级类目，详细类目可以参考https://docs.alipay.com/isv/10325，如果不填默认采用当前小程序应用类目
     **/
    private $appCategoryIds;
    /**
     * 小程序应用描述，20-200个字，如果不填默认采用当前小程序的应用描述
     **/
    private $appDesc;
    /**
     * 小程序应用英文名称，如果不填默认采用当前小程序应用英文名称
     **/
    private $appEnglishName;
    /**
     * 小程序logo图标，图片格式必须为：png、jpeg、jpg，建议上传像素为180*180，如果不填默认采用当前小程序应用logo图标
     **/
    private $appLogo;
    /**
     * 小程序应用名称，如果不填默认采用当前小程序应用名称
     **/
    private $appName;
    /**
     * 小程序应用简介，一句话描述小程序功能，如果不填默认采用当前小程序应用简介
     **/
    private $appSlogan;
    /**
     * 小程序版本号
     **/
    private $appVersion;
    /**
     * 第五张营业执照照片，不能超过4MB，图片格式只支持jpg，png，部分小程序类目需要提交，参照https://docs.alipay.com/isv/10325中是否需要营业执照信息
     **/
    private $fifthLicensePic;
    /**
     * 小程序第五张应用截图，不能超过4MB，图片格式只支持jpg，png
     **/
    private $fifthScreenShot;
    /**
     * 第一张营业执照照片，不能超过4MB，图片格式只支持jpg，png，部分小程序类目需要提交，参照https://docs.alipay.com/isv/10325中是否需要营业执照信息
     **/
    private $firstLicensePic;
    /**
     * 实例化的小程序可以不用传第一张应用截图，小程序第一张应用截图，不能超过4MB，图片格式只支持jpg，png
     **/
    private $firstScreenShot;
    /**
     * 第四张营业执照照片，不能超过4MB，图片格式只支持jpg，png，部分小程序类目需要提交，参照https://docs.alipay.com/isv/10325中是否需要营业执照信息
     **/
    private $fourthLicensePic;
    /**
     * 小程序第四张应用截图，不能超过4MB，图片格式只支持jpg，png
     **/
    private $fourthScreenShot;
    /**
     * 营业执照名称，部分小程序类目需要提交，参照https://docs.alipay.com/isv/10325中是否需要营业执照信息
     **/
    private $licenseName;
    /**
     * 营业执照号，部分小程序类目需要提交，参照https://docs.alipay.com/isv/10325中是否需要营业执照信息
     **/
    private $licenseNo;
    /**
     * 营业执照有效期，格式为yyyy-MM-dd，9999-12-31表示长期，部分小程序类目需要提交，参照https://docs.alipay.com/isv/10325中是否需要营业执照信息
     **/
    private $licenseValidDate;
    /**
     * 小程序备注
     **/
    private $memo;
    /**
     * 门头照图片，部分小程序类目需要提交，参照https://docs.alipay.com/isv/10325中是否需要营业执照信息
     **/
    private $outDoorPic;
    /**
     * 小程序服务区域类型，GLOBAL-全球，CHINA-中国，LOCATION-指定区域
     **/
    private $regionType;
    /**
     * 第二张营业执照照片，不能超过4MB，图片格式只支持jpg，png，部分小程序类目需要提交，参照https://docs.alipay.com/isv/10325中是否需要营业执照信息
     **/
    private $secondLicensePic;
    /**
     * 实例化的小程序可以不用传第二张应用截图，小程序第二张应用截图，不能超过4MB，图片格式只支持jpg，png
     **/
    private $secondScreenShot;
    /**
     * 小程序客服邮箱，如果不填默认采用当前小程序的应用客服邮箱
     **/
    private $serviceEmail;
    /**
     * 小程序客服电话，如果不填默认采用当前小程序的应用客服电话
     **/
    private $servicePhone;
    /**
     * 省市区信息，当区域类型为LOCATION时，不能为空，province_code不能为空，当填写city_code时，province_code不能为空，当填写area_code时，province_code和city_code不能为空。只填province_code时，该省全部选择；province_code和city_code都填时，该市全部选择。province_code，city_code和area_code都填时，该县全部选择。具体code可以参考https://docs.alipay.com/isv/10327
     **/
    private $serviceRegionInfo;
    /**
     * 第三张营业执照照片，不能超过4MB，图片格式只支持jpg，png，部分小程序类目需要提交，参照https://docs.alipay.com/isv/10325中是否需要营业执照信息
     **/
    private $thirdLicensePic;
    /**
     * 小程序第三张应用截图，不能超过4MB，图片格式只支持jpg，png
     **/
    private $thirdScreenShot;
    /**
     * 小程序版本描述
     **/
    private $versionDesc;

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

    public function setAppVersion($appVersion)
    {
        $this->appVersion = $appVersion;
        $this->apiParams['app_version'] = $appVersion;
    }

    public function getAppVersion()
    {
        return $this->appVersion;
    }

    public function setFifthLicensePic($fifthLicensePic)
    {
        $this->fifthLicensePic = $fifthLicensePic;
        $this->apiParams['fifth_license_pic'] = $fifthLicensePic;
    }

    public function getFifthLicensePic()
    {
        return $this->fifthLicensePic;
    }

    public function setFifthScreenShot($fifthScreenShot)
    {
        $this->fifthScreenShot = $fifthScreenShot;
        $this->apiParams['fifth_screen_shot'] = $fifthScreenShot;
    }

    public function getFifthScreenShot()
    {
        return $this->fifthScreenShot;
    }

    public function setFirstLicensePic($firstLicensePic)
    {
        $this->firstLicensePic = $firstLicensePic;
        $this->apiParams['first_license_pic'] = $firstLicensePic;
    }

    public function getFirstLicensePic()
    {
        return $this->firstLicensePic;
    }

    public function setFirstScreenShot($firstScreenShot)
    {
        $this->firstScreenShot = $firstScreenShot;
        $this->apiParams['first_screen_shot'] = $firstScreenShot;
    }

    public function getFirstScreenShot()
    {
        return $this->firstScreenShot;
    }

    public function setFourthLicensePic($fourthLicensePic)
    {
        $this->fourthLicensePic = $fourthLicensePic;
        $this->apiParams['fourth_license_pic'] = $fourthLicensePic;
    }

    public function getFourthLicensePic()
    {
        return $this->fourthLicensePic;
    }

    public function setFourthScreenShot($fourthScreenShot)
    {
        $this->fourthScreenShot = $fourthScreenShot;
        $this->apiParams['fourth_screen_shot'] = $fourthScreenShot;
    }

    public function getFourthScreenShot()
    {
        return $this->fourthScreenShot;
    }

    public function setLicenseName($licenseName)
    {
        $this->licenseName = $licenseName;
        $this->apiParams['license_name'] = $licenseName;
    }

    public function getLicenseName()
    {
        return $this->licenseName;
    }

    public function setLicenseNo($licenseNo)
    {
        $this->licenseNo = $licenseNo;
        $this->apiParams['license_no'] = $licenseNo;
    }

    public function getLicenseNo()
    {
        return $this->licenseNo;
    }

    public function setLicenseValidDate($licenseValidDate)
    {
        $this->licenseValidDate = $licenseValidDate;
        $this->apiParams['license_valid_date'] = $licenseValidDate;
    }

    public function getLicenseValidDate()
    {
        return $this->licenseValidDate;
    }

    public function setMemo($memo)
    {
        $this->memo = $memo;
        $this->apiParams['memo'] = $memo;
    }

    public function getMemo()
    {
        return $this->memo;
    }

    public function setOutDoorPic($outDoorPic)
    {
        $this->outDoorPic = $outDoorPic;
        $this->apiParams['out_door_pic'] = $outDoorPic;
    }

    public function getOutDoorPic()
    {
        return $this->outDoorPic;
    }

    public function setRegionType($regionType)
    {
        $this->regionType = $regionType;
        $this->apiParams['region_type'] = $regionType;
    }

    public function getRegionType()
    {
        return $this->regionType;
    }

    public function setSecondLicensePic($secondLicensePic)
    {
        $this->secondLicensePic = $secondLicensePic;
        $this->apiParams['second_license_pic'] = $secondLicensePic;
    }

    public function getSecondLicensePic()
    {
        return $this->secondLicensePic;
    }

    public function setSecondScreenShot($secondScreenShot)
    {
        $this->secondScreenShot = $secondScreenShot;
        $this->apiParams['second_screen_shot'] = $secondScreenShot;
    }

    public function getSecondScreenShot()
    {
        return $this->secondScreenShot;
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

    public function setServiceRegionInfo($serviceRegionInfo)
    {
        $this->serviceRegionInfo = $serviceRegionInfo;
        $this->apiParams['service_region_info'] = $serviceRegionInfo;
    }

    public function getServiceRegionInfo()
    {
        return $this->serviceRegionInfo;
    }

    public function setThirdLicensePic($thirdLicensePic)
    {
        $this->thirdLicensePic = $thirdLicensePic;
        $this->apiParams['third_license_pic'] = $thirdLicensePic;
    }

    public function getThirdLicensePic()
    {
        return $this->thirdLicensePic;
    }

    public function setThirdScreenShot($thirdScreenShot)
    {
        $this->thirdScreenShot = $thirdScreenShot;
        $this->apiParams['third_screen_shot'] = $thirdScreenShot;
    }

    public function getThirdScreenShot()
    {
        return $this->thirdScreenShot;
    }

    public function setVersionDesc($versionDesc)
    {
        $this->versionDesc = $versionDesc;
        $this->apiParams['version_desc'] = $versionDesc;
    }

    public function getVersionDesc()
    {
        return $this->versionDesc;
    }
}
