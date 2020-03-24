<?php
/**
 * ALIPAY API: alipay.eco.edu.kt.student.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-06 11:46:27
 */

namespace Alipay\Request;

class AlipayEcoEduKtStudentModifyRequest extends AbstractAlipayRequest
{
    /**
     * 学生信息更新
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
