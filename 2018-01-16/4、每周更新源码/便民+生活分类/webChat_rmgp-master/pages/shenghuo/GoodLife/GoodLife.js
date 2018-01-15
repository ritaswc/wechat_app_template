// pages/shenghuo/GoodLife/GoodLife.js

const config = require('../../../config')
var util = require('../../../utils/util.js')
// var locationManager = require('../../../utils/locationManager.js')

// 引入SDK核心类 - 腾讯LBS服务（微信小程序原生LBS能力的最佳拍档）
var QQMapWX = require('../../../lbs/qqmap-wx-jssdk.js');
var qqmapsdk;

let TENCENT_KEY = "AJPBZ-S6MRU-NFIVK-4BH5A-IZA57-OKB24"

var longt = ""
var lati = ""

var pageNo = 0;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    advertCaroucelsAr: [], /** 轮播数据 */
    ChildCateAr: [], /** 分类数据 */
    LifeSearchList: [], /** 列表数据 */
    localCtiyName: "定位中...",  /** 当前定位城市 */

    priceAndDistance: [],
    quancheng: [],
    priceopen: false,
    quanchengopen: false,
    priceshow: false,
    quanchengshow: false,
    priceSelectedName: "离我最近",
    quanchengSelectedName: "全城",
    isfull: false,
  },

  //点击GridCell
  tapGridCell: function (e) {
    var CategoryId = e.currentTarget.dataset.categoryId;
    var url = 'LifeSearchList/LifeSearchList?CategoryId=' + CategoryId + '&longt=' + longt + '&lati=' + lati + '&localCtiyName=' + this.data.localCtiyName;

    wx.navigateTo({
      url: url,
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    var that = this;

    that.setData({
      priceAndDistance: ['离我最近', '面额最高'],
      quancheng: ['1千米', '3千米', '5千米', '10千米', '全城']
    })

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

          /** 请求-广告轮播 */
          that.requestAdvertCaroucels()
          /** 请求-分类 */
          that.requestChildCate()
          /** 请求-列表信息 */
          that.loadNewData()
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
  requestAdvertCaroucels: function (e) {
    var that = this;
    let url = config.AdvertCaroucelsUrl

    var para = {
      "category": 8
    }

    wx.showLoading({ title: '加载中...' })

    util.RequestManager(url, para, function (res, fail) {

      wx.hideLoading()
      that.setData({ advertCaroucelsAr: res.data })

    })
  },
  requestChildCate: function (e) {
    var that = this;
    let url = config.ChildCateUrl

    var para = { "id": 13530 }

    wx.showLoading({ title: '加载中...' })

    util.RequestManager(url, para, function (res, fail) {

      var ChildCateAr = [];

      var count = res.data.length > 10 ? 10 : res.data.length;

      for (var i = 0; i < count; i++) {
        var model = res.data[i];

        if (i == 9) {
          model["name"] = "全部"
          model["imgUrl"] = "resource/serviceType/qb.png"
        }

        ChildCateAr.push(model)
      }

      wx.hideLoading()
      that.setData({ ChildCateAr: ChildCateAr })

    })

  },

  /** 下拉刷新 */
  loadNewData: function (e) {
    pageNo = 1;
    this.requestLifeSearchList();

  },
  /** 上拉加载 */
  loadNewData_NextPage: function (e) {
    pageNo += 1;
    this.requestLifeSearchList();
  },


  requestLifeSearchList: function (e) {
    var that = this;
    let url = config.LifeSearchListUrl

    var distanceStr = null

    if (that.data.quanchengSelectedName === "1千米") {
      distanceStr = "1000"
    } else if (that.data.quanchengSelectedName === "3千米") {
      distanceStr = "3000"
    }
    else if (that.data.quanchengSelectedName === "5千米") {
      distanceStr = "5000"
    } else if (that.data.quanchengSelectedName === "10千米") {
      distanceStr = "10000"
    } else if (that.data.quanchengSelectedName === "全城") {
      distanceStr = null
    }

    var para = {
      "pageSize": 20,
      "pageNum": pageNo,
      "sortType": ("离我最近" === that.data.priceSelectedName ? 0 : 1), //排序方式 0- 距离排序 1-面额最高排序
      "position": {
        "distance": distanceStr,
        "latitude": lati,
        "longitude": longt
      },
      "doorCateId": ["13530"],
      "city": that.data.localCtiyName,
      "country": null
    }

    wx.showLoading({ title: '加载中...' })

    util.RequestManager(url, para, function (res, fail) {

      wx.hideLoading()

      var tempAr = [];

      var doorIdAr = [];
      for (var i = 0; i < res.data.dataList.length; i++) {
        var model = res.data.dataList[i];

        doorIdAr.push(model["doorId"]); /** doorId集合 查询优惠价格时需要 */

        model["starAr"] = util.convertToStarsArray(model["star"])
        model["distance"] = util.convertToDistance(model["distance"])
        tempAr.push(model)
      }

      if (pageNo == 1) {
        //下拉刷新
        that.setData({ LifeSearchList: tempAr })
      } else {
        //上拉加载
        that.setData({ LifeSearchList: that.data.LifeSearchList.concat(tempAr) })
      }

      that.requesHighestDiscount(doorIdAr)
    })

  },
  requesHighestDiscount: function (doorIdAr) {

    var that = this;
    let url = config.HighestDiscountUrl

    var para = doorIdAr

    wx.showLoading({ title: '加载中...' })

    util.RequestManager(url, para, function (res, fail) {

      wx.hideLoading()


      var tempAr = that.data.LifeSearchList;

      for (var i = 0; i < tempAr.length; i++) {
        var model = tempAr[i];
        for (var j = 0; j < res.data.length; j++) {
          var discountModel = res.data[j];
          if (discountModel["doorId"] == model["doorId"]) {
            //赋值
            tempAr[i]["maxDiscountPrice"] = discountModel["maxDiscountPrice"];
            
          }

        }
      }

      that.setData({ LifeSearchList: tempAr })

    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    this.loadNewData_NextPage()
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})