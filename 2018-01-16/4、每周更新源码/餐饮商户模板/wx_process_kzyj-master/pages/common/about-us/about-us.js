var app = getApp()
Page({
  data: {
    
  },
  
  onLoad: function () {
    
    
  },
  changeRole:function(){
    console.log(1111,app.globalData)
    let url = '';
    let role = wx.getStorageSync('role');
    if (role == 'customer'){
        url = '../../merchant/index/index';
        role = 'merchant';
    } else {
        url = '../../customer/index/index';
        role = 'customer';
    }
    //存储角色状态 
    // app.globalData.role = role;
    wx.setStorageSync('role',role);

    wx.navigateBack({
      delta: 5
    })
  }
})
