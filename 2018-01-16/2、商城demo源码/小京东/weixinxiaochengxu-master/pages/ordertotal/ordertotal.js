//ordertotal.js
var util = require('../../utils/util.js')
var request = require('../../utils/https.js')
var uri = 'orderapi/orderlist'
Page({
  data: {
    navTab: ["全部订单", "待付款", "待收货"],
    currentNavtab: 0,
    pageNo: 1,
    hidden: false,
    list: [],
    newlist: [],
    tips: '' //无数据
  },
  onLoad: function (options) {
    this.setData({
      currentNavtab: options.id
    })
    //刷新数据
    this.getData();
  },
  //切换tab刷新数据
  switchTab: function (o) {
    var that = this;
    var idx = o.currentTarget.dataset.idx;
    if (idx !== that.data.currentNavtab) {
      that.setData({
        currentNavtab: idx,
        list: [], //数据源清空
        newlist: [],
        pageNo: 1
      })
      //刷新数据
      that.getData();
    }
  },
  getData: function () {
    var that = this;
    var status = "";
    var ordertype = that.data.currentNavtab;
    if (ordertype == 0) {
      status = "";
    } else if (ordertype == 1) {
      status = "10";
    } else if (ordertype == 2) {
      status = "30";
    }
    var pageNo = that.data.pageNo;
    var CuserInfo = wx.getStorageSync('CuserInfo');
    request.req(uri, {
      ordersn: '1',
      status: status,
      pageNo: pageNo,
    }, (err, res) => {
      console.log(res.data)
      if (res.data.result == 1) {
        if (!res.data) {   //无数据
          that.setData({hidden: true, tips: "没有数据~" })
        } else {
          that.setData({
            hidden: true,
            list: that.data.list.concat(res.data.data),
          })
          //处理数据
          var list = that.data.list;
          for (var i = 0; i < list.length; i++) {
            var item = list[i]  //状态状态
            if (item.orderState == 10) {   //待支付
              that.data.newlist.push({
                button: "去支付",
                state: "待支付",
                orderTotalPrice: item.orderTotalPrice, //总价
                goodsImage: item.orderGoodsList[0].goodsImage,//图片地址
                goodsName: item.orderGoodsList[0].goodsName,  //商品介绍介绍
                goodsNum: item.orderGoodsList[0].goodsNum  //商品数量
              });
            } else if (item.orderState == 30) {  //待收货
              that.data.newlist.push({
                button: "确认收货",
                state: "待收货",
                orderTotalPrice: item.orderTotalPrice, //总价
                goodsImage: item.orderGoodsList[0].goodsImage,//图片地址
                goodsName: item.orderGoodsList[0].goodsName,  //商品介绍介绍
                goodsNum: item.orderGoodsList[0].goodsNum  //商品数量
              });
            } else {
              that.data.newlist.push({
                button: "查看详情",
                state: "其他状态",
                orderTotalPrice: item.orderTotalPrice, //总价
                goodsImage: item.orderGoodsList[0].goodsImage,//图片地址
                goodsName: item.orderGoodsList[0].goodsName,  //商品介绍介绍
                goodsNum: item.orderGoodsList[0].goodsNum  //商品数量
              });
            }
          }
          console.log(that.data.newlist);
          that.setData({
            newlist: that.data.newlist
          })
        }
      }
    })
  },
  //点击到相应的页面
  orderbutton: function (options) {
    var type = options.currentTarget.dataset.button;
    console.log(type)
    if (type == "去支付") {
      
    } else if (type == "查看详情") {

    } else if (type == "确认收货") {

    }
  },

  //下滑加载更多
  lower: function () {
    console.log("下滑啦");
    var that = this;
    that.setData({ pageNo: that.data.pageNo + 1 })
    that.getData();
  },

})
