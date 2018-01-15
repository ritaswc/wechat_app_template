var util = require('../../common/common.js');
let URLINDEX=util.prefix();
Page({
  data:{
    // 搜索的数据
    src:{
      img1:URLINDEX+"/jmj/new_active/index/leftear.png",
      img2:URLINDEX+"/jmj/new_active/index/rightear.png",
      img3:URLINDEX+"/jmj/new_active/index/flower.png",
      img4:URLINDEX+"/jmj/new_active/index/search.png"
    },
    //顶部导航的数据
    navState:'',
    class1:"headItem",
    class2:"headItem active",
    headnav:[
      {
        name:'推荐',
        img:URLINDEX+"/jmj/icon/page1_bg1.png",
        url:'../index/index'
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
    prolist:{},
    // 最新品logo
    imgNew:URLINDEX+"/jmj/product/new.png",
    imgMore:URLINDEX+"/jmj/icon/more.png",
    imgHot:URLINDEX+"/jmj/product/hot.png",
    //随机品牌
    article_list1:{},
    imgLook:URLINDEX+"/jmj/icon/read.png",
    article_list2:{}
  },
  onLoad:function(options){
    var that=this;
    this.setData({
      navState:options.id
    })
    // 获取proListproList数据
    wx.request({
      url: 'http://m.jiumaojia.com/apic/pro_list',
       data:{
          tid:that.data.navState
      },
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
        console.log(res.data)
         that.setData({
        prolist:res.data.data,
        article_list1:res.data.data.article_list[0],
        article_list2:res.data.data.article_list[1]
      })
      }
    })
  },
 //此为搜索相关的函数
    inputfocus:function(e){
      console.log(e);
      wx.navigateTo({
      url: "../search/search"
      })
    },
  //头部nav点击事件函数
  changeNavState: function(e) {
    var item=e.currentTarget.dataset.item;
    var key=e.currentTarget.dataset.key;
    console.log(key);
    if(key==0){
      wx.navigateBack()
    }
    wx.redirectTo({
      url: item.url
    });
  },
})