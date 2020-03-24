<?php
namespace Flc\Alidayu\Requests;

/**
 * 阿里大于 - 多方通话
 *
 * @author Flc <2016-09-20 20:29:29>
 * @link   http://flc.ren
 * @link   http://open.taobao.com/docs/api.htm?apiId=25443
 */
class AlibabaAliqinFcVoiceNumDoublecall extends Request implements IRequest
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'alibaba.aliqin.fc.voice.num.doublecall';

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->params = [
            'session_time_out' => '',  // 可选 通话超时时长
            'extend'           => '',  // 可选 公共回传参数
            'caller_num'       => '',  // 必须 主叫号码
            'caller_show_num'  => '',  // 必须 主叫号码侧的号码显示
            'called_num'       => '',   // 必须 被叫号码
            'called_show_num'  => ''   // 必须 被叫号码侧的号码显示
        ];
    }

    /**
     * 设置主叫号码
     * @param string $value 主叫号码
     */
    public function setCallerNum($value)
    {
        $this->params['caller_num'] = $value;

        return $this;
    }

    /**
     * 设置主叫号码侧的号码显示
     * @param string $value 主叫号码侧的号码显示
     */
    public function setCallerShowNum($value)
    {
        $this->params['caller_show_num'] = $value;

        return $this;
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
     * 设置被叫号码侧的号码显示
     * @param  string $value 被叫号码侧的号码显示
     */
    public function setCalledShowNum($value)
    {
        $this->params['called_show_num'] = $value;

        return $this;
    }

    /**
     * 设置通话超时时长
     * @param  string $value 通话超时时长，单位秒
     */
    public function setSessionTimeOut($value = 120)
    {
        $this->params['session_time_out'] = $value;

        return $this;
    }

    /**
     * 设置公共回传参数
     * @param  string $value 公共回传参数
     */
    public function setExtend($value = 120)
    {
        $this->params['extend'] = $value;

        return $this;
    }
}
