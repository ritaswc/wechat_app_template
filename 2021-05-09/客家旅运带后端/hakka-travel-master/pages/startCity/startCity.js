//startCity.js
//获取应用实例
const app = getApp();
const util = require('../../utils/util.js');
Page({
    data: {
        chosenCity: '',
        chosenStation: '',
        chosenCityIndex: -1,
        endCity: '',
        startCityList: [],
        queryParam: '',
        startStation: {
            chosenIndex: -1,
            stationTitle: '选择上车地点',
            stationList: []
        },
        toast: {
            content: '请选择出发城市及上车地点',
            iconUrl: '../../../images/warning.png',
            showToast: false
        }
    },
    onLoad() {
        let that = this;

        let endCity = app.globalData.endCity;
        this.data.queryParam = endCity != '请选择到达地点' ? 'endCity=' + endCity : 'allStart=1';

        util.showWxLoading();
        // get all start point
        wx.request({
            url: 'https://localhost:3011/fr/city/list?' + this.data.queryParam,
            method: 'GET',
            success: function (res) {
                if (res.data.statusCode == 20011011) {
                    let data = res.data.data;
                    let arr = [];
                    data.forEach((item) => {
                        let obj = {};
                        obj.name = item.start_city;
                        obj.busCityId = item.bus_city_id;
                        arr.push(obj);
                    });
                    that.setData({
                        startCityList: arr
                    })
                }
            },
            complete: function() {
                util.hideWxLoading();
            }
        })
    },
    check() {
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
            delta: 1  // 回退前 delta(默认为1) 页面
        })
    },
    chooseCity(event) {
        console.log(event);
        let that = this;
        let index = event.target.dataset.index;
        let busCityId = event.target.dataset.busCityId;
        that.setData({
            chosenCity: that.data.startCityList[index].name,
            chosenCityIndex: index
        });
        // 通过app.global跨页面传数据,替换用storage的方式
        app.globalData.startCity = this.data.chosenCity;

        util.showWxLoading();
        // 获取当前所选城市站点列表
        let arr = [];
        wx.request({
            url: 'https://localhost:3011/fr/station/list?stationType=0&busCityId=' + busCityId,
            method: 'GET',
            success: function (res) {
                if (res.data.statusCode == 20011011) {
                    let data = res.data.data;
                    data.forEach((item) => {
                        let obj = {};
                        obj.name = item.station_name;
                        arr.push(obj);
                    });
                    that.setData({
                        'startStation.stationList': arr
                    })
                }
            },
            complete: function() {
                util.hideWxLoading();
            }
        })
    },
    chooseStation(event) {
        let index = event.target.dataset.index;
        this.setData({
            chosenStation: this.data.startStation.stationList[index].name,
            'startStation.chosenIndex': index
        });
        app.globalData.startStation = this.data.chosenStation;
    }
});
  