//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
  List1:"",
  List2:"",
  List3:"",
  navIndex:0,
  toView: 'red',
  scrollTop: 500
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
    console.log('onLoad')
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    })
  },
  swiperChange:function(event){
      this.setData({
        navIndex:event.detail.current
      })
  },
  changeNav:function(event){
      this.setData({
        navIndex:event.target.dataset.index
      })
  },
  onLoad(){
    this.fetchList();
  },
  fetchList(){
     var _this = this;
     wx.showLoading({mask:true,title:"努力加载..."});
    //   wx.showModal({
    //       title: '提示',
    //       content: '这是一个模态弹窗',
    //       confirmColor:"pink",
    //       success: function(res) {
    //         if (res.confirm) {
    //           console.log('用户点击确定')
    //         } else if (res.cancel) {
    //           console.log('用户点击取消')
    //         }
    //       }
    //  });
     
      wx.request({
        url: app.globalData.globalUrl + '/api/list',
        data: {},
        method: 'GET', 
        success: function(res){
          setTimeout(function(){
              wx.hideLoading()
          },1000);
          _this.setData({
              List1:res.data.List1,
              List2:res.data.List2,
              List3:res.data.List3
            } 
          );
          wx.setStorage({
            key: 'List1',
            data: res.data.List1
          });
          
        }
     });
   },
   refresh:function(e){
    console.log(1)
   },
 
})
