<?php
namespace Flc\Alidayu\Requests;

use Flc\Alidayu\Support;

/**
 * 阿里大于 - 短信发送
 *
 * @author Flc <2016-09-18 19:43:18>
 * @link   http://flc.ren
 * @link   http://open.taobao.com/docs/api.htm?apiId=25450
 */
class AlibabaAliqinFcSmsNumSend extends Request implements IRequest
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'alibaba.aliqin.fc.sms.num.send';

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->params = [
            'extend'             => '',  // 可选 公共回传参数，在“消息返回”中会透传回该参数；
            'sms_type'           => 'normal',  // 必须 短信类型，传入值请填写normal
            'sms_free_sign_name' => '',  // 必须 短信签名，传入的短信签名必须是在阿里大于“管理中心-短信签名管理”中的可用签名
            'sms_param'          => '',  // 可选 短信模板变量，传参规则{"key":"value"}，key的名字须和申请模板中的变量名一致，多个变量之间以逗号隔开
            'rec_num'            => '',  // 必须 短信接收号码。支持单个或多个，传入号码为11位手机号码，不能加0或+86。传入多个号码，以英文逗号分隔，一次调用最多传入200个号码
            'sms_template_code'  => ''  // 必须 短信模板ID，传入的模板必须是在阿里大于“管理中心-短信模板管理”中的可用模板
        ];
    }

    /**
     * 设置短信接受的手机号
     * @param string|array $value 手机号
     */
    public function setRecNum($value)
    {
        if (is_array($value))
            $value = implode(',', $value);

        $this->params['rec_num'] = $value;

        return $this;
    }

    /**
     * 设置短信内容模板参数
     * @param array|string $value 模板参数
     */
    public function setSmsParam($value)
    {
        if (is_array($value)) {
            $value = Support::jsonStr($value);
        }

        $this->params['sms_param'] = $value;

        return $this;
    }

    /**
     * 设置短信签名
     * @param string $value 短信签名
     */
    public function setSmsFreeSignName($value)
    {
        $this->params['sms_free_sign_name'] = $value;

        return $this;
    }

    /**
     * 短信模板ID
     * @param  string $value 短信模板ID
     */
    public function setSmsTemplateCode($value)
    {
        $this->params['sms_template_code'] = $value;

        return $this;
    }

    /**
     * 设置短信类型，传入值请填写normal
     * @param  string $value 短信类型
     */
    public function smsType($value = 'normal')
    {
        $this->params['sms_type'] = $value;

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
