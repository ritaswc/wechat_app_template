'use strict';

// 获取全局应用程序实例对象
// const app = getApp()
var tcity = require('../../utils/city');
// 创建页面实例对象
Page({
  /**
   * 页面的初始数据
   */
  data: {
    title: 'businessCooperation',
    // 地区选择相关
    provinces: [],
    province: '',
    citys: [],
    city: '',
    countys: [],
    county: '',
    value: [0, 0, 0],
    values: [0, 0, 0],
    condition: false,
    // 地区选择相关
    shopAddress: '添加地图标记',
    showMain: true,
    allHidden: false,
    hiddenMain: false,
    // 图片上传
    addPicText: '立即上传',
    faceImg: '../../images/4.png',
    insideImg: '../../images/5.png',
    IdFaceImg: '../../images/6.png',
    licenseImg: '../../images/2.png',
    restaurantLicenseImg: '../../images/3.png',
    upStatus: 0,
    license: [{ name: 'noraml', value: '营业执照', checked: 'true' }, { name: 'special', value: '特许证件' }],
    licenseTime: [{ name: 'noTime', value: '长期有效', checked: 'true' }, { name: 'haveTime', value: '固定有效期' }],
    restaurantLicenseTime: [{ name: 'noTime', value: '长期有效', checked: 'true' }, { name: 'haveTime', value: '固定有效期' }]
  },
  /**
   * 信息录入
   * @param e
   */
  inputMessage: function inputMessage(e) {
    var obj = {};
    obj[e.currentTarget.dataset.type] = e.detail.value;
    this.setData(obj);
  },

  /**
   * 执行下一步操作
   */
  //
  //
  nextStep2: function nextStep2() {
    if (!this.data.xkzAddress || !this.data.xkzNumber || !this.data.xkzName || !this.data.zzAddress || !this.data.zzNumber || !this.data.zzName || !this.data.frIdNumber || !this.data.frName || this.data.IdFaceImg.indexOf('wxfile') === -1 || this.data.licenseImg.indexOf('wxfile') === -1 || this.data.restaurantLicenseImg.indexOf('wxfile') === -1) {
      return wx.showModal({
        title: '抱歉',
        content: '请补全您的资质信息，再进行下一步操作',
        showCancel: false
      });
    }
    this.setData({
      allHidden: true
    });
  },

  /**
   * 单项选择设置
   * @param e
   */
  radioChange: function radioChange(e) {
    console.log('radio发生change事件，携带value值为：', e.detail.value);
  },

  /**
   * 选择地区
   * @param e
   */
  bindChange: function bindChange(e) {
    // console.log(e);
    var val = e.detail.value;
    var t = this.data.values;
    var cityData = this.data.cityData;

    if (val[0] !== t[0]) {
      console.log('province no ');
      var citys = [];
      var countys = [];

      for (var i = 0; i < cityData[val[0]].sub.length; i++) {
        citys.push(cityData[val[0]].sub[i].name);
      }
      for (var _i = 0; _i < cityData[val[0]].sub[0].sub.length; _i++) {
        countys.push(cityData[val[0]].sub[0].sub[_i].name);
      }
      this.setData({
        province: this.data.provinces[val[0]],
        city: cityData[val[0]].sub[0].name,
        citys: citys,
        county: cityData[val[0]].sub[0].sub[0].name,
        countys: countys,
        values: val,
        value: [val[0], 0, 0]
      });
      return;
    }
    if (val[1] !== t[1]) {
      console.log('city no');
      var _countys = [];
      for (var _i2 = 0; _i2 < cityData[val[0]].sub[val[1]].sub.length; _i2++) {
        _countys.push(cityData[val[0]].sub[val[1]].sub[_i2].name);
      }
      this.setData({
        city: this.data.citys[val[1]],
        county: cityData[val[0]].sub[val[1]].sub[0].name,
        countys: _countys,
        values: val,
        value: [val[0], val[1], 0]
      });
      return;
    }
    if (val[2] !== t[2]) {
      // console.log('county no')
      this.setData({
        county: this.data.countys[val[2]],
        values: val,
        value: [val[0], val[1], val[2]]
      });
      return;
    }
  },
  /**
   * 地区显示开关
   */
  open: function open() {
    this.setData({
      condition: !this.data.condition
    });
  },
  /**
   * 添加店铺图片
   */
  addPic: function addPic() {
    this.setData({
      showMain: false
    });
  },

  /**
   * 选择地址
   */
  addMapSite: function addMapSite() {
    var that = this;
    wx.chooseLocation({
      success: function success(res) {
        // console.log(res)
        that.setData({
          shopAddress: res.name || res.address || '添加地图标记'
        });
      }
    });
    // todo 添加地址后展示地址
  },

  /**
   * 保存上传后的店铺图片
   */
  saveShopImg: function saveShopImg() {
    this.setData({
      showMain: true
    });
    if (this.data.upStatus === 1) {
      this.setData({
        addPicText: '重新上传图片'
      });
    }
  },

  /**
   * 上传门脸图
   */
  upFacePic: function upFacePic(e) {
    var that = this;
    wx.chooseImage({
      count: 1, // 默认9
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function success(res) {
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
        var tempFilePaths = res.tempFilePaths[0];
        // console.log(tempFilePaths)
        if (e.currentTarget.dataset.shop === 'faceImg') {
          that.setData({
            faceImg: tempFilePaths
          });
        } else if (e.currentTarget.dataset.shop === 'insideImg') {
          that.setData({
            insideImg: tempFilePaths
          });
        } else if (e.currentTarget.dataset.shop === 'IdFaceImg') {
          that.setData({
            IdFaceImg: tempFilePaths
          });
        } else if (e.currentTarget.dataset.shop === 'licenseImg') {
          that.setData({
            licenseImg: tempFilePaths
          });
        } else if (e.currentTarget.dataset.shop === 'restaurantLicenseImg') {
          that.setData({
            restaurantLicenseImg: tempFilePaths
          });
        }
        that.setData({
          upStatus: 1
        });
      }
    });
  },

  /**
   * 显示资质信息
   */
  nextStep: function nextStep() {
    if (this.data.shopAddress === '添加地图标记' || this.data.insideImg.indexOf('wxfile') === -1 || this.data.faceImg.indexOf('wxfile') === -1 || !this.data.addressDetail || !this.data.lxrName || !this.data.lxrPhone) {
      return wx.showModal({
        title: '抱歉',
        content: '请补全相关信息，再进行下一步操作',
        showCancel: false
      });
    }
    this.setData({
      hiddenMain: true
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad(e) {
    // TODO: onLoad
    var that = this;
    tcity.init(that);
    var cityData = that.data.cityData;
    var provinces = [];
    var citys = [];
    var countys = [];
    for (var i = 0; i < cityData.length; i++) {
      provinces.push(cityData[i].name);
    }
    // console.log('省份完成')
    for (var _i3 = 0; _i3 < cityData[0].sub.length; _i3++) {
      citys.push(cityData[0].sub[_i3].name);
    }
    // console.log('city完成')
    for (var _i4 = 0; _i4 < cityData[0].sub[0].sub.length; _i4++) {
      countys.push(cityData[0].sub[0].sub[_i4].name);
    }
    that.setData({
      'provinces': provinces,
      'citys': citys,
      'countys': countys,
      'province': cityData[0].name,
      'city': cityData[0].sub[0].name,
      'county': cityData[0].sub[0].sub[0].name
    });
    // console.log('初始化完成')
    console.log(e);
  },


  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function onReady() {
    // TODO: onReady

  },


  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function onShow() {
    // TODO: onShow
  },


  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function onHide() {
    // TODO: onHide
  },


  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function onUnload() {
    // TODO: onUnload
  },


  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function onPullDownRefresh() {
    // TODO: onPullDownRefresh
  }
});
//# sourceMappingURL=businessCooperation.js.map
