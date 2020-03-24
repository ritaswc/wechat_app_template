<?php
namespace Flc\Alidayu\Requests;

/**
 * 阿里大于 - 语音通知
 *
 * @author Flc <2016-09-19 21:39:58>
 * @link   http://flc.ren
 * @link   http://open.taobao.com/docs/api.htm?apiId=25445
 */
class AlibabaAliqinFcVoiceNumSinglecall extends Request implements IRequest
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'alibaba.aliqin.fc.voice.num.singlecall';

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->params = [
            'extend'          => '',  // 可选 公共回传参数，在“消息返回”中会透传回该参数；
            'called_num'      => '',  // 必须 被叫号码
            'called_show_num' => '',  // 必须 被叫号显
            'voice_code'      => ''   // 必须 语音文件ID
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
     * 设置被叫号显
     * @param string $value 被叫号显
     */
    public function setCalledShowNum($value)
    {
        $this->params['called_show_num'] = $value;

        return $this;
    }

    /**
     * 设置语音文件ID
     * @param  string $value 语音文件ID
     */
    public function setVoiceCode($value)
    {
        $this->params['voice_code'] = $value;

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
