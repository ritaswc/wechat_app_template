<?php
/**
 * ALIPAY API: alipay.security.info.analysis request
 *
 * @author auto create
 *
 * @since  1.0, 2016-03-04 14:55:20
 */

namespace Alipay\Request;

class AlipaySecurityInfoAnalysisRequest extends AbstractAlipayRequest
{
    /**
     * 客户端的基带版本
     **/
    private $envClientBaseBand;
    /**
     * 客户端连接的基站信息
     **/
    private $envClientBaseStation;
    /**
     * 客户端的经纬度坐标
     **/
    private $envClientCoordinates;
    /**
     * 操作的客户端的imei
     **/
    private $envClientImei;
    /**
     * 操作的客户端的imsi
     **/
    private $envClientImsi;
    /**
     * IOS设备的UDID
     **/
    private $envClientIosUdid;
    /**
     * 操作的客户端ip
     **/
    private $envClientIp;
    /**
     * 操作的客户端mac
     **/
    private $envClientMac;
    /**
     * 操作的客户端分辨率，格式为：水平像素^垂直像素；如：800^600
     **/
    private $envClientScreen;
    /**
     * 客户端设备的统一识别码UUID
     **/
    private $envClientUuid;
    /**
     * JS SDK生成的 tokenID
     **/
    private $jsTokenId;
    /**
     * 签约的支付宝账号对应的支付宝唯一用户号
     **/
    private $partnerId;
    /**
     * 场景编码
     **/
    private $sceneCode;
    /**
     * 卖家账户ID
     **/
    private $userAccountNo;
    /**
     * 用户绑定银行卡号
     **/
    private $userBindBankcard;
    /**
     * 用户绑定银行卡的卡类型
     **/
    private $userBindBankcardType;
    /**
     * 用户绑定手机号
     **/
    private $userBindMobile;
    /**
     * 用户证件类型
     **/
    private $userIdentityType;
    /**
     * 用户真实姓名
     **/
    private $userRealName;
    /**
     * 用户注册时间
     **/
    private $userRegDate;
    /**
     * 用户注册Email
     **/
    private $userRegEmail;
    /**
     * 用户注册手机号
     **/
    private $userRegMobile;
    /**
     * 用户证件号码
     **/
    private $userrIdentityNo;

    public function setEnvClientBaseBand($envClientBaseBand)
    {
        $this->envClientBaseBand = $envClientBaseBand;
        $this->apiParams['env_client_base_band'] = $envClientBaseBand;
    }

    public function getEnvClientBaseBand()
    {
        return $this->envClientBaseBand;
    }

    public function setEnvClientBaseStation($envClientBaseStation)
    {
        $this->envClientBaseStation = $envClientBaseStation;
        $this->apiParams['env_client_base_station'] = $envClientBaseStation;
    }

    public function getEnvClientBaseStation()
    {
        return $this->envClientBaseStation;
    }

    public function setEnvClientCoordinates($envClientCoordinates)
    {
        $this->envClientCoordinates = $envClientCoordinates;
        $this->apiParams['env_client_coordinates'] = $envClientCoordinates;
    }

    public function getEnvClientCoordinates()
    {
        return $this->envClientCoordinates;
    }

    public function setEnvClientImei($envClientImei)
    {
        $this->envClientImei = $envClientImei;
        $this->apiParams['env_client_imei'] = $envClientImei;
    }

    public function getEnvClientImei()
    {
        return $this->envClientImei;
    }

    public function setEnvClientImsi($envClientImsi)
    {
        $this->envClientImsi = $envClientImsi;
        $this->apiParams['env_client_imsi'] = $envClientImsi;
    }

    public function getEnvClientImsi()
    {
        return $this->envClientImsi;
    }

    public function setEnvClientIosUdid($envClientIosUdid)
    {
        $this->envClientIosUdid = $envClientIosUdid;
        $this->apiParams['env_client_ios_udid'] = $envClientIosUdid;
    }

    public function getEnvClientIosUdid()
    {
        return $this->envClientIosUdid;
    }

    public function setEnvClientIp($envClientIp)
    {
        $this->envClientIp = $envClientIp;
        $this->apiParams['env_client_ip'] = $envClientIp;
    }

    public function getEnvClientIp()
    {
        return $this->envClientIp;
    }

    public function setEnvClientMac($envClientMac)
    {
        $this->envClientMac = $envClientMac;
        $this->apiParams['env_client_mac'] = $envClientMac;
    }

    public function getEnvClientMac()
    {
        return $this->envClientMac;
    }

    public function setEnvClientScreen($envClientScreen)
    {
        $this->envClientScreen = $envClientScreen;
        $this->apiParams['env_client_screen'] = $envClientScreen;
    }

    public function getEnvClientScreen()
    {
        return $this->envClientScreen;
    }

    public function setEnvClientUuid($envClientUuid)
    {
        $this->envClientUuid = $envClientUuid;
        $this->apiParams['env_client_uuid'] = $envClientUuid;
    }

    public function getEnvClientUuid()
    {
        return $this->envClientUuid;
    }

    public function setJsTokenId($jsTokenId)
    {
        $this->jsTokenId = $jsTokenId;
        $this->apiParams['js_token_id'] = $jsTokenId;
    }

    public function getJsTokenId()
    {
        return $this->jsTokenId;
    }

    public function setPartnerId($partnerId)
    {
        $this->partnerId = $partnerId;
        $this->apiParams['partner_id'] = $partnerId;
    }

    public function getPartnerId()
    {
        return $this->partnerId;
    }

    public function setSceneCode($sceneCode)
    {
        $this->sceneCode = $sceneCode;
        $this->apiParams['scene_code'] = $sceneCode;
    }

    public function getSceneCode()
    {
        return $this->sceneCode;
    }

    public function setUserAccountNo($userAccountNo)
    {
        $this->userAccountNo = $userAccountNo;
        $this->apiParams['user_account_no'] = $userAccountNo;
    }

    public function getUserAccountNo()
    {
        return $this->userAccountNo;
    }

    public function setUserBindBankcard($userBindBankcard)
    {
        $this->userBindBankcard = $userBindBankcard;
        $this->apiParams['user_bind_bankcard'] = $userBindBankcard;
    }

    public function getUserBindBankcard()
    {
        return $this->userBindBankcard;
    }

    public function setUserBindBankcardType($userBindBankcardType)
    {
        $this->userBindBankcardType = $userBindBankcardType;
        $this->apiParams['user_bind_bankcard_type'] = $userBindBankcardType;
    }

    public function getUserBindBankcardType()
    {
        return $this->userBindBankcardType;
    }

    public function setUserBindMobile($userBindMobile)
    {
        $this->userBindMobile = $userBindMobile;
        $this->apiParams['user_bind_mobile'] = $userBindMobile;
    }

    public function getUserBindMobile()
    {
        return $this->userBindMobile;
    }

    public function setUserIdentityType($userIdentityType)
    {
        $this->userIdentityType = $userIdentityType;
        $this->apiParams['user_identity_type'] = $userIdentityType;
    }

    public function getUserIdentityType()
    {
        return $this->userIdentityType;
    }

    public function setUserRealName($userRealName)
    {
        $this->userRealName = $userRealName;
        $this->apiParams['user_real_name'] = $userRealName;
    }

    public function getUserRealName()
    {
        return $this->userRealName;
    }

    public function setUserRegDate($userRegDate)
    {
        $this->userRegDate = $userRegDate;
        $this->apiParams['user_reg_date'] = $userRegDate;
    }

    public function getUserRegDate()
    {
        return $this->userRegDate;
    }

    public function setUserRegEmail($userRegEmail)
    {
        $this->userRegEmail = $userRegEmail;
        $this->apiParams['user_reg_email'] = $userRegEmail;
    }

    public function getUserRegEmail()
    {
        return $this->userRegEmail;
    }

    public function setUserRegMobile($userRegMobile)
    {
        $this->userRegMobile = $userRegMobile;
        $this->apiParams['user_reg_mobile'] = $userRegMobile;
    }

    public function getUserRegMobile()
    {
        return $this->userRegMobile;
    }

    public function setUserrIdentityNo($userrIdentityNo)
    {
        $this->userrIdentityNo = $userrIdentityNo;
        $this->apiParams['userr_identity_no'] = $userrIdentityNo;
    }

    public function getUserrIdentityNo()
    {
        return $this->userrIdentityNo;
    }
}
