// city-list.js
var app = getApp();
var ajax = require('../../../utils/util.ajax.js');
var _url = app.globalData.url;
var _data = 'lanKey=zh-cn&method=citySwitch&';
Page({
    data:{
        curCity:'',
        hotCitys:null,
        allCitys:null,
        toView:'A'
    },
    onLoad:function(query){
        var that = this;
        this.setData({
            curCity:query.city
        });
        ajax.post(_url,_data,function (res) {  
            res.data.data ? that.getFullData(res) : that.getPartData(res);
        })
    },
    getPartData : function (res) {
        this.setData({
            hotCitys : [
                {
                    "vtg_city_name": "北京",
                    "lyId": 53,
                    "guideFlag": 0,
                    "vtg_city_id": 3,
                    "vtg_city_code": "0100",
                    "longitude": 116.413624,
                    "latitude": 39.910837,
                    "lyPrefixLetter": "B"
                },
                {
                    "vtg_city_name": "南京",
                    "lyId": 224,
                    "guideFlag": 1,
                    "vtg_city_id": 1,
                    "vtg_city_code": "0250",
                    "longitude": 118.802962,
                    "latitude": 32.064792,
                    "lyPrefixLetter": "N"
                },
                {
                    "vtg_city_name": "西安",
                    "lyId": 317,
                    "guideFlag": 0,
                    "vtg_city_id": 5,
                    "vtg_city_code": "0290",
                    "longitude": 108.946167,
                    "latitude": 34.347652,
                    "lyPrefixLetter": "X"
                },
                {
                    "vtg_city_name": "杭州",
                    "lyId": 383,
                    "guideFlag": 0,
                    "vtg_city_id": 6,
                    "vtg_city_code": "0571",
                    "longitude": 120.162142,
                    "latitude": 30.278988,
                    "lyPrefixLetter": "H"
                },
                {
                    "vtg_city_name": "洛阳",
                    "lyId": 155,
                    "guideFlag": 0,
                    "vtg_city_id": 7,
                    "vtg_city_code": "0379",
                    "longitude": 112.460445,
                    "latitude": 34.624379,
                    "lyPrefixLetter": "L"
                },
                {
                    "vtg_city_name": "成都",
                    "lyId": 324,
                    "guideFlag": 0,
                    "vtg_city_id": 4,
                    "vtg_city_code": "0280",
                    "longitude": 104.07214,
                    "latitude": 30.578832,
                    "lyPrefixLetter": "C"
                },
                {
                    "vtg_city_name": "苏州",
                    "lyId": 226,
                    "guideFlag": 0,
                    "vtg_city_id": 10,
                    "vtg_city_code": "0512",
                    "longitude": 120.591427,
                    "latitude": 31.307026,
                    "lyPrefixLetter": "S"
                },
                {
                    "vtg_city_name": "厦门",
                    "lyId": 61,
                    "guideFlag": 0,
                    "vtg_city_id": 82,
                    "vtg_city_code": "0592",
                    "longitude": 118.095905,
                    "latitude": 24.485817,
                    "lyPrefixLetter": "X"
                },
                {
                    "vtg_city_name": "桂林",
                    "lyId": 102,
                    "guideFlag": 0,
                    "vtg_city_id": 22,
                    "vtg_city_code": "0773",
                    "longitude": 110.296591,
                    "latitude": 25.279857,
                    "lyPrefixLetter": "G"
                }
                ]
        });
    },
    getFullData : function (res) {
        var that = this;
        var obj = {};
        var all = res.data.data.allCitys;
        for(var i in all){
            if(!obj[all[i].lyPrefixLetter]){
                obj[all[i].lyPrefixLetter]=[];
            }
            obj[all[i].lyPrefixLetter].push(all[i]);
        }
        var arr = [];
        for(var j in obj){
            var tmp = {id:j,citys:obj[j]};
            arr.push(tmp);
        }
        // 将对象数组进行排序
        arr.sort(function (a,b) {  
            if(a.id>b.id){
                return 1;
            }else if(a.id<b.id){
                return -1;
            }else{
                return 0;
            }
        });
        that.setData({
            hotCitys:res.data.data.hotCitys,
            allCitys:arr
        });
    },
    chooseCity:function (event) {  
        // console.log(event.target.id);
        wx.switchTab({
            url:"/pages/index/index",
            success:function () {  
                app.globalData.curCity = event.target.id;
                // console.log(app.globalData.curCity);
            }
        })
    }
})