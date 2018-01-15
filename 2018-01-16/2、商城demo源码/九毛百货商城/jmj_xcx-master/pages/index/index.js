//index.js
//获取应用实例
var app = getApp();
var util = require('../../common/common.js');
console.log(util);
let URLINDEX=util.prefix();
Page({
  data: {
    userInfo: {},
    // 此为搜索的数据
    src:{
      img1:URLINDEX+"/jmj/new_active/index/leftear.png",
      img2:URLINDEX+"/jmj/new_active/index/rightear.png",
      img3:URLINDEX+"/jmj/new_active/index/flower.png",
      img4:URLINDEX+"/jmj/new_active/index/search.png"
    },
    //此为顶部导航的数据
    navState:1,
    class1:"headItem",
    class2:"headItem active",
    headnav:[
      {
        name:'推荐',
        img:URLINDEX+"/jmj/icon/page1_bg1.png",
        url:'../index/index?id=0'
      },{
        name:'药妆',
        img:URLINDEX+"/jmj/icon/page1_bg2.png",
        url:'../prolist/prolist?id=1'
      },{
        name:'个户',
        img:URLINDEX+"/jmj/icon/page1_bg3.png",
        url:'../prolist/prolist?id=2'
      },{
        name:'宠物',
        img:URLINDEX+"/jmj/icon/page1_bg4.png",
        url:'../prolist/prolist?id=3'
      },{
        name:'健康',
        img:URLINDEX+"/jmj/icon/page1_bg5.png",
        url:'../prolist/prolist?id=4'
      },{
        name:'零食',
        img:URLINDEX+"/jmj/icon/page1_bg6.png",
        url:'../prolist/prolist?id=5'
      }
    ],
    banner:[],
    cate:[],
    page:1,
    // 首页专辑列表的数据
    fimgimg:URLINDEX+"/jmj/icon/like-ed.png",
    ufimgimg:URLINDEX+"/jmj/icon/like.png",
    imgLook:URLINDEX+"/jmj/icon/read.png",
    cateList:[],
    lodding:true,
  },
  //此为搜索相关的函数
    inputfocus:function(e){
      console.log(e);
      wx.navigateTo({
      url: "../search/search"
      })
    },
  //头部nav点击事件函数
  changeNavState: function(e){
    console.log(e);
    var item=e.currentTarget.dataset.item;
    var key=e.currentTarget.dataset.key;
    console.log(key);
    wx.navigateTo({
      url: item.url
    })
  },
  //首页的收藏事件
  favorite:function(e){
     var index=e.currentTarget.dataset.index;
     var self=this;
    fav(self,index);
  },
  pullrefresh:function(e){
    var self=this;
    self.setData({
        lodding:false
      })
    getIndexgetIndexList(self);
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
  },
  onLoad: function () {
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    })
    console.log(this.data.userInfo)
    //获取轮播图的banner
    wx.request({
      url: 'http://m.jiumaojia.com/apic/banner_list',
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
        console.log(res.data)
         that.setData({
        banner:res.data.banner
      })
      }
    })
    //获取特别分类接口
    wx.request({
      url: 'http://m.jiumaojia.com/apic/article_category_list',
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
        console.log(res.data)
         that.setData({
        cate:res.data.ac
      })
      }
    })
    //获取初始专辑列表
     getIndexgetIndexList(that);
  }
})
// 获取专辑的函数
function getIndexgetIndexList(that){
    wx.request({
      url: 'http://m.jiumaojia.com/apic/article_list',
      data:{
          page:that.data.page,
          token:util.code()
      },
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
         that.setData({
          lodding:true,
          cateList:that.data.cateList.concat(res.data),
          page:++that.data.page
      })
      }
    }) 
}
function fav(self,index){
  wx.request({
    url: util.pre()+'/apic/favorite_article_add',
    data:{
      id:self.data.cateList[index].id,
      random:Math.random(),
      token:util.code()
    },
    header: {
      'Content-Type': 'application/json'
    },
    success: function(res) {
      if(res.data.message=="收藏成功"){
        self.data.cateList[index].is_favorite=1;
        self.data.cateList[index].favorite_num=parseInt(self.data.cateList[index].favorite_num)+1;
      }
      if(res.data.message==""){
        self.data.cateList[index].is_favorite=0;
        self.data.cateList[index].favorite_num=parseInt(self.data.cateList[index].favorite_num)-1;
      }
      self.setData({
        cateList:self.data.cateList
      })
    }
  })
}
