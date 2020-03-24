<?php
/**
 * ALIPAY API: koubei.marketing.data.retail.dm.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-05-16 17:02:42
 */

namespace Alipay\Request;

class KoubeiMarketingDataRetailDmQueryRequest extends AbstractAlipayRequest
{
    /**
     * 快消店铺DM浏览数据查询接口
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
