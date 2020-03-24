<?php
/**
 * ALIPAY API: alipay.data.dataservice.userlevel.zrank.get request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-22 10:27:27
 */

namespace Alipay\Request;

class AlipayDataDataserviceUserlevelZrankGetRequest extends AbstractAlipayRequest
{
    /**
     * 通用的活跃高价值用户等级，支持EMAIL,PHONE,BANKCARD,CERTNO,IMEI,MAC，TBID维度查询用户活跃高价值等级。等级从Z0-Z7，等级越高价值越高，Z0表示未实名认证或者用户信息不全。
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
