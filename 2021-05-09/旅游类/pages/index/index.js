import { View, star, getViewData } from "../../utils/util.js";
let app = getApp();
let cityName;
Page({
  data: {
    allCity: [],
    cityHot: [],
    viewHot: [],
    viewList: [],
    active: true,
    viewDetail: [],
    loading: true,
    animationData: {}
  },
  onLoad() {
    let _this = this;
    // 获取热门城市
    new View("http://70989421.appservice.open.weixin.qq.com/data/city.json", "get").send((res) => {
      let data = res.data.result;
      dealCityHot(data);
      this.getCity();
    })
    // 获取热门景区
    new View("http://70989421.appservice.open.weixin.qq.com/data/view.json", "get").send((res) => {
      let data = res.data.result;
      dealViewHot(data);
    })
    //  处理获取到的城市数据
    function dealCityHot(data) {
      // 获取前4个信息
      let cityHot = data.splice(0, 4);
      _this.setData({
        allCity: data,
        cityHot: cityHot
      });
    }
    // 处理获取到的景点数据
    function dealViewHot(data) {
      //获取前9个景区信息
      let viewHot_1 = data.splice(0, 3);
      let viewHot_2 = data.splice(0, 3);
      let viewHot_3 = data.splice(0, 3);
      // 数据结构满足[[],[],[]]结构，页面中for使用
      let arr = [];
      arr.push(viewHot_1);
      arr.push(viewHot_2);
      arr.push(viewHot_3);
      _this.setData({
        viewHot: arr
      })
    }
  },
  getCity() {
    let cityName;
    let _this = this;
    let cityId;
    // 允许获取位置
    wx.showModal({
      title: "地理位置",
      content: '允许 "旅游天地+" 访问您的位置吗？',
      cancelText: "不允许",
      confirmText: "允许",
      success(res) {
        // 允许
        if (res.confirm) {
          wx.chooseLocation({
            success: function (res) {
              // 获取城市名
              if (/省/.test(res.address)) {
                cityName = res.address.split("省")[0]
              } else {
                cityName = res.address.split("市")[0]
              }
              getViewData(_this.data.allCity, cityName, function (res) {

                _this.spliceTwoView(res)
              })
            }
          })
        } else {
          // 默认地址北京
          cityName = "北京";
          // 处理数据
          getViewData(_this.data.allCity, cityName, function (res) {
            _this.spliceTwoView(res)
          });
        }
      }
    });
  },
  // 首页加载2个景点数据
  spliceTwoView(res) {
    let resultArr = res.splice(0, 2)
    this.setData({
      viewList: resultArr,
      loading: false
    })
    this.enterAnimate();
    // 缓存获取到的景区数据，切换时候不在重新获取  
    if (this.data.active) {
      wx.setStorageSync('aroundViewList', resultArr)
    } else {
      wx.setStorageSync('countryViewList', resultArr)
    }
  },

  // 获取周边热门游
  getaroundView() {
    this.tabGetData(true, true, "aroundViewList", cityName)
  },
  // 获取国内热门游游
  getcountryView() {
    this.tabGetData(false, true, 'countryViewList', "北京")
  },

  tabGetData(active, loading, key, city) {
    let _this = this;
    this.leaveAnimate();
    this.setData({
      active: active,
      loading: loading
    })
    if (wx.getStorageSync(key)) {
      this.setData({
        viewList: wx.getStorageSync(key),
        loading: !loading
      });
      this.enterAnimate();
    } else {
      getViewData(_this.data.allCity, city, function (res) {
        _this.spliceTwoView(res)
      })
    }
  },
  // 进入景点列表
  enterViewList() {
    let allcity = JSON.stringify({
      allcity: this.data.allCity
    })
    wx.navigateTo({
      url: 'view-list/view-list?allcity=' + allcity + ''
    })
  },
  // 动画
  enterAnimate() {
    let animation = wx.createAnimation({
      duration: 1000,
      timingFunction: 'ease',
    })
    animation.opacity(1).step()
    this.setData({
      animationData: animation.export()
    })
  },
  leaveAnimate() {
    let animation = wx.createAnimation({
      duration: 100,
      timingFunction: 'ease',
    })
    animation.opacity(0).step()
    this.setData({
      animationData: animation.export()
    })
  },
  // 进入景点详情
  enterDetail(e) {
    let sid=e.currentTarget.dataset.id;
    wx.navigateTo({
      url: 'view-detail/view-detail?sid='+sid+''
    })
  }
})
