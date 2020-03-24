<?php
/**
 * ALIPAY API: koubei.marketing.data.customreport.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-25 17:21:14
 */

namespace Alipay\Request;

class KoubeiMarketingDataCustomreportDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 自定义数据报表删除接口
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
