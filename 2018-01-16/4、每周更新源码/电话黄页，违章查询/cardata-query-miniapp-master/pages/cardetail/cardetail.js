// pages/cardetail/cardetail.js
Page({
  data:{},
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    var carData = wx.getStorageSync('allCarData');
    this.setData({
      carData: carData
    });
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  deleteCar: function() {
    wx.showActionSheet({
      itemList: ['确认删除此条车辆信息'],
      itemColor: '#f53535',
      success: function(res){
        if( res.tapIndex == 0 ) {
          wx.setStorageSync('allCarData', '');
          wx.showToast({
            title: '删除成功!',
            icon: 'success'
          });
          setTimeout(function(){
            wx.navigateBack({
              delta: 1
            });
          }, 1500);
        }
      }
    });
    
  }
})