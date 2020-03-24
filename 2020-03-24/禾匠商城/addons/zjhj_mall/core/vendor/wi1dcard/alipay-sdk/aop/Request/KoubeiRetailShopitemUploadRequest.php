<?php
/**
 * ALIPAY API: koubei.retail.shopitem.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 18:10:01
 */

namespace Alipay\Request;

class KoubeiRetailShopitemUploadRequest extends AbstractAlipayRequest
{
    /**
     * isv 回传的门店商品信息上传接口
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
