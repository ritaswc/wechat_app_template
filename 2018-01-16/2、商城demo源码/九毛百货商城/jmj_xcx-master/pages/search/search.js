// pages/search/search.js
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
    placeholder:"元旦好礼等你来搜",
    inputValue:"",
    word:[]
  },
  // 表单事件
  formSubmit:function(e){
     wx.redirectTo({
      url: "../searchlist/searchlist?word="+this.data.inputValue
    })
  },
  inputfocus:function(e){
  },
  inputblur:function(e){
  },
  bindInput:function(e){
    this.setData({
        inputValue:e.detail.value
      })
  },
  searchCancle:function(e){
    wx.navigateBack()
  },
  onLoad:function(options){
    var that=this;
    if(!wx.getStorageSync('searchData')){
      getSearchWords(that)
    }else{
      var getSearch = wx.getStorageSync('searchData');
      this.setData({
        word:getSearch,
        inputValue:''
      })
    }
  }
})
// 获取专辑的函数
function getSearchWords(that){
    wx.request({
      url: 'http://m.jiumaojia.com/apic/search_words',
      header:{
          'Content-Type': 'application/json'
      },
      success: function(res) {
          wx.setStorageSync('searchData',res.data);
          that.setData({
          word:that.data.word.concat(res.data),
      })
      }
    }) 
}