//endCity.js
//获取应用实例
const app = getApp();
const util = require('../../utils/util.js');
Page({
    data: {
        chosenCity: '',
        chosenStation: '',
        chosenCityIndex: -1,
        startCity: '',
        endCityList: [],
        queryParam: '',
        endStation: {
            chosenIndex: -1,
            stationTitle: '选择下车地点',
            stationList: []
        },
        toast: {
            content: '请选择到达城市及下车地点',
            iconUrl: '../../../images/warning.png',
            showToast: false
        }
    },
    onLoad: function () {
        let that = this;
        let startCity = app.globalData.startCity;
        this.data.queryParam = startCity != '请选择出发地点' ? 'startCity=' + startCity : 'allStart=1';

        util.showWxLoading();
        // get all start point
        wx.request({
            url: 'https://loaclhost:8080/fr/city/list?' + that.data.queryParam,
            method: 'GET',
            success: function (res) {
                console.log(res.data);
                if (res.data.statusCode == 200110110) {
                    let data = res.data.data;
                    let arr = [];
                    data.forEach((item) => {
                        let obj = {};
                        obj.name = item.end_city;
                        obj.busCityId = item.bus_city_id;
                        arr.push(obj);
                    });
                    that.setData({
                        endCityList: arr
                    })
                }
            },
            complete: function() {
                util.hideWxLoading();
            }
        })
    },
    check: function () {
        // city and station have been selected
        let that = this;
        if (that.data.chosenCity == '' || that.data.chosenStation == '') {
            that.setData({
                'toast.showToast': true
            });
            setTimeout(() => {
                that.setData({
                    'toast.showToast': false
                })
            }, 1000);
            return;
        }

        wx.navigateBack({
            delta: 1 // 回退前 delta(默认为1) 页面
        })
    },
    chooseCity: function (event) {
        let that = this;
        let index = event.target.dataset.index;
        this.setData({
            chosenCity: that.data.endCityList[index].name,
            chosenCityIndex: index
        });
        app.globalData.endCity = that.data.chosenCity;

        util.showWxLoading();

        // 获取当前所选城市站点列表
        let arr = [];
        wx.request({
            url: 'https://localhost:3011/fr/station/list?station_type=1&end_city="' + that.data.chosenCity + '"',
            method: 'GET',
            success: function(res) {
                if(res.data.statusCode == 20011011) {
                    let data = res.data.data;
                    data.forEach((item) => {
                        let obj = {};
                        obj.name = item.station_name;
                        arr.push(obj);
                    });
                    that.setData({
                        'endStation.stationList': arr
                    })

                }
            },
            complete: function() {
                util.hideWxLoading();
            }
        })
    },
    chooseStation: function (event) {
        let index = event.target.dataset.index;
        this.setData({
            chosenStation: this.data.endStation.stationList[index].name,
            'endStation.chosenIndex': index
        });
        app.globalData.endStation = this.data.chosenStation;
    }
});
