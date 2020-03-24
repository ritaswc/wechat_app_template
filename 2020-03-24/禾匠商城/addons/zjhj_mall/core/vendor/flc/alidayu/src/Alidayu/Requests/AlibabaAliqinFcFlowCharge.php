<?php
namespace Flc\Alidayu\Requests;

/**
 * 阿里大于 - 流量直充
 *
 * @author Flc <2016-09-20 20:39:44>
 * @link   http://flc.ren
 * @link   http://open.taobao.com/docs/api.htm?apiId=26306
 */
class AlibabaAliqinFcFlowCharge extends Request implements IRequest
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'alibaba.aliqin.fc.flow.charge';

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->params = [
            'phone_num'       => '',  // 必须 手机号
            'reason'          => '',  // 可选 充值原因
            'grade'           => '',  // 必须 需要充值的流量
            'out_recharge_id' => '',  // 必须 唯一流水号
        ];
    }

    /**
     * 设置手机号
     * @param string $value 手机号
     */
    public function setPhoneNum($value)
    {
        $this->params['phone_num'] = $value;

        return $this;
    }

    /**
     * 设置需要充值的流量
     * @param string $value 需要充值的流量
     */
    public function setGrade($value)
    {
        $this->params['grade'] = $value;

        return $this;
    }

    /**
     * 设置唯一流水号
     * @param string $value 唯一流水号
     */
    public function setOutRechargeId($value)
    {
        $this->params['out_recharge_id'] = $value;

        return $this;
    }

    /**
     * 设置充值原因
     * @param  string $value 充值原因
     */
    public function setReason($value = '')
    {
        $this->params['reason'] = $value;

        return $this;
    }
}
