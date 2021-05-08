//index.js
//获取应用实例
var app = getApp();
//获取封装好的ajax
var ajax = require('../../utils/util.ajax.js');

var _url = app.globalData.url;

var amapFile = require('../../utils/amap-wx.js');
// 实例化地图API，获取当前定位信息
var myAmapFun = new amapFile.AMapWX({key:'fa5b1bf50930615d2303c0072aa33691'});

Page({
    data:{
        cityName:'',       
        hotList:null,
        navList:[
            {
                name:"名优特产",
                icon:"btn_specialty.png",
                page:"local-product/index"
            },
            {
                name:"名店小吃",
                icon:"btn_shop.png",
                page:"local-snack/local-snack"
            },
            {
                name:"行程管理",
                icon:"btn_journey.png",
                page:""
            },
            {
                name:"景点门票",
                icon:"btn_ticket.png",
                page:""
            }
        ]
    },
    onReady:function(){
        var that = this;
        // 调用高德地图接口，获取城市信息
        if(app.globalData.curCity){
             this.setData({cityName:app.globalData.curCity});
             this.getHot();
        }else{
            myAmapFun.getRegeo({
                success: function(data){
                    //获取当前城市信息
                    
                    console.log(data[0].regeocodeData.addressComponent);
                    var _msg = data[0].regeocodeData.addressComponent;
                    var _city = _msg.city[0]?_msg.city[0]:_msg.province;
                    app.globalData.curCity = _city;
                    that.setData({
                        cityName:_city
                    });

                    // console.log(app.globalData.curCity);

                    // 获取当前城市热门景点列表
                    that.getHot();
                },
                fail: function(info){
                    //失败回调
                    console.log(info)
                }
            });
        }
        
    },
    myLocation:function(){
        var that = this;
        wx.getLocation({
            // 返回可以用于wx.openLocation的经纬度
            type:'gcj02',
            success:function(res){
                var latitude = res.latitude;
                var longitude = res.longitude;
                wx.openLocation({
                    latitude:latitude,
                    longitude:longitude,
                    scale:1
                })
            }
        });
    },
    getHot:function () {
        var that = this;  
        ajax.post(_url,
                'cityName='+that.data.cityName+'&lanKey=zh-cn&provinceName=&method=scenicsOfCityNew&',
                function(res){
                    that.setData({
                        hotList:res.data.data.scenics
                    });
                })
    }
})
