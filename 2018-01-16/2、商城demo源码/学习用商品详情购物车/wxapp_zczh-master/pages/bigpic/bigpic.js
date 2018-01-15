var app = getApp();
Page({
    data:{
        images:[],
        goodUrl:'/m.php?g=api&m=wxshop&a=goods&goods_id=',
        indicatorDots: true,
        vertical: false,
        autoplay:false,
        interval: 4000,
        duration: 1200,
        current:0,
        serverSrc:app.globalData.serverUrl,
        windowW:"1px",
        windowH:"1px"
    },
    onLoad: function () {
        var that = this
        that.setData({
            current:app.globalData.pic_index
        })
        wx.request({
            url: app.globalData.serverUrl+that.data.goodUrl+app.globalData.requestId, //详情页数据
            header: {
                'content-type': 'application/json'
            },
            success: function(res) {
                console.log(res.data)
                that.setData({
                    images:res.data.data.main
                })
                console.log(that.data.images)   
            }
        })
        wx.getSystemInfo({
            success: function(res) {
                console.log(res.model)
                console.log(res.pixelRatio)
                console.log(res.windowWidth)
                console.log(res.windowHeight)
                console.log(res.language)
                console.log(res.version)
                that.setData({
                    windowW:res.windowWidth+"px",
                    windowH:res.windowHeight+"px"
                })
            }
        })
    }
})