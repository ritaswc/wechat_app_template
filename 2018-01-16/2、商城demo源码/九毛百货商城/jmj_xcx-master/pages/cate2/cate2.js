// pages/cate2/cate2.js
var util = require('../../common/common.js');
let URLINDEX=util.prefix();
Page({
  data:{
    cate2:[],
    noMore_cat:URLINDEX+"/jmj/icon/nomore.png",
    topImg:URLINDEX+"/jmj/icon/top-icon.png",
    scrollTop:100,
    showBotton:false,
    show:true
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
    getcate2(that);
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
// 获取二级子类目的函数
function getcate2(that){
    wx.request({
      url: 'http://m.jiumaojia.com/apic/category_child',
      data:{
        id:that.data.id
      },
      header:{
          'Content-Type': 'application/json'
      },
      success: function(res) {
          that.setData({
          cate2:that.data.cate2.concat(res.data),
          show:false
      })
      console.log(res.data);
      }
    }) 
}