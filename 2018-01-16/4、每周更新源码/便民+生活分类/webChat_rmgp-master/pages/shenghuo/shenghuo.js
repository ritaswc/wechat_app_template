// pages/shenghuo/shenghuo.js
const shenghuoiconList = require('../../data/local-data')
const shenghuoImageList1 = require('../../data/local-data')
const shenghuoImageList2 = require('../../data/local-data')

const config = require('../../config')
var util = require('../../utils/util.js')

// 引入SDK核心类 - 腾讯LBS服务（微信小程序原生LBS能力的最佳拍档）
var QQMapWX = require('../../lbs/qqmap-wx-jssdk.js');
var qqmapsdk;

let TENCENT_KEY = "AJPBZ-S6MRU-NFIVK-4BH5A-IZA57-OKB24"

var longt = ""
var lati = ""

Page({
  data: {
    localCtiyName: "定位中...",  /** 当前定位城市 */
    list: [] /** 便民首页优选推荐店铺列表 */
  },

  /** 点击 */
  tapGridCell: function (event) {
    let index = event.currentTarget.dataset.iconId
    console.log(index)

    switch (index) {
      case 0: //领券中心
        wx.navigateTo({
          url: 'ValuePreference/ValuePreference',
        })
        break;
      case 1: //吃喝玩乐
        wx.navigateTo({
          url: 'GoodLife/GoodLife',
        })
        break;
      case 2: //精选商品
        wx.navigateTo({
          url: 'SelectedGoods/SelectedGoods',
        })
        break;
      case 3: //分类服务
        wx.navigateTo({
          url: 'AllServiceclassify/AllServiceclassify'
        })
        break;

    }


  },

  tapimageList1Cell: function (event) {
    switch (event.currentTarget.dataset.iconId) {
      case 0:
        //天天有奖
        wx.navigateTo({
          url: 'ttPrize/ttPrize',
        })
        break;
      case 1:
        wx.navigateTo({
          url: 'miaosha/miaosha',
        })
        break;
      case 2:
        //一元夺宝
        wx.navigateTo({
          url: 'OnePrize/OnePrize',
        })
        break;
      default:
        break;
    }

    console.log(event.currentTarget.dataset.iconId)
  },

  tapimageList2Cell: function (event) {
    console.log(event.currentTarget.dataset.iconId)
  },

  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数

    var that = this;

    console.log(shenghuoiconList, shenghuoImageList1, shenghuoImageList2)

    that.setData(shenghuoiconList, shenghuoImageList1, shenghuoImageList2)

    /*******************定位***********************/
    // 实例化API核心类
    qqmapsdk = new QQMapWX({
      key: TENCENT_KEY
    });
    /** 获取定位 */
    wx.showLoading({ title: '加载中...', })
    util.getLocation((successRes, failRes) => {
      wx.hideLoading()
      console.log(successRes)
      console.log(failRes)

      //赋值
      longt = successRes.longitude
      lati = successRes.latitude

      // 调用接口-逆地址解析
      qqmapsdk.reverseGeocoder({
        location: {
          latitude: successRes.latitude,
          longitude: successRes.longitude
        },
        coord_type: 1,//输入的locations的坐标类型，可选值为[1,6]之间的整数，每个数字代表的类型说明： 1 GPS坐标 2 sogou经纬度 3 baidu经纬度 4 mapbar经纬度 5 [默认]腾讯、google、高德坐标 6 sogou墨卡托
        success: function (res) {
          console.log(res);

          var city = res.result.address_component.city;
          that.setData({ localCtiyName: city });
          console.log(city);

          /** 首次定位成功 -> 网络请求 */
          /** 请求-便民首页优选推荐店铺列表 */
          that.requestAdvertRecommendDoorList()
        },
        fail: function (res) {
          console.log(res);
        },
        complete: function (res) {
          console.log(res);
        }
      });
    })
  },
  /** 便民首页优选推荐店铺列表 */
  requestAdvertRecommendDoorList: function (e) {
    var that = this;
    let url = config.RecommendDoorListUrl

    var para = {
      "currentLongitude": longt,
      "currentLattitude": lati
    }

    wx.showLoading({ title: '加载中...' })

    util.RequestManager(url, para, function (res, fail) {

      wx.hideLoading()
      
      var tempAr = [];

      for (var i = 0; i < res.data.length; i++) {
        var model = res.data[i];
        model["doorImg"] = model["logo"];
        model["starAr"] = util.convertToStarsArray(model["star"])
        model["province"] = model["provinceName"];
        model["city"] = model["cityName"];
        model["country"] = model["districtName"];
        //model["maxDiscountPrice"] = model["maxDiscountPrice"];
        tempAr.push(model)
      }

      that.setData({ list: tempAr })

    })
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