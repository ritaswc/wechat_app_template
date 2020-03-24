<?php
/**
 * ALIPAY API: alipay.daowei.sp.schedule.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-10 16:05:46
 */

namespace Alipay\Request;

class AlipayDaoweiSpScheduleModifyRequest extends AbstractAlipayRequest
{
    /**
     * 更新服务者可用时间接口
     **/
    private $bizContent;

    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParams['biz_content'] = $bizContent;
    }

    public function getBizContent()
    {
        return $this->bizContent;
    }
}
