//schedule.js
//获取应用实例
const dateUtil = require('../../utils/date.js');
const util = require('../../utils/util.js');
const app = getApp()
Page({
  data: {
  	curDate: dateUtil.getToday(),
    scheduleList: []
  },
  
  onLoad: function () {
    // 获取所有车次信息
	let that = this;
	let globalData = app.globalData;
	let scheduleDate = globalData.date;
	let startCity = globalData.startCity;
	let endCity = globalData.endCity;
	let startStation = globalData.startStation;
	
	util.showWxLoading();
	wx.request({
	  url: 'https://localhost:3011/fr/schedule/list?startCity=' + startCity + '&endCity=' + endCity + '&scheduleDate=' + scheduleDate + '&stationName=' + startStation,
	  method: 'GET', 
	  success: function(res){
		if(res.data.statusCode == 20011011) {
			let data = res.data.data;
			let arr = [];
			data.forEach((item) => {
				let obj = {
					time: item.depart_time,
					startStation: item.start_station,
					endStation: item.end_station,
					benefitTicket: item.benefit_ticket,
					normalTicket: item.normal_ticket
				};
				arr.push(obj);
			}) 
			that.setData({
				scheduleList: arr
			})
		}
	  },
	  complete: function() {
		util.hideWxLoading();
	  }
	})
  },
  booking() {
  	wx.navigateTo({
			url: '../order/order',
		})
  }
})
