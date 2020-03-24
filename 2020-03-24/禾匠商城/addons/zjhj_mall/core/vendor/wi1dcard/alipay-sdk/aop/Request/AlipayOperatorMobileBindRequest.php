<?php
/**
 * ALIPAY API: alipay.operator.mobile.bind request
 *
 * @author auto create
 *
 * @since  1.0, 2017-05-03 16:48:39
 */

namespace Alipay\Request;

class AlipayOperatorMobileBindRequest extends AbstractAlipayRequest
{
    /**
     * 标识该运营商是否需要验证用户的手机号绑定过快捷卡
     * 1：需要
     * 0：不需要
     **/
    private $checkSigncard;
    /**
     * 支付宝处理完请求后，如验证失败，当前页面自动跳转到商户网站里指定页面的http路径。
     **/
    private $fReturnUrl;
    /**
     * 标识该运营商是否提供了查询手机归属的spi接口。
     * 1：提供了
     * 0：没提供
     **/
    private $hasSpi;
    /**
     * 标识该运营商名称
     **/
    private $operatorName;
    /**
     * 标识该运营商所在省份
     **/
    private $provinceName;
    /**
     * 支付宝处理完请求后，如验证成功，当前页面自动跳转到商户网站里指定页面的http路径。
     **/
    private $sReturnUrl;

    public function setCheckSigncard($checkSigncard)
    {
        $this->checkSigncard = $checkSigncard;
        $this->apiParams['check_signcard'] = $checkSigncard;
    }

    public function getCheckSigncard()
    {
        return $this->checkSigncard;
    }

    public function setfReturnUrl($fReturnUrl)
    {
        $this->fReturnUrl = $fReturnUrl;
        $this->apiParams['f_return_url'] = $fReturnUrl;
    }

    public function getfReturnUrl()
    {
        return $this->fReturnUrl;
    }

    public function setHasSpi($hasSpi)
    {
        $this->hasSpi = $hasSpi;
        $this->apiParams['has_spi'] = $hasSpi;
    }

    public function getHasSpi()
    {
        return $this->hasSpi;
    }

    public function setOperatorName($operatorName)
    {
        $this->operatorName = $operatorName;
        $this->apiParams['operator_name'] = $operatorName;
    }

    public function getOperatorName()
    {
        return $this->operatorName;
    }

    public function setProvinceName($provinceName)
    {
        $this->provinceName = $provinceName;
        $this->apiParams['province_name'] = $provinceName;
    }

    public function getProvinceName()
    {
        return $this->provinceName;
    }

    public function setsReturnUrl($sReturnUrl)
    {
        $this->sReturnUrl = $sReturnUrl;
        $this->apiParams['s_return_url'] = $sReturnUrl;
    }

    public function getsReturnUrl()
    {
        return $this->sReturnUrl;
    }
}
