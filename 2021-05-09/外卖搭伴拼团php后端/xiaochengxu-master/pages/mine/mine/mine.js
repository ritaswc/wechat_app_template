
var app = getApp();

Page({
  data : {
    userInfo : {}
  },
  onLoad : function(options){
    // wx.openSetting({
    //   success: (res) => {
    //     if(!res.authSetting["scope.userInfo"]){
    //          console.log('auth error');
          
    //         return ;
    //     }
    //   }
    // })
    

  },
  onShow : function(){//console.log(33444)
    this.onLoad();
    this.loadUserInfo();
    if(!wx.getStorageSync('unionId') && wx.getStorageSync('auth') !== 'success'){
      app.applyNotice();
      return false;
    }
  },
  loadUserInfo : function(){
    var uid = wx.getStorageSync('uid');//console.log(uid)
    var page = this;
    wx.request({    
        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/User/getUserInfo',    
        data: {data:JSON.stringify({"uid":uid,"openid":null})},
        method: 'POST',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){  //console.log(res)  
          page.setData({userInfo:res.data.data})
        }    
    });  
  },
  mineGroup : function(e){
    wx.navigateTo({
      url: '/pages/mine/group/list/list'
    })
  },
  mineOrder : function(e){
    wx.navigateTo({
      url: '/pages/mine/order/list/list'
    })
  },
  mineCoupon  : function(e){
    wx.navigateTo({
      url: '/pages/mine/coupon/coupon'
    })
  },
  showAddress : function(e){
    // console.log(e.currentTarget.dataset.index)
    wx.navigateTo({
      url: '/pages/mine/address/list/list'
    })
  },
  showFlow : function(e){
    wx.navigateTo({
      url: '/pages/mine/flow/flow'
    })
  },
  showService : function(e){
    wx.navigateTo({
      url: '/pages/mine/service/service'
    })
  }
})