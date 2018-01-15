var util = require('../../common/common.js');
let URLINDEX=util.prefix();
Page({
  data:{
    // 此为搜索的数据
    src:{
      img1:URLINDEX+"/jmj/new_active/index/leftear.png",
      img2:URLINDEX+"/jmj/new_active/index/rightear.png",
      img3:URLINDEX+"/jmj/new_active/index/flower.png",
      img4:URLINDEX+"/jmj/new_active/index/search.png"
    },
    inputValue:"",
    page:1,
    lodding:true,
    goodslist:[],
    articleList:[],
    // 状态
    showState:1,
    class1:"item active",
    class2:"item",
    showGoods:true,
    showArticle:true,
    //为空猫状态
    noMore_cat:URLINDEX+"/jmj/icon/nomore.png",
    // 回到顶部按钮
    scrollTop:100,
    showBotton:false,
    topImg:URLINDEX+"/jmj/icon/top-icon.png",
  },
  // 这里处理所有事件
  // 改变状态事件
  changestate:function(e){
    console.log(e);
    var key=e.currentTarget.dataset.state;
    this.setData({
      showState:key
    });
  },
  //此为搜索相关的函数
    inputfocus:function(e){
      console.log(e);
      wx.redirectTo({
      url: "../search/search"
      })
    },
    // 下拉刷新事件
    pullrefresh: function(e){
      var self=this;
      if(self.data.showGoods||self.data.showArticle){
        self.setData({
          lodding:false,
          showBotton:true
        });
        getSearchList(self);
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
      inputValue:options.word
    });
    wx.setNavigationBarTitle({
      title:options.word
    });
    getSearchList(that);
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
// 获取专辑的函数
function getSearchList(that){
    wx.request({
      url: 'http://m.jiumaojia.com/apic/search',
      data:{
          page:that.data.page,
          word:that.data.inputValue
      },
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
         that.setData({
          lodding:true,
          goodslist:that.data.goodslist.concat(res.data.goods),
          articleList:that.data.articleList.concat(res.data.article),
          page:++that.data.page
        })
        //判断获取的数据是否为空
        if(res.data.goods.length==0){
           that.setData({
              showGoods:false,
            }) 
        };
        if(res.data.article.length==0){
          that.setData({
            showArticle:false,
          })  
        };
      }
      
    }) 
}