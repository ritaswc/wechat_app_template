<?php
/**
 * ALIPAY API: koubei.quality.test.cloudacpt.checkresult.submit request
 *
 * @author auto create
 *
 * @since  1.0, 2016-06-15 15:06:55
 */

namespace Alipay\Request;

class KoubeiQualityTestCloudacptCheckresultSubmitRequest extends AbstractAlipayRequest
{
    /**
     * 云验收检测结果提交
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
