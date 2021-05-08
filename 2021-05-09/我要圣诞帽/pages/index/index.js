// pages/index/index.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    bgPic:null,
    picChoosed:false
  },

  assignPicChoosed() {
    if (this.data.bgPic) {
      this.setData({
        picChoosed: true
      })
    } else {
      this.setData({
        picChoosed: false
      })
    }
  },
  getAvatar() {
    if (app.globalData.userInfo) {
      this.setData({
        bgPic: app.globalData.userInfo.avatarUrl,
      });
      this.assignPicChoosed();
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo;
          this.setData({
            userInfo: res.userInfo,
            bgPic: res.userInfo.avatarUrl
          });
          this.assignPicChoosed();
        }
      })
    }
  },
  chooseImage(from){
    wx.chooseImage({
      count: 1,
      sizeType: ["original", "compressed"],
      sourceType: [from.target.dataset.way],
      success:(res)=> {
        var tempFilePaths = res.tempFilePaths;
        this.setData({
          bgPic:res.tempFilePaths[0]
        });
        this.assignPicChoosed();
      },
      fail: (res)=>{
        this.assignPicChoosed();
        },
      complete: (res)=>{
        this.assignPicChoosed();
        },
    })
  },
  nextPage(){
      app.globalData.bgPic=this.data.bgPic;
      wx.navigateTo({
        url: '../imageeditor/imageeditor',
      })
  }
})