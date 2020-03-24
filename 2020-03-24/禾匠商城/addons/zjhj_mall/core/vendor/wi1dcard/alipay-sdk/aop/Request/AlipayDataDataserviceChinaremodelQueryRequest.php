<?php
/**
 * ALIPAY API: alipay.data.dataservice.chinaremodel.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-22 10:27:48
 */

namespace Alipay\Request;

class AlipayDataDataserviceChinaremodelQueryRequest extends AbstractAlipayRequest
{
    /**
     * 中再核保结果查询
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
