//获取应用实例
var app = getApp()
let { chooseRole } = require('../../../components/guide/guide');
Page({
  data: {
    motto: 'Hello World',
    userInfo: {},
    dataArr:['1','2'],
    shopOpened:true,
    showContent:true
  },
  
  onLoad: function () {
    //  wx.redirectTo({
    //       url: `../../customer/index/index`,
    //       success: function(res){
    //         // success
    //         // wx.setStorageSync('role',role);
    //         // app.globalData.role = role;
    //       },
    //       fail: function() {
    //         // fail
    //       },
    //       complete: function() {
    //         // complete
    //       }
    //   })
  },
  // chooseRole:function(e) {
  //     const role = e.currentTarget.id; 
  //     if (role == 'merchant'){
  //         this.setData({
  //             needGuide:'no'  
  //         });
  //     } else {
  //         wx.navigateTo({
  //             url: `../../${role}/index/index`,
  //             success: function(res){
  //               // success
  //               wx.setStorageSync('role',role);
  //               // app.globalData.role = role;
  //             },
  //             fail: function() {
  //               // fail
  //             },
  //             complete: function() {
  //               // complete
  //             }
  //         })
  //     } 
  // },
  onShow:function(){
      console.log(12)
      //判断是否为回退切换角色
      const role = wx.getStorageSync('role');
      if (role=='customer'){
          wx.redirectTo({
            url: '../../common/guide/guide',
            success: function(res){
              // success
            },
            fail: function() {
              // fail
            },
            complete: function() {
              // complete
            }
          })
      } else {
        this.setData({
          showContent:true
        })
      }
  },
  onHide:function(){
      this.setData({
         showContent:false
      })
  },
  // 开张
  openShop:function(){
    this.setData({
      shopOpened:false
    })
  },
  // 店铺休息
  rest:function(){
    this.setData({
      shopOpened:true
    })
  },
  // 店铺打烊
  close:function(){
    this.setData({
      shopOpened:true
    })
  },

  // 编辑公告
  editNotice:function(){
    wx.navigateTo({
      url: '../edit-notice/edit-notice',
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },

  // 我的信息
  goMineControl:function(e){
      wx.navigateTo({
        url: '../mine/mine'
      })
  },
  // 服务管理
  goServiceControl:function(e){
      wx.navigateTo({
        url: '../prodsvr-control/prodsvr-control?title=服务管理'
      })
  },
  // 商品管理
  goProductControl:function(e){
      wx.navigateTo({
        url: '../prodsvr-control/prodsvr-control?title=商品管理'
      })
  },
  // 优惠券管理
  goDiscountControl:function(e){
      wx.navigateTo({
        url: '../discount-control/discount-control'
      })
  },


})
