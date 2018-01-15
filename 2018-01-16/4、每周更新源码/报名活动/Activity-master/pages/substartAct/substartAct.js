// pages/substartAct/substartAct.js

const date = new Date()
const years = []
const months = []
const days = []

for (let i = 1990; i <= date.getFullYear(); i++) {
  years.push(i)
}

for (let i = 1 ; i <= 12; i++) {
  months.push(i)
}

for (let i = 1 ; i <= 31; i++) {
  days.push(i)
}

Page({
  data:{
    ac_name:true,
    ac_time:false,
    ac_address:false,
    ac_duration:false,
    ac_detail:false,
    years: years,
    year: date.getFullYear(),
    months: months,
    month: 2,
    days: days,
    day: 2,
    year: date.getFullYear(),
    value: [9999, 1, 1],
    array: ['美国', '中国', '巴西', '日本'],
    location:0,
    startDate: '2016-09-01',
    endDate:'2016-09-01',
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    let theam = options.theam;
    switch(theam){
      case "ac_name":
        this.setData({
          ac_name:true,
          ac_time:false,
          ac_address:false,
          ac_duration:false,
          ac_detail:false
        });
        wx.setNavigationBarTitle({
          title: '活动名称'
        });
        break;
      case "ac_time":
        this.setData({
          ac_name:false,
          ac_time:true,
          ac_address:false,
          ac_duration:false,
          ac_detail:false
        });
        wx.setNavigationBarTitle({
          title: '时间'
        });
        break;
      case "ac_address":
        this.setData({
          ac_name:false,
          ac_time:false,
          ac_address:true,
          ac_duration:false,
          ac_detail:false
        });
        wx.setNavigationBarTitle({
          title: '地点'
        });
        break;
      case "ac_duration":
        this.setData({
          ac_name:false,
          ac_time:false,
          ac_address:false,
          ac_duration:true,
          ac_detail:false
        });
        wx.setNavigationBarTitle({
          title: '有效期'
        });
        break;
      case "ac_detail":
        this.setData({
          ac_name:false,
          ac_time:false,
          ac_address:false,
          ac_duration:false,
          ac_detail:true
        });
        wx.setNavigationBarTitle({
          title: '详情'
        });
        break;
    }
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  showAddress:function(e){
    this.setData({
      location: e.detail.value
    });
  },
  bindStartDateChange: function(e) {
    this.setData({
      startDate: e.detail.value
    })
  },
  bindEndDateChange: function(e) {
    this.setData({
      endDate: e.detail.value
    })
  },
})