<?php
/**
 * ALIPAY API: alipay.mobile.public.info.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-01 20:55:35
 */

namespace Alipay\Request;

class AlipayMobilePublicInfoModifyRequest extends AbstractAlipayRequest
{
    /**
     * 服务窗名称，2-20个字之间；不得含有违反法律法规和公序良俗的相关信息；不得侵害他人名誉权、知识产权、商业秘密等合法权利；不得以太过广泛的、或产品、行业词组来命名，如：女装、皮革批发；不得以实名认证的媒体资质账号创建服务窗，或媒体相关名称命名服务窗，如：XX电视台、XX杂志等
     **/
    private $appName;
    /**
     * 授权运营书，企业商户若为被经营方授权，需上传加盖公章的扫描件，请使用照片上传接口上传图片获得image_url
     **/
    private $authPic;
    /**
     * 营业执照地址，建议尺寸 320 x 320px，支持.jpg .jpeg .png 格式，小于3M
     **/
    private $licenseUrl;
    /**
     * 服务窗头像地址，建议尺寸 320 x 320px，支持.jpg .jpeg .png 格式，小于3M
     **/
    private $logoUrl;
    /**
     * 服务窗欢迎语，200字以内，首次使用服务窗必须
     **/
    private $publicGreeting;
    /**
     * 第一张门店照片地址，建议尺寸 320 x 320px，支持.jpg .jpeg .png 格式，小于3M
     **/
    private $shopPic1;
    /**
     * 第二张门店照片地址
     **/
    private $shopPic2;
    /**
     * 第三张门店照片地址
     **/
    private $shopPic3;

    public function setAppName($appName)
    {
        $this->appName = $appName;
        $this->apiParams['app_name'] = $appName;
    }

    public function getAppName()
    {
        return $this->appName;
    }

    public function setAuthPic($authPic)
    {
        $this->authPic = $authPic;
        $this->apiParams['auth_pic'] = $authPic;
    }

    public function getAuthPic()
    {
        return $this->authPic;
    }

    public function setLicenseUrl($licenseUrl)
    {
        $this->licenseUrl = $licenseUrl;
        $this->apiParams['license_url'] = $licenseUrl;
    }

    public function getLicenseUrl()
    {
        return $this->licenseUrl;
    }

    public function setLogoUrl($logoUrl)
    {
        $this->logoUrl = $logoUrl;
        $this->apiParams['logo_url'] = $logoUrl;
    }

    public function getLogoUrl()
    {
        return $this->logoUrl;
    }

    public function setPublicGreeting($publicGreeting)
    {
        $this->publicGreeting = $publicGreeting;
        $this->apiParams['public_greeting'] = $publicGreeting;
    }

    public function getPublicGreeting()
    {
        return $this->publicGreeting;
    }

    public function setShopPic1($shopPic1)
    {
        $this->shopPic1 = $shopPic1;
        $this->apiParams['shop_pic1'] = $shopPic1;
    }

    public function getShopPic1()
    {
        return $this->shopPic1;
    }

    public function setShopPic2($shopPic2)
    {
        $this->shopPic2 = $shopPic2;
        $this->apiParams['shop_pic2'] = $shopPic2;
    }

    public function getShopPic2()
    {
        return $this->shopPic2;
    }

    public function setShopPic3($shopPic3)
    {
        $this->shopPic3 = $shopPic3;
        $this->apiParams['shop_pic3'] = $shopPic3;
    }

    public function getShopPic3()
    {
        return $this->shopPic3;
    }
}
