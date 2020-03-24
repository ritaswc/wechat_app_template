<?php
/**
 * ALIPAY API: koubei.craftsman.data.work.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-11 20:35:17
 */

namespace Alipay\Request;

class KoubeiCraftsmanDataWorkBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 手艺人作品信息批量查询接口
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
