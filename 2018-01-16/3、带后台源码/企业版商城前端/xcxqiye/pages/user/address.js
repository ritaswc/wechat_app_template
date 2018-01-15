// pages/user/address.js
var common = require("../../utils/common.js");
var app = getApp();
Page({
  data: {
    "consigneeName": "",
    "consigneePhone": "",
    "consigneeArea": "",
    "consigneeAddress": "",
    "saveAddressResult":"",
    "addressList": []
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    this.initAddressPage();
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  },
  /**
   * 初始化页面数据
   */
  initAddressPage: function () {
    this.getAddressList();
    this.initInputData();
  },
  /**
   * 初始化输入框数据，清空
   */
  initInputData: function () {
    this.setData({
      consigneeName: "",
      consigneePhone: "",
      consigneeArea: "",
      consigneeAddress: ""
    })
  },
  /**
   * 获取收货地址列表，并填入页面数据
   */
  getAddressList: function () {
    var that = this;
    var uri = "/ztb/addressZBT/List";
    var dataMap = {
      userId: app.d.userId
    }
    var method = "post";
    var successCallback = function (response) {
      that.initAddressList(response.data.data);
    };
    common.sentHttpRequestToServer(uri, dataMap, method, successCallback);
  },
  /**
   * 初始化收货地址列表数据
   */
  initAddressList: function(dataList){
    this.setData({
      addressList: dataList
    })
  },

  /**
   * 监听控件事件，修改收货人姓名数据
   */
  setConsigneeName: function (event) {
    this.setData({
      consigneeName: event.detail.value
    })
  },
  /**
   * 监听控件事件，修改收货人电话数据
   */
  setConsigneePhone: function (event) {
    this.setData({
      consigneePhone: event.detail.value
    })
  },
  /**
   * 监听控件事件，修改收货人地域数据
   */
  setConsigneeArea: function (event) {
    this.setData({
      consigneeArea: event.detail.value
    })
  },
  /**
   * 监听控件时间，修改收货人地址数据
   */
  setConsigneeAddress: function (event) {
    this.setData({
      consigneeAddress: event.detail.value
    })
  },
  /**
   * 添加收货地址并进行非空判断
   */
  addAddress: function (event) {
    if (common.isStringEmpty(this.data.consigneeName)) {
      return;
    }
    if (common.isStringEmpty(this.data.consigneePhone)) {
      return;
    }
    if (common.isStringEmpty(this.data.consigneeArea)) {
      return;
    }
    if (common.isStringEmpty(this.data.consigneeAddress)) {
      return;
    }
    this.saveAddressData();
  },
  /**
   * 调用接口保存收货地址数据
   */
  saveAddressData: function () {
    var that = this;
    var uri = "/ztb/addressZBT/Add";
    var dataMap = {
      userId: app.d.userId,
      Receiver: this.data.consigneeName,
      PhoneNum: this.data.consigneePhone,
      Region: this.data.consigneeArea,
      DetailAddress: this.data.consigneeAddress
    };
    var method = "post";
    var successCallback = function (response) {
      common.toastSuccess();
      that.initAddressPage();
    };
    common.sentHttpRequestToServer(uri, dataMap, method, successCallback);
  }
})