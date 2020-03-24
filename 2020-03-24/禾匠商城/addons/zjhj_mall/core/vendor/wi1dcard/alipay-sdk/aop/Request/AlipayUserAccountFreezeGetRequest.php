<?php
/**
 * ALIPAY API: alipay.user.account.freeze.get request
 *
 * @author auto create
 *
 * @since  1.0, 2016-08-11 15:48:05
 */

namespace Alipay\Request;

class AlipayUserAccountFreezeGetRequest extends AbstractAlipayRequest
{
    /**
     * 冻结类型，多个用,分隔。不传返回所有类型的冻结金额。 DEPOSIT_FREEZE,充值冻结 WITHDRAW_FREEZE,提现冻结 PAYMENT_FREEZE,支付冻结 BAIL_FREEZE,保证金冻结 CHARGE_FREEZE,收费冻结 PRE_DEPOSIT_FREEZE,预存款冻结 LOAN_FREEZE,贷款冻结 OTHER_FREEZE,其它
     **/
    private $freezeType;

    public function setFreezeType($freezeType)
    {
        $this->freezeType = $freezeType;
        $this->apiParams['freeze_type'] = $freezeType;
    }

    public function getFreezeType()
    {
        return $this->freezeType;
    }
}
