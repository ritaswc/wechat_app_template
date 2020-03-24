<?php
/**
 * ALIPAY API: alipay.pass.tpl.content.add request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 16:32:21
 */

namespace Alipay\Request;

class AlipayPassTplContentAddRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝用户识别信息：
     * 当 recognition_type=1时， recognition_info={“partner_id”:”2088102114633762”,“out_trade_no”:”1234567”}；
     * 当recognition_type=3时，recognition_info={“mobile”:”136XXXXXXXX“}
     * 当recognition_type=4时， recognition_info={“open_id”:”afbd8d9bb12fc02c5094d8ea89d1fae8“}
     **/
    private $recognitionInfo;
    /**
     * Alipass添加对象识别类型【1--订单信息;3--支付宝用户绑定手机号；4--支付宝OpenId;】
     **/
    private $recognitionType;
    /**
     * 支付宝pass模版ID
     **/
    private $tplId;
    /**
     * 模版动态参数信息【支付宝pass模版参数键值对JSON字符串】
     **/
    private $tplParams;

    public function setRecognitionInfo($recognitionInfo)
    {
        $this->recognitionInfo = $recognitionInfo;
        $this->apiParams['recognition_info'] = $recognitionInfo;
    }

    public function getRecognitionInfo()
    {
        return $this->recognitionInfo;
    }

    public function setRecognitionType($recognitionType)
    {
        $this->recognitionType = $recognitionType;
        $this->apiParams['recognition_type'] = $recognitionType;
    }

    public function getRecognitionType()
    {
        return $this->recognitionType;
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

    public function setTplParams($tplParams)
    {
        $this->tplParams = $tplParams;
        $this->apiParams['tpl_params'] = $tplParams;
    }

    public function getTplParams()
    {
        return $this->tplParams;
    }
}
