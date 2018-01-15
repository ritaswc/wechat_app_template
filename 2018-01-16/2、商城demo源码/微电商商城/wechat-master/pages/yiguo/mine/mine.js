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
    var that = this;
    app.getUserInfo(function(userInfo){
      //更新数据
      //console.log(userInfo);
     // nickName: "A花椒软件-小K", 
     //avatarUrl: "http://wx.qlogo.cn/mmhead/0vat6xq2cojlD8cF9acwmz6oB6IjBlkO9YHU3VFRdvg/132",
     // gender: 1, 
     //province: "Jiangxi", 
     //city: "Nanchang"
      that.setData({
        userInfo:userInfo
      })
    })
  },
  modalconfirm:function(){
    wx.setStorageSync('username', this.data.item.userlocal.nickName);
    wx.setStorageSync('password', this.data.item.userlocal.nickPwd);
    this.setData({
      'item.signinHidden':true
    })
  },
  modalcancel:function(){

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
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
})