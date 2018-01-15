// pages/sitemap/sitemap.js
var util = require('../../common/common.js');
let URLINDEX=util.prefix();
Page({
  data:{
    src:{
      img1:URLINDEX+"/jmj/new_active/index/leftear.png",
      img2:URLINDEX+"/jmj/new_active/index/rightear.png",
      img3:URLINDEX+"/jmj/new_active/index/flower.png",
      img4:URLINDEX+"/jmj/new_active/index/search.png"
    },
    sitemap:[],
    choState:null
  },
  //点击事件
  //此为搜索相关的函数
    inputfocus:function(e){
      wx.navigateTo({
      url: "../search/search"
      })
    },
  showCate: function(e){
    var key=e.currentTarget.dataset.key;
    console.log(this.data.choState);
    this.setData({
          choState:key
          })
  },
  onLoad:function(options){
    var that=this;
      wx.request({
        url: 'http://m.jiumaojia.com/apic/category_top',
        header: {
            'Content-Type': 'application/json'
        },
        success: function(res) {
          res.data.map(function(item){
            item.bannerImg="http://m.jiumaojia.com/upload/category/article_img/12.png";
             item.img1=item.image.split(",")[0];
             item.img2=item.image.split(",")[1];
             item.class1="item";
             item.class2="item active";
          });
          that.setData({
          sitemap:res.data
          })
        }
      })
  }
})