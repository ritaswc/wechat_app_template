<?php
/**
 * ALIPAY API: koubei.item.extitem.category.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-07-06 10:47:52
 */

namespace Alipay\Request;

class KoubeiItemExtitemCategoryQueryRequest extends AbstractAlipayRequest
{
    /**
     * 品类查询接口
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
