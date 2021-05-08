// pages/trip/trip.js
Page({
  data: {
    page_index: 1,
    orders: [],
    class: '',
  },
  onLoad: function (options) {
    var vm = this;
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/history-order-list',
      data: {
        openid: wx.getStorageSync('openid'),
        page_index: 1,
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {

        var orders = res.data.data
        for (var i in orders) {

          if (orders[i].o_orderstate == '进行中' || orders[i].o_orderstate =='待评价') {

            orders[i].class = "color-h"
            orders[i].class1 = "jinxing"

          } if (orders[i].o_orderstate == '已完成'||orders[i].o_orderstate == '已完成(赔付)') {

            orders[i].class = "color-pp"
            orders[i].class1 = "wancheng"
          } if (orders[i].o_orderstate == '已取消') {
            orders[i].class = "color-s"
            orders[i].class1 = "quxiao"

          }
        }
        vm.setData({
          orders: orders

        })
      }
    });
  },
  gotoOrder: function (e) {
     var orderstate= e.currentTarget.dataset.orderstate

      if (orderstate !='已取消') {
        var o_id = e.currentTarget.dataset.o_id

        var ordercode = e.currentTarget.dataset.ordercode

        wx.redirectTo({
          url: './order/order?o_id=' + o_id + '&o_ordercode=' + ordercode+'&o_orderstate='+orderstate,

        })

      }


    }
  })