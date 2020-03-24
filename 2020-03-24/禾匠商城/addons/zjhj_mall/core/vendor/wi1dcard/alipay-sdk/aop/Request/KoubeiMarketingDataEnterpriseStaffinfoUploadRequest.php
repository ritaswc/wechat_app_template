<?php
/**
 * ALIPAY API: koubei.marketing.data.enterprise.staffinfo.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-25 15:32:34
 */

namespace Alipay\Request;

class KoubeiMarketingDataEnterpriseStaffinfoUploadRequest extends AbstractAlipayRequest
{
    /**
     * 外部企业员工信息上传接口
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
