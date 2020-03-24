<?php
/**
 * ALIPAY API: koubei.item.category.children.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-06-06 11:40:51
 */

namespace Alipay\Request;

class KoubeiItemCategoryChildrenBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 批量查询标准后台类目
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
