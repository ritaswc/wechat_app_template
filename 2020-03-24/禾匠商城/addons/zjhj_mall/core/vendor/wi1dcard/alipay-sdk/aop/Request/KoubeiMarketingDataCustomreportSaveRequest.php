<?php
/**
 * ALIPAY API: koubei.marketing.data.customreport.save request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-25 17:35:49
 */

namespace Alipay\Request;

class KoubeiMarketingDataCustomreportSaveRequest extends AbstractAlipayRequest
{
    /**
     * 自定义数据报表创建及更新接口
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
