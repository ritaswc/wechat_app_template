// pages/brand/brand.js
var util = require('../../common/common.js');
let URLINDEX=util.prefix();
Page({
  data:{
    goodsList:[],
    lodding:true,
    page:1,
    nobanner:URLINDEX+"/jmj/article/brank_banner.jpg",
    img001:URLINDEX+"/jmj/brand_detail/shop01.png",
    // 开合
    showAll:URLINDEX+"/jmj/brand_detail/zhan.png",
    showHalf:URLINDEX+"/jmj/brand_detail/shou.png",
    showState:false,
    class1:"text hidecamp2",
    class2:"text",
    //为空猫状态
    noMore_cat:URLINDEX+"/jmj/icon/nomore.png",
    showcat:true,
    // 回到顶部按钮
    scrollTop:100,
    showBotton:false,
    topImg:URLINDEX+"/jmj/icon/top-icon.png",
  },
  // tap事件
  change:function(){
    this.setData({
          showState:!this.data.showState,
        });
  },
  // 下拉刷新事件
    pullrefresh: function(e){
      var self=this;
      if(self.data.showcat){
        self.setData({
          lodding:false,
          showBotton:true
        });
        getBand(self);
      }
    },
    scroll:function(e){
      //监听回到顶部按钮是否需要隐藏
      if(e.detail.scrollTop>10){
        this.setData({
          showBotton:true
        });
      }else{
        this.setData({
          showBotton:false
        });
      }
    },
    // 直达顶部
    toTop:function(){
      this.setData({
          scrollTop:0,
          showBotton:false
        });
    },
  onLoad:function(options){
    var that=this;
    that.setData({
      id:options.id
    });
    wx.setNavigationBarTitle({
      title:options.title
    });
    getBand(that)
  },
  //获取窗口的高度
   onShow: function( e ) {
    wx.getSystemInfo( {
      success: ( res ) => {
        this.setData( {
          windowHeight: res.windowHeight,
          windowWidth: res.windowWidth
        })
      }
    })
  }
})

function getBand(that){
    wx.request({
      url: 'http://m.jiumaojia.com/apic/brand?id=313&page=1',
      data:{
          page:that.data.page,
          id:that.data.id
      },
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
         that.setData({
          lodding:true,
          brand:res.data,
          goodsList:that.data.goodsList.concat(res.data.goods_list),
          page:++that.data.page
        })
        //判断获取的数据是否为空
        if(res.data.goods_list.length==0){
           that.setData({
              showcat:false,
            }) 
        };
      } 
    }) 
}