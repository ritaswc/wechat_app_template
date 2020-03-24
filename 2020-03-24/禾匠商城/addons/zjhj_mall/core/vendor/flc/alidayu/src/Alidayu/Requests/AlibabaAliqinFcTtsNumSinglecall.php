<?php
namespace Flc\Alidayu\Requests;

use Flc\Alidayu\Support;

/**
 * 阿里大于 - 文本转语音通知
 *
 * @author Flc <2016-09-19 21:38:11>
 * @link   http://flc.ren
 * @link   http://open.taobao.com/docs/api.htm?apiId=25444
 */
class AlibabaAliqinFcTtsNumSinglecall extends Request implements IRequest
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'alibaba.aliqin.fc.tts.num.singlecall';

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->params = [
            'extend'          => '',  // 可选 公共回传参数，在“消息返回”中会透传回该参数；
            'tts_param'       => '',  // 可选 文本转语音（TTS）模板变量
            'called_num'      => '',  // 必须 被叫号码
            'called_show_num' => '',  // 必须 被叫号显
            'tts_code'        => ''   // 必须 TTS模板ID
        ];
    }

    /**
     * 设置被叫号码
     * @param string $value 被叫号码
     */
    public function setCalledNum($value)
    {
        $this->params['called_num'] = $value;

        return $this;
    }

    /**
     * 设置内容模板参数
     * @param array|string $value 模板参数
     */
    public function setTtsParam($value)
    {
        if (is_array($value)) {
            $value = Support::jsonStr($value);
        }

        $this->params['tts_param'] = $value;

        return $this;
    }

    /**
     * 设置被叫号显
     * @param string $value 被叫号显
     */
    public function setCalledShowNum($value)
    {
        $this->params['called_show_num'] = $value;

        return $this;
    }

    /**
     * 设置TTS模板ID
     * @param  string $value TTS模板ID
     */
    public function setTtsCode($value)
    {
        $this->params['tts_code'] = $value;

        return $this;
    }

    /**
     * 设置公共回传参数
     * @param string $value 公共回传参数
     */
    public function setExtend($value = '')
    {
        $this->params['extend'] = $value;

        return $this;
    }
}
