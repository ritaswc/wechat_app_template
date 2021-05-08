

Page({
    data : {
        userInfo : {},
        pointGoods : {}
    }, 
    onLoad : function(){
        this.uid = wx.getStorageSync('uid');
        this.loadUserInfo();
        this.loadPointGoods();
    },
    loadUserInfo : function(){
        var page = this;
        wx.request({
            url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/User/getUserInfo',
            data: {data:JSON.stringify({"uid":this.uid,"openid":null})},
            method: 'POST',
            header: {
                'content-type': 'application/x-www-form-urlencoded'
            },
            success: function(res){console.log(res.data.data)
                page.setData({userInfo:res.data.data}) 
            }
        })
    },
    loadPointGoods : function(){
        var page = this;
        wx.request({
            url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/PointGoods/getPointGoods',
            success: function(res){console.log(res.data.data)
                page.setData({pointGoods:res.data.data})
            }
        })
    },
    gotoPay : function(){
        wx.showToast({
            title : '功能在开发中...敬请期待！'
        })
    }
})