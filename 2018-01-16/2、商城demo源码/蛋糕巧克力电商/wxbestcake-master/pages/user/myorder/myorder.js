// pages/user/myorder/myorder.js

var base = getApp();
var _list = [];
Page({
  data: {
    loaded: false,
    mylist: [],
    pagenum: 1
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    var _this = this;
    this.initData();
    wx.setNavigationBarTitle({
      title: '我的订单列表'
    })
  },
  initData: function () {
    this.getOrderList();  
  },

  getOrderList: function () {
    var _this = this;
    base.get({ c: "UserCenter", m: "GetOrderList", pageSize: 10, currentPage: _this.data.pagenum}, function (d) {
      var data = d.data;
      if (data.Status == "ok") {       
        var arr = data.Tag.obj;
        for (var i = 0; i < arr.length; i++) {
          _list.push({
            name: arr[i].ListCake[0].ProductName,
            totalprice: arr[i].TotalPrice ,//+ ".00",
            num: arr[i].ListCake.length,
            imgUrl: base.path.res + "/images/ksk/item/w_113/" + arr[i].ListCake[0].ProductName + ".jpg",
            oid: arr[i].OrderId
          })
        }
        _this.setData({
          loaded: true,
          mylist: _list
        })

      }
    })

  },
  toDetail: function (e) {
    var oid = e.currentTarget.dataset.poid;
    if (oid) {
      wx.navigateTo({
        url: "../../user/myorderdetals/myorderdetals?oid=" + oid
      })
    }
  },
  onReachBottom: function () {
    var _this = this;
    this.setData({
      "pagenum": this.data.pagenum + 1
    });
    this.getOrderList();
  }
})