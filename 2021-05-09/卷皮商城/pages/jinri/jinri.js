//jinri.js
var homeData = require('../../utils/homeData.js')
Page({
  data: {
    navTab: ["上新", "女装", "鞋包", "母婴"],
    currentNavtab: "0",
    imgUrls: [
      '../../images/lunbo1.png',
      '../../images/lunbo2.png'
    ],
    indicatorDots: false,
    autoplay: true,
    interval: 3000,
    duration: 1000,
    circular: true,
    actListData:[],
    actListData1:[],
    actListData2:[],
    actListData3:[],
    actListData4:[],
    actListData5:[],
    actListData6:[],
    actListData7:[],
    actListData7_length:0
  },
  onLoad: function () {
    console.log('onLoad')
    var that = this
    //调用应用实例的方法获取全局数据
    this.refresh();
    // 新增start

    console.log(homeData.formatHomeData1());
    this.setData({
      actListData: homeData.formatHomeData().module_ads.multi_block[0].data[0].child,
      actListData1: homeData.formatHomeData().module_ads.multi_block[1].data[0].child[0],
      actListData2: homeData.formatHomeData().module_ads.multi_block[2].data[0].child,
      actListData3: homeData.formatHomeData().module_ads.multi_block[3].data[0].child[0],
      actListData4: homeData.formatHomeData().module_ads.multi_block[4].data[0].child,
      actListData5: homeData.formatHomeData().module_ads.multi_block[5].data[0].child,
      actListData6: homeData.formatHomeData().module_ads.multi_block[6].data[0].child,
      actListData7: homeData.formatHomeData1().list,
    })
    console.log(this.data.actListData1)
    // 新增end
  },
  switchTab: function(e){
    this.setData({
      currentNavtab: e.currentTarget.dataset.idx
    });
  },

  bindItemTap: function() {
    wx.navigateTo({
      url: '../lastcrazy/lastcrazy'
    })
  },
  bindQueTap: function() {
    wx.navigateTo({
      url: '../lastcrazy/lastcrazy'
    })
  },
  upper: function () {
    wx.showNavigationBarLoading()
    this.refresh();
    console.log("upper");
    setTimeout(function(){wx.hideNavigationBarLoading();wx.stopPullDownRefresh();}, 2000);
  },
  lower: function (e) {
    wx.showNavigationBarLoading();
    var that = this;
    setTimeout(function(){wx.hideNavigationBarLoading();that.nextLoad();}, 1000);
  },

  //使用本地 listdata 数据实现刷新效果
  refresh: function(){
  },

  //使用本地 listdata 数据实现继续加载效果
  nextLoad: function(){
    var next = homeData.formatHomeData1();
    console.log(next);
    console.log("continueload");
    var next_data = next.list;
    this.setData({
      actListData7: this.data.actListData7.concat(next_data),
      actListData7_length: this.data.actListData7_length + next_data.length
    });
  }
});
