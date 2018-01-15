// pages/address/address.js
var city = require('../../utils/city.js')
var detailedInfo;
var consigneeName;
var phoneNum;
var address = {};
var addressList = [
];
Page({
  data: {
    provinceList: [],
    cityList: [],
    districtList: [],
    indexProvince: 0,
    indexCity: 0,
    indexDistrict: 0,
    isChecked: false,
  },
  //改变省
  provinceChange: function (e) {
    this.setData({
      indexProvince: e.detail.value,
      indexCity: 0,
      indexDistrict: 0,
    });
    //改变市和区
    this.setData({
      cityList: city.getCity(this.data.provinceList[this.data.indexProvince])
    });
    this.setData({
      districtList: city.getArea(this.data.provinceList[this.data.indexProvince], this.data.cityList[this.data.indexCity])
    });
  },
  //改变市
  cityChange: function (e) {
    this.setData({
      indexCity: e.detail.value,
      indexDistrict: 0,
    });
    //改变区
    this.setData({
      districtList: city.getArea(this.data.provinceList[this.data.indexProvince], this.data.cityList[this.data.indexCity])
    });
  },
  //改变区区/县
  districtChange: function (e) {
    this.setData({
      indexDistrict: e.detail.value,
    });
  },
  /**
   * 获取详细地址
   */
  getAddressInfo: function (e) {
    detailedInfo = e.detail.value;
    console.log("detailedInfo:" + detailedInfo);
  },
  /**
   * 获取联系人名称
   */
  getConsigneeName: function (e) {
    consigneeName = e.detail.value;
    console.log("consigneeName:" + consigneeName);
  },
  /**
 * 获取联系人电话
 */
  getPhoneNum: function (e) {
    phoneNum = e.detail.value;
    console.log("phoneNum:" + phoneNum);
  },

  //选择默认地址
  checkboxChange: function (e) {
    this.setData({
      isChecked: !this.data.isChecked
    });
    for (var key in addressList) {
      addressList[key].isDefult = false;
    }
  },

  /**
   * 点击添加地址
   */
  addNewAdress: function (e) {

    address.province = this.data.provinceList[this.data.indexProvince];
    address.city = this.data.cityList[this.data.indexCity];
    address.district = this.data.districtList[this.data.indexDistrict];
    address.detailedInfo = detailedInfo;
    address.consigneeName = consigneeName;
    address.phoneNum = phoneNum;
    address.isDefult = this.data.isChecked;
    addressList.push(address);

    wx.setStorageSync('address', addressList)
    wx.navigateBack({
      delta: 1, // 回退前 delta(默认为1) 页面
    })
  },


  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    this.setData({
      provinceList: city.getProvince()
    });
    this.setData({
      cityList: city.getCity(this.data.provinceList[this.data.indexProvince])
    });
    this.setData({
      districtList: city.getArea(this.data.provinceList[this.data.indexProvince], this.data.cityList[this.data.indexCity])
    });
    addressList = wx.getStorageSync('address').length > 0 ? wx.getStorageSync('address') : [];
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
  }
})