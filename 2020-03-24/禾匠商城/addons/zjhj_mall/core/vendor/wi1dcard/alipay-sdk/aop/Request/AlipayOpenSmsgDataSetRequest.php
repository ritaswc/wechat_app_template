<?php
/**
 * ALIPAY API: alipay.open.smsg.data.set request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-09 11:16:20
 */

namespace Alipay\Request;

class AlipayOpenSmsgDataSetRequest extends AbstractAlipayRequest
{
    /**
     * to蚂蚁消息测试-数据重置
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
