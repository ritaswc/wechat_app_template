<?php
/**
 * ALIPAY API: alipay.pass.tpl.update request
 *
 * @author auto create
 *
 * @since  1.0, 2016-07-01 15:35:58
 */

namespace Alipay\Request;

class AlipayPassTplUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 模版内容
     **/
    private $tplContent;
    /**
     * 模版ID
     **/
    private $tplId;

    public function setTplContent($tplContent)
    {
        $this->tplContent = $tplContent;
        $this->apiParams['tpl_content'] = $tplContent;
    }

    public function getTplContent()
    {
        return $this->tplContent;
    }

    public function setTplId($tplId)
    {
        $this->tplId = $tplId;
        $this->apiParams['tpl_id'] = $tplId;
    }

    public function getTplId()
    {
        return $this->tplId;
    }
}
