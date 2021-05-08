var _url = 'https://www.yuanweilh.com.cn/vtg/v3/api.do';
var _data = "cityName=北京&method=snackRecommended&page=1";

var ajax = require('../../../utils/util.ajax.js');
var app = getApp();

Page({
    data:{
        localSnack:null,
        scrollHeight: 0,
        hasMore:false,
        loadMore:false
    },
     onLoad:function() {
        var that = this;
        this.setData({hasMore:true});
        wx.getSystemInfo({
            success:function (res) {  
                console.log(res.windowHeight);
                that.setData({
                    hasMore:false,
                    scrollHeight:res.windowHeight
                });
            }
        })
    },
    onShow:function () { 
        // 在页面展示之后先获取一次数据
        var that = this;
        ajax.post(_url,'cityName='+app.globalData.curCity+'&method=snackRecommended&page=1',function (res) {  
           that.setData({
              localSnack:res.data.data,
              hasMore:false
            })
        });
    },
    scroll:function (event) {  
        console.log("scroll");
    },
    pullDownRefresh:function (event) {
        var that = this;
        this.onLoad();  
        this.setData({
            hasMore:true
        });

       ajax.post(_url,'cityName='+app.globalData.curCity+'&method=snackRecommended&page=1',function (res) {   
           that.setData({
               localSnack:res.data.data,
              hasMore:false
            })
        });
        // console.log("下拉刷新");
    },
    pullUpLoad:function (event) {  
        console.log("上拉加载。。。");

        var that = this;
        this.setData({
            loadMore:true
        });

        ajax.post(_url,'cityName='+app.globalData.curCity+'&method=snackRecommended&page=1',function (res) {  
            that.setData({
                 localSnack:res.data.data,
                loadMore:false
            });
        });
    }
})