<?php
/**
 * ALIPAY API: alipay.zdatafront.datatransfered.send request
 *
 * @author auto create
 *
 * @since  1.0, 2017-05-18 11:27:33
 */

namespace Alipay\Request;

class AlipayZdatafrontDatatransferedSendRequest extends AbstractAlipayRequest
{
    /**
     * 数据字段，identity对应的其他数据字段。使用json格式组织，且仅支持字符串类型，其他类型请转为字符串。
     **/
    private $data;
    /**
     * 合作伙伴的主键数据，同一合作伙伴要保证该字段唯一，若出现重复，后入数据会覆盖先入数据。使用json格式组织，且仅支持字符串类型，其他类型请转为字符串。
     **/
    private $identity;
    /**
     * 合作伙伴标识字段，用来区分数据来源。建议使用公司域名或公司名。
     **/
    private $typeId;

    public function setData($data)
    {
        $this->data = $data;
        $this->apiParams['data'] = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setIdentity($identity)
    {
        $this->identity = $identity;
        $this->apiParams['identity'] = $identity;
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
        $this->apiParams['type_id'] = $typeId;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }
}
