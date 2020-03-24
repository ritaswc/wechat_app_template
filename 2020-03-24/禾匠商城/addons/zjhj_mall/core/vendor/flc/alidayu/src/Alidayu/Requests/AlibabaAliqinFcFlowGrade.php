<?php
namespace Flc\Alidayu\Requests;

/**
 * 阿里大于 - 流量直充档位表
 *
 * @author Flc <2016-09-20 20:12:18>
 * @link   http://flc.ren
 * @link   http://open.taobao.com/docs/api.htm?apiId=26312
 */
class AlibabaAliqinFcFlowGrade extends Request implements IRequest
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'alibaba.aliqin.fc.flow.grade';

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->params = [];
    }
}
