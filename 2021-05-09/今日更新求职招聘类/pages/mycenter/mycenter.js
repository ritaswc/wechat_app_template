// pages/mycenter/mycenter.js
let app = getApp();
let myrequire = require('../../utils/wxrequire');
const url = require('../../utils/requireurl.js').url;
Page({
  data:{
    userInfo: {},
    favorited_num:'',
    avatar:'',
    user_name:'',
    showBading:true
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 2000000
    })
    let _that = this
    let user_ticket = wx.getStorageSync('user_ticket');
    let user_name = wx.getStorageSync('user_name');
    this.setData({
      userInfo:wx.getStorageSync('userInfo')
    })
    myrequire.requireMassage( '/user/status', user_ticket,function(res){
        // console.log( res )
        let status = res.data.status;
        let data = res.data.data || [];
        if(status == 1){
          
          let favorited_num = data.favorited_num //收藏的职位;
          let avatar = data.avatar//用户头像头像;
          let true_name = data.true_name //用户的姓名;
          let resume_status = data.resume_status //是否公开
            _that.setData({
                favorited_num:favorited_num,
                avatar:avatar,
                user_name:user_name
            })
            
            wx.hideToast()
        }else{
          wx.hideToast();
          let err = res.data.errMsg;
          wx.showModal({
            title: '失败',
            showCancel:false,
            content: err,
            success: function(res) {
            }
          })
        }
    },function(){
      console.log( '接口调用失败' )
    })
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    let is_binding = wx.getStorageSync('is_binding');
    let user_name = wx.getStorageSync('user_name');
    this.setData({
      user_name:user_name
    })
    if ( is_binding == 0 ){ //==用户没有绑定 显示绑定控件
        this.setData({
          showBading:true
        })
    }else{  //==用户已经绑定,隐藏控件
        this.setData({
          showBading:false
        })
    }
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  change_avatarUrl:function(){  //==============================修改用户头像  
      let _that = this;
      wx.chooseImage({
        count: 1,
        sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
        sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
        success: function (res) {
          var tempFilePaths = res.tempFilePaths
          
          let user_ticket = wx.getStorageSync('user_ticket');
          wx.uploadFile({
            url: url+'/resume/upload_avatar',
            filePath:tempFilePaths[0],
            name:'avatar',
             header: {
                'content-type' : 'multipart/form-data'
             },
             formData: {
               'user_ticket':user_ticket
             },
            success: function(res){
              console.log( res )
              // _that.setData({
              //   'userInfo.avatarUrl':tempFilePaths[0]
              // })
            },
            fail: function(res) {
              console.log( res )
            }
          })
        }
      })
  },
   onPullDownRefresh:function(){  //上拉刷新 在josn中开启;
      let _that = this
      let user_ticket = wx.getStorageSync('user_ticket');
      myrequire.requireMassage( '/user/status', user_ticket,function(res){
        // console.log( res )
        let status = res.data.status;
        let data = res.data.data || [];
        if(status == 1){
          wx.stopPullDownRefresh()
          let favorited_num = data.favorited_num //收藏的职位;
          // console.log( favorited_num )
            _that.setData({
                favorited_num:favorited_num,
            }) 
        }else{
          let err = res.data.errMsg;
          wx.showModal({
            title: '失败',
            showCancel:false,
            content: err,
            success: function(res) {
            }
          })
        }
    },function(){
      console.log( '接口调用失败' )
    })
  }
})