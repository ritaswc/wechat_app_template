var app = getApp()
Page({
  data:{
     userInfo: {},
     mine_list:[ 
          {
            "pic_url": "/images/icons/iocn_home_01.png",
            "title":"我的订单",
          },
          {
            "pic_url": "/images/icons/iocn_home_02.png",
            "title":"优惠券",
          },
          {
            "pic_url": "/images/icons/iocn_home_03.png",
            "title":"收货地址",
          },
          {
            "pic_url": "/images/icons/iocn_home_04.png",
            "title":"客服电话",
          },
          {
            "pic_url": "/images/icons/iocn_home_04.png",
            "title":"提货券",
          },
          {
            "pic_url": "/images/icons/iocn_home_04.png",
            "title":"修改密码",
          }
        ],
    item: {
      signinHidden:false,
      userlocal:{
       nickName:'',
       nickPwd:''
     },
    }
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
   
  },
  modalconfirm:function(){
    wx.setStorageSync('username', this.data.item.userlocal.nickName);
    wx.setStorageSync('password', this.data.item.userlocal.nickPwd);
    this.setData({
      'item.signinHidden':true
    })
  },
  modalcancel:function(){
     var that = this;
      wx.login({
        success: function () {
          wx.getUserInfo({
            success: function (res) {
             
               that.setData({
        userInfo:res.userInfo
      })
            }
          })
        }
      })
     
   
    this.onShow();
     this.setData({
      'item.signinHidden':true
    })
  },
  quit:function(){
    this.setData({
        userInfo:'',
        'item.signinHidden':false
      })
  },
  saveusername:function(event){
    this.setData({
      'item.userlocal.nickName': event.detail.value
    });
  },
  saveuserpwd:function(event){
    this.setData({
      'item.userlocal.nickPwd': event.detail.value
    });
  },
  onReady:function(){
   
    // 页面渲染完成
  },
  onShow:function(){
    if(this.data.userInfo==''){
      this.setData({
      'item.signinHidden':false
      })
    }

  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
})