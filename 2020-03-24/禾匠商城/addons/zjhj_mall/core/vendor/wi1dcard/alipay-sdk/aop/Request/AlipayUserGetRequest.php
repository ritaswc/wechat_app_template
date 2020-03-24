<?php
/**
 * ALIPAY API: alipay.user.get request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-28 16:34:11
 */

namespace Alipay\Request;

class AlipayUserGetRequest extends AbstractAlipayRequest
{
    /**
     * 需要返回的字段列表。alipay_user_id：支付宝用户userId,user_status：支付宝用户状态,user_type：支付宝用户类型,certified：是否通过实名认证,real_name：真实姓名,logon_id：支付宝登录号,sex：用户性别
     **/
    private $fields;

    public function setFields($fields)
    {
        $this->fields = $fields;
        $this->apiParams['fields'] = $fields;
    }

    public function getFields()
    {
        return $this->fields;
    }
}
