var app = getApp();
Page({
    data:{
        userInfo:{}
    },
    onLoad: function () {
        var that = this
        //调用应用实例的方法获取全局数据
        app.getUserInfo(function(userInfo){
            //更新数据
            that.setData({
                userInfo:userInfo
            })
        })
    },
    //跳转到编辑地址等信息页面
    toAddress:function(){
        wx.navigateTo({
            url: '../address/address',
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
    //跳转到订单列表
    no_handle:function(e){
        wx.navigateTo({
            url: '../orderList/orderList?type=no_handle',
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
    handle:function(e){
        wx.navigateTo({
            url: '../orderList/orderList?type=handle',
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
    }
    
})