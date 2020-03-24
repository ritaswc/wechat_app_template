<?php
/**
 * ALIPAY API: alipay.eco.edu.kt.parent.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-19 14:55:00
 */

namespace Alipay\Request;

class AlipayEcoEduKtParentQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询学生家长状态接口
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
