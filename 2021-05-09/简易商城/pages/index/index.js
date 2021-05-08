//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    current_goods:"",
    history_goods_list:"",
    loading:true,
    noMore:false
  },
  onLoad: function () {
    var that = this;
    wx.setNavigationBarTitle({
      title: "特卖"
    })
    wx.request({
      url: 'https://shop.llzg.cn/weapp/goodslist.php?act=fri',
      data: {},
      method: 'POST',
      header: {'content-type': 'application/x-www-form-urlencoded'},
      success: function(data) {
        //console.log(data)
        that.setData({
          current_goods:data.data,
          myTitle:data.data.goods_name
        })
      }
    })
    wx.request({
      url: 'https://shop.llzg.cn/weapp/goodslist.php?act=list',
      data: {},
      method: 'POST',
      header: {'content-type': 'application/x-www-form-urlencoded'},
      success: function(data) {
        //console.log(data)
        that.setData({
          history_goods_list:data.data,
        })
      }
    })
    
  },
  onReachBottom:function(){
    var that = this;
    if(that.data.noMore == false){
      that.setData({
        loading:false
      });
      wx.request({
        url: 'https://shop.llzg.cn/weapp/goodslist.php?act=reload&goods_num='+that.data.history_goods_list.length,
        data: {},
        method: 'POST',
        header: {'content-type': 'application/x-www-form-urlencoded'},
        success: function(data) {
          if(data.data == 0){
            that.setData({
              noMore:true,
              loading:true
            })
          }else{
            setTimeout(function(){
              that.setData({
                loading:true
              });
              that.setData({
                history_goods_list:data.data
              })
            },1000)
          }  
        }
      })
    }
  },
  onShareAppMessage: function () {
    return {
      title:'邻里特供社，原产地直供优质农特产品',
      desc:'',
      path:'/pages/index/index'
    }
  },
  onShow:function(){
    wx.setNavigationBarTitle({
      title: "特卖"
    })
  }
})
