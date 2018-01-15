var app = getApp();
Page({
    data:{
      order:[],
      del_item:null,
      serverSrc:app.globalData.serverUrl
    },
    //去支付
    pay:function(e){
      console.log("去支付。。。")
      var id=e.currentTarget.id;
      var order_id=e.currentTarget.dataset.order_id;
      console.log(id)
      app.globalData.requestId=id;//商品ID
      app.globalData.order_id=order_id;//全局订单ID
      app.globalData.order_k=true;//改为：是处理未完成的订单
      console.log(order_id);
      //跳转到订单页
        wx.navigateTo({
          url: '../order/order',
          success: function(res){
            // success
          },
          fail: function() {
            // fail
          },
          complete: function() {
            // complete
          }
        })
    },
    onLoad:function(orderType){
      console.log(orderType)
      var order_Type=orderType
      var that=this;
      //发送请求获取订单
      wx.request({
        url: 'https://www.520hhy.cn/m.php?g=api&m=wxshop&a=my_order&limit=100&',
        data: {
          sess_id:wx.getStorageSync(
                 'sess_id'
                ),
           type:orderType.type   
        },
        method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        // header: {}, // 设置请求的 header
        success: function(res){
          // success
          console.log(res)
          that.setData({
            order:res.data.data
          })
          console.log(that.data.order)
        },
        fail: function() {
          // fail
        },
        complete: function() {
          // complete
        }
      })
    },
    //取消订单
    del:function(e){
      var that=this;
      var id=e.currentTarget.id;
        wx.request({
          url: 'https://www.520hhy.cn/m.php?g=api&m=wxshop&a=cancel_order&sess_id='+wx.getStorageSync('sess_id'),
          data: {order_id:id},
          method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
          header: {"content-type":'application/x-www-form-urlencoded'}, // 设置请求的 header
          success: function(res){
            //取消成功
            wx.showToast({
              title: '已订单取消',
              icon: 'success',
              duration: 2000
            })
            console.log(res)
              //发送请求获取新的订单
              wx.request({
                url: 'https://www.520hhy.cn/m.php?g=api&m=wxshop&a=my_order&limit=10&',
                data: {
                  sess_id:wx.getStorageSync(
                        'sess_id'
                        ),
                  type:"no_handle"     
                },
                method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
                // header: {}, // 设置请求的 header
                success: function(res){
                  // success
                  console.log(res)
                  that.setData({
                    order:res.data.data
                  })
                  console.log(that.data.order)
                }
            })
          },
          fail: function() {
            // fail
          },
          complete: function() {
            // complete
          }
        })
    },
})