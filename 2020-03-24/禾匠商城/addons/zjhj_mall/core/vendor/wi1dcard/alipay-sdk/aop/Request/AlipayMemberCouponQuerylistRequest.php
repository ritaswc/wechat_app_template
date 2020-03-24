<?php
/**
 * ALIPAY API: alipay.member.coupon.querylist request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 11:46:40
 */

namespace Alipay\Request;

class AlipayMemberCouponQuerylistRequest extends AbstractAlipayRequest
{
    /**
     * 红包发放者商户信息，json格式。商户可以传自己的PID，平台商可以传其它商户的PID用于查询指定商户的信息
     * 目前仅支持如下key：
     * unique：商户唯一标识
     * unique_type：支持以下1种取值。
     * PID：商户所在平台商的partner id[唯一]
     **/
    private $merchantInfo;
    /**
     * 翻页页码：翻页查询时使用，表明当前要查询第几页，若page_size为0，则此字段不生效
     **/
    private $pageNo;
    /**
     * 翻页每页条数：翻页查询时使用，表明每页返回的记录数量，范围为1至20；为空或者为0时表示不使用翻页查询，返回所有数量
     **/
    private $pageSize;
    /**
     * 优惠券状态列表，如果指定则只返回指定状态的优惠券.
     * 目前状态主要有以下几种：
     * VALID：可使用
     * WRITED_OFF：已核销,
     * EXPIRED：已过期
     * CLOSED：已关闭
     * 注意：
     * 多个状态以逗号隔开
     **/
    private $status;
    /**
     * 劵所有者买家用户信息，必须是支付宝的用户，json格式。
     * 目前仅支持如下key：
     * unique：用户唯一标识
     * unique_type：支持以下1种取值。
     * UID：用户支付宝账户的唯一ID
     * OPENID：用户支付宝账户在某商户下的唯一ID
     **/
    private $userInfo;

    public function setMerchantInfo($merchantInfo)
    {
        $this->merchantInfo = $merchantInfo;
        $this->apiParams['merchant_info'] = $merchantInfo;
    }

    public function getMerchantInfo()
    {
        return $this->merchantInfo;
    }

    public function setPageNo($pageNo)
    {
        $this->pageNo = $pageNo;
        $this->apiParams['page_no'] = $pageNo;
    }

    public function getPageNo()
    {
        return $this->pageNo;
    }

    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        $this->apiParams['page_size'] = $pageSize;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        $this->apiParams['status'] = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setUserInfo($userInfo)
    {
        $this->userInfo = $userInfo;
        $this->apiParams['user_info'] = $userInfo;
    }

    public function getUserInfo()
    {
        return $this->userInfo;
    }
}
