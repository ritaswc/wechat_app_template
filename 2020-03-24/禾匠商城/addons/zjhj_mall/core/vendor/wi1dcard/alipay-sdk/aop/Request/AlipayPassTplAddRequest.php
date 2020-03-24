<?php
/**
 * ALIPAY API: alipay.pass.tpl.add request
 *
 * @author auto create
 *
 * @since  1.0, 2016-07-01 15:35:14
 */

namespace Alipay\Request;

class AlipayPassTplAddRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝pass模版内容【JSON格式】
     * 具体格式可参考https://alipass.alipay.com中文档中心-格式说明
     **/
    private $tplContent;
    /**
     * 模版外部唯一标识：商户用于控制模版的唯一性。
     **/
    private $uniqueId;

    public function setTplContent($tplContent)
    {
        $this->tplContent = $tplContent;
        $this->apiParams['tpl_content'] = $tplContent;
    }

    public function getTplContent()
    {
        return $this->tplContent;
    }

    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;
        $this->apiParams['unique_id'] = $uniqueId;
    }

    public function getUniqueId()
    {
        return $this->uniqueId;
    }
}
