<?php
/**
 * ALIPAY API: alipay.security.risk.detect request
 *
 * @author auto create
 *
 * @since  1.0, 2016-03-04 14:55:25
 */

namespace Alipay\Request;

class AlipaySecurityRiskDetectRequest extends AbstractAlipayRequest
{
    /**
     * 买家账户编号
     **/
    private $buyerAccountNo;
    /**
     * 买家绑定银行卡号
     **/
    private $buyerBindBankcard;
    /**
     * 买家绑定银行卡的卡类型
     **/
    private $buyerBindBankcardType;
    /**
     * 买家绑定手机号
     **/
    private $buyerBindMobile;
    /**
     * 买家账户在商家的等级，范围：VIP（高级买家）, NORMAL(普通买家）。为空默认NORMAL
     **/
    private $buyerGrade;
    /**
     * 买家证件号码
     **/
    private $buyerIdentityNo;
    /**
     * 买家证件类型
     **/
    private $buyerIdentityType;
    /**
     * 买家真实姓名
     **/
    private $buyerRealName;
    /**
     * 买家注册时间
     **/
    private $buyerRegDate;
    /**
     * 买家注册时留的Email
     **/
    private $buyerRegEmail;
    /**
     * 买家注册手机号
     **/
    private $buyerRegMobile;
    /**
     * 买家业务处理时使用的银行卡号
     **/
    private $buyerSceneBankcard;
    /**
     * 买家业务处理时使用的银行卡类型
     **/
    private $buyerSceneBankcardType;
    /**
     * 买家业务处理时使用的邮箱
     **/
    private $buyerSceneEmail;
    /**
     * 买家业务处理时使用的手机号
     **/
    private $buyerSceneMobile;
    /**
     * 币种
     **/
    private $currency;
    /**
     * 客户端的基带版本
     **/
    private $envClientBaseBand;
    /**
     * 客户端连接的基站信息,格式为：CELLID^LAC
     **/
    private $envClientBaseStation;
    /**
     * 客户端的经纬度坐标,格式为：精度^维度
     **/
    private $envClientCoordinates;
    /**
     * 操作的客户端的imei
     **/
    private $envClientImei;
    /**
     * 操作的客户端IMSI识别码
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
     * 订单产品数量，购买产品的数量（不可为小数）
     **/
    private $itemQuantity;
    /**
     * 订单产品单价，取值范围为[0.01,100000000.00]，精确到小数点后两位。 curren...
     **/
    private $itemUnitPrice;
    /**
     * JS SDK生成的 tokenID
     **/
    private $jsTokenId;
    /**
     * 订单总金额，取值范围为[0.01,100000000.00]，精确到小数点后两位。
     **/
    private $orderAmount;
    /**
     * 订单商品所在类目
     **/
    private $orderCategory;
    /**
     * 订单下单时间
     **/
    private $orderCredateTime;
    /**
     * 订单商品所在城市
     **/
    private $orderItemCity;
    /**
     * 订单产品名称
     **/
    private $orderItemName;
    /**
     * 商户订单唯一标识号
     **/
    private $orderNo;
    /**
     * 签约的支付宝账号对应的支付宝唯一用户号
     **/
    private $partnerId;
    /**
     * 订单收货人地址
     **/
    private $receiverAddress;
    /**
     * 订单收货人地址城市
     **/
    private $receiverCity;
    /**
     * 订单收货人地址所在区
     **/
    private $receiverDistrict;
    /**
     * 订单收货人邮箱
     **/
    private $receiverEmail;
    /**
     * 订单收货人手机
     **/
    private $receiverMobile;
    /**
     * 订单收货人姓名
     **/
    private $receiverName;
    /**
     * 订单收货人地址省份
     **/
    private $receiverState;
    /**
     * 订单收货人地址邮编
     **/
    private $receiverZip;
    /**
     * 场景编码
     **/
    private $sceneCode;
    /**
     * 卖家账户编号
     **/
    private $sellerAccountNo;
    /**
     * 卖家绑定银行卡号
     **/
    private $sellerBindBankcard;
    /**
     * 卖家绑定的银行卡的卡类型
     **/
    private $sellerBindBankcardType;
    /**
     * 卖家绑定手机号
     **/
    private $sellerBindMobile;
    /**
     * 卖家证件号码
     **/
    private $sellerIdentityNo;
    /**
     * 卖家证件类型
     **/
    private $sellerIdentityType;
    /**
     * 卖家真实姓名
     **/
    private $sellerRealName;
    /**
     * 卖家注册时间,格式为：yyyy-MM-dd。
     **/
    private $sellerRegDate;
    /**
     * 卖家注册Email
     **/
    private $sellerRegEmail;
    /**
     * 卖家注册手机号
     **/
    private $sellerRegMoile;
    /**
     * 订单物流方式
     **/
    private $transportType;

    public function setBuyerAccountNo($buyerAccountNo)
    {
        $this->buyerAccountNo = $buyerAccountNo;
        $this->apiParams['buyer_account_no'] = $buyerAccountNo;
    }

    public function getBuyerAccountNo()
    {
        return $this->buyerAccountNo;
    }

    public function setBuyerBindBankcard($buyerBindBankcard)
    {
        $this->buyerBindBankcard = $buyerBindBankcard;
        $this->apiParams['buyer_bind_bankcard'] = $buyerBindBankcard;
    }

    public function getBuyerBindBankcard()
    {
        return $this->buyerBindBankcard;
    }

    public function setBuyerBindBankcardType($buyerBindBankcardType)
    {
        $this->buyerBindBankcardType = $buyerBindBankcardType;
        $this->apiParams['buyer_bind_bankcard_type'] = $buyerBindBankcardType;
    }

    public function getBuyerBindBankcardType()
    {
        return $this->buyerBindBankcardType;
    }

    public function setBuyerBindMobile($buyerBindMobile)
    {
        $this->buyerBindMobile = $buyerBindMobile;
        $this->apiParams['buyer_bind_mobile'] = $buyerBindMobile;
    }

    public function getBuyerBindMobile()
    {
        return $this->buyerBindMobile;
    }

    public function setBuyerGrade($buyerGrade)
    {
        $this->buyerGrade = $buyerGrade;
        $this->apiParams['buyer_grade'] = $buyerGrade;
    }

    public function getBuyerGrade()
    {
        return $this->buyerGrade;
    }

    public function setBuyerIdentityNo($buyerIdentityNo)
    {
        $this->buyerIdentityNo = $buyerIdentityNo;
        $this->apiParams['buyer_identity_no'] = $buyerIdentityNo;
    }

    public function getBuyerIdentityNo()
    {
        return $this->buyerIdentityNo;
    }

    public function setBuyerIdentityType($buyerIdentityType)
    {
        $this->buyerIdentityType = $buyerIdentityType;
        $this->apiParams['buyer_identity_type'] = $buyerIdentityType;
    }

    public function getBuyerIdentityType()
    {
        return $this->buyerIdentityType;
    }

    public function setBuyerRealName($buyerRealName)
    {
        $this->buyerRealName = $buyerRealName;
        $this->apiParams['buyer_real_name'] = $buyerRealName;
    }

    public function getBuyerRealName()
    {
        return $this->buyerRealName;
    }

    public function setBuyerRegDate($buyerRegDate)
    {
        $this->buyerRegDate = $buyerRegDate;
        $this->apiParams['buyer_reg_date'] = $buyerRegDate;
    }

    public function getBuyerRegDate()
    {
        return $this->buyerRegDate;
    }

    public function setBuyerRegEmail($buyerRegEmail)
    {
        $this->buyerRegEmail = $buyerRegEmail;
        $this->apiParams['buyer_reg_email'] = $buyerRegEmail;
    }

    public function getBuyerRegEmail()
    {
        return $this->buyerRegEmail;
    }

    public function setBuyerRegMobile($buyerRegMobile)
    {
        $this->buyerRegMobile = $buyerRegMobile;
        $this->apiParams['buyer_reg_mobile'] = $buyerRegMobile;
    }

    public function getBuyerRegMobile()
    {
        return $this->buyerRegMobile;
    }

    public function setBuyerSceneBankcard($buyerSceneBankcard)
    {
        $this->buyerSceneBankcard = $buyerSceneBankcard;
        $this->apiParams['buyer_scene_bankcard'] = $buyerSceneBankcard;
    }

    public function getBuyerSceneBankcard()
    {
        return $this->buyerSceneBankcard;
    }

    public function setBuyerSceneBankcardType($buyerSceneBankcardType)
    {
        $this->buyerSceneBankcardType = $buyerSceneBankcardType;
        $this->apiParams['buyer_scene_bankcard_type'] = $buyerSceneBankcardType;
    }

    public function getBuyerSceneBankcardType()
    {
        return $this->buyerSceneBankcardType;
    }

    public function setBuyerSceneEmail($buyerSceneEmail)
    {
        $this->buyerSceneEmail = $buyerSceneEmail;
        $this->apiParams['buyer_scene_email'] = $buyerSceneEmail;
    }

    public function getBuyerSceneEmail()
    {
        return $this->buyerSceneEmail;
    }

    public function setBuyerSceneMobile($buyerSceneMobile)
    {
        $this->buyerSceneMobile = $buyerSceneMobile;
        $this->apiParams['buyer_scene_mobile'] = $buyerSceneMobile;
    }

    public function getBuyerSceneMobile()
    {
        return $this->buyerSceneMobile;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        $this->apiParams['currency'] = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

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

    public function setItemQuantity($itemQuantity)
    {
        $this->itemQuantity = $itemQuantity;
        $this->apiParams['item_quantity'] = $itemQuantity;
    }

    public function getItemQuantity()
    {
        return $this->itemQuantity;
    }

    public function setItemUnitPrice($itemUnitPrice)
    {
        $this->itemUnitPrice = $itemUnitPrice;
        $this->apiParams['item_unit_price'] = $itemUnitPrice;
    }

    public function getItemUnitPrice()
    {
        return $this->itemUnitPrice;
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

    public function setOrderAmount($orderAmount)
    {
        $this->orderAmount = $orderAmount;
        $this->apiParams['order_amount'] = $orderAmount;
    }

    public function getOrderAmount()
    {
        return $this->orderAmount;
    }

    public function setOrderCategory($orderCategory)
    {
        $this->orderCategory = $orderCategory;
        $this->apiParams['order_category'] = $orderCategory;
    }

    public function getOrderCategory()
    {
        return $this->orderCategory;
    }

    public function setOrderCredateTime($orderCredateTime)
    {
        $this->orderCredateTime = $orderCredateTime;
        $this->apiParams['order_credate_time'] = $orderCredateTime;
    }

    public function getOrderCredateTime()
    {
        return $this->orderCredateTime;
    }

    public function setOrderItemCity($orderItemCity)
    {
        $this->orderItemCity = $orderItemCity;
        $this->apiParams['order_item_city'] = $orderItemCity;
    }

    public function getOrderItemCity()
    {
        return $this->orderItemCity;
    }

    public function setOrderItemName($orderItemName)
    {
        $this->orderItemName = $orderItemName;
        $this->apiParams['order_item_name'] = $orderItemName;
    }

    public function getOrderItemName()
    {
        return $this->orderItemName;
    }

    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;
        $this->apiParams['order_no'] = $orderNo;
    }

    public function getOrderNo()
    {
        return $this->orderNo;
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

    public function setReceiverAddress($receiverAddress)
    {
        $this->receiverAddress = $receiverAddress;
        $this->apiParams['receiver_address'] = $receiverAddress;
    }

    public function getReceiverAddress()
    {
        return $this->receiverAddress;
    }

    public function setReceiverCity($receiverCity)
    {
        $this->receiverCity = $receiverCity;
        $this->apiParams['receiver_city'] = $receiverCity;
    }

    public function getReceiverCity()
    {
        return $this->receiverCity;
    }

    public function setReceiverDistrict($receiverDistrict)
    {
        $this->receiverDistrict = $receiverDistrict;
        $this->apiParams['receiver_district'] = $receiverDistrict;
    }

    public function getReceiverDistrict()
    {
        return $this->receiverDistrict;
    }

    public function setReceiverEmail($receiverEmail)
    {
        $this->receiverEmail = $receiverEmail;
        $this->apiParams['receiver_email'] = $receiverEmail;
    }

    public function getReceiverEmail()
    {
        return $this->receiverEmail;
    }

    public function setReceiverMobile($receiverMobile)
    {
        $this->receiverMobile = $receiverMobile;
        $this->apiParams['receiver_mobile'] = $receiverMobile;
    }

    public function getReceiverMobile()
    {
        return $this->receiverMobile;
    }

    public function setReceiverName($receiverName)
    {
        $this->receiverName = $receiverName;
        $this->apiParams['receiver_name'] = $receiverName;
    }

    public function getReceiverName()
    {
        return $this->receiverName;
    }

    public function setReceiverState($receiverState)
    {
        $this->receiverState = $receiverState;
        $this->apiParams['receiver_state'] = $receiverState;
    }

    public function getReceiverState()
    {
        return $this->receiverState;
    }

    public function setReceiverZip($receiverZip)
    {
        $this->receiverZip = $receiverZip;
        $this->apiParams['receiver_zip'] = $receiverZip;
    }

    public function getReceiverZip()
    {
        return $this->receiverZip;
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

    public function setSellerAccountNo($sellerAccountNo)
    {
        $this->sellerAccountNo = $sellerAccountNo;
        $this->apiParams['seller_account_no'] = $sellerAccountNo;
    }

    public function getSellerAccountNo()
    {
        return $this->sellerAccountNo;
    }

    public function setSellerBindBankcard($sellerBindBankcard)
    {
        $this->sellerBindBankcard = $sellerBindBankcard;
        $this->apiParams['seller_bind_bankcard'] = $sellerBindBankcard;
    }

    public function getSellerBindBankcard()
    {
        return $this->sellerBindBankcard;
    }

    public function setSellerBindBankcardType($sellerBindBankcardType)
    {
        $this->sellerBindBankcardType = $sellerBindBankcardType;
        $this->apiParams['seller_bind_bankcard_type'] = $sellerBindBankcardType;
    }

    public function getSellerBindBankcardType()
    {
        return $this->sellerBindBankcardType;
    }

    public function setSellerBindMobile($sellerBindMobile)
    {
        $this->sellerBindMobile = $sellerBindMobile;
        $this->apiParams['seller_bind_mobile'] = $sellerBindMobile;
    }

    public function getSellerBindMobile()
    {
        return $this->sellerBindMobile;
    }

    public function setSellerIdentityNo($sellerIdentityNo)
    {
        $this->sellerIdentityNo = $sellerIdentityNo;
        $this->apiParams['seller_identity_no'] = $sellerIdentityNo;
    }

    public function getSellerIdentityNo()
    {
        return $this->sellerIdentityNo;
    }

    public function setSellerIdentityType($sellerIdentityType)
    {
        $this->sellerIdentityType = $sellerIdentityType;
        $this->apiParams['seller_identity_type'] = $sellerIdentityType;
    }

    public function getSellerIdentityType()
    {
        return $this->sellerIdentityType;
    }

    public function setSellerRealName($sellerRealName)
    {
        $this->sellerRealName = $sellerRealName;
        $this->apiParams['seller_real_name'] = $sellerRealName;
    }

    public function getSellerRealName()
    {
        return $this->sellerRealName;
    }

    public function setSellerRegDate($sellerRegDate)
    {
        $this->sellerRegDate = $sellerRegDate;
        $this->apiParams['seller_reg_date'] = $sellerRegDate;
    }

    public function getSellerRegDate()
    {
        return $this->sellerRegDate;
    }

    public function setSellerRegEmail($sellerRegEmail)
    {
        $this->sellerRegEmail = $sellerRegEmail;
        $this->apiParams['seller_reg_email'] = $sellerRegEmail;
    }

    public function getSellerRegEmail()
    {
        return $this->sellerRegEmail;
    }

    public function setSellerRegMoile($sellerRegMoile)
    {
        $this->sellerRegMoile = $sellerRegMoile;
        $this->apiParams['seller_reg_moile'] = $sellerRegMoile;
    }

    public function getSellerRegMoile()
    {
        return $this->sellerRegMoile;
    }

    public function setTransportType($transportType)
    {
        $this->transportType = $transportType;
        $this->apiParams['transport_type'] = $transportType;
    }

    public function getTransportType()
    {
        return $this->transportType;
    }
}
