<?php
namespace Flc\Alidayu\Requests;

/**
 * 阿里大于 - 短信发送记录查询
 *
 * @author Flc <2016-09-19 21:38:11>
 * @link   http://flc.ren
 * @link   http://open.taobao.com/docs/api.htm?apiId=26039
 */
class AlibabaAliqinFcSmsNumQuery extends Request implements IRequest
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'alibaba.aliqin.fc.sms.num.query';

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->params = [
            'biz_id'       => '',  // 可选 短信发送流水
            'rec_num'      => '',  // 必须 短信接收号码
            'query_date'   => date('Ymd'),  // 必须 短信发送日期，支持近30天记录查询，格式yyyyMMdd
            'current_page' => 1,  // 必须 分页参数,页码
            'page_size'    => 10   // 必须 分页参数，每页数量。最大值50
        ];
    }

    /**
     * 设置短信发送流水
     * @param string $value 短信发送流水
     */
    public function setBizId($value)
    {
        $this->params['biz_id'] = $value;

        return $this;
    }

    /**
     * 设置短信接收号码
     * @param string $value 短信接收号码
     */
    public function setRecNum($value)
    {
        $this->params['rec_num'] = $value;

        return $this;
    }

    /**
     * 设置短信发送日期
     * @param string $value 短信发送日期，支持近30天记录查询，格式yyyyMMdd
     */
    public function setQueryDate($value)
    {
        $this->params['query_date'] = $value;

        return $this;
    }

    /**
     * 设置分页参数,页码
     * @param  string $value 分页参数,页码
     */
    public function setCurrentPage($value = 1)
    {
        $this->params['current_page'] = $value;

        return $this;
    }

    /**
     * 设置分页参数，每页数量。
     * @param  string $value 分页参数，每页数量。最大值50
     */
    public function setPageSize($value = 10)
    {
        $this->params['page_size'] = $value;

        return $this;
    }
}
