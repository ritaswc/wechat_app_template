// pages/mine/login/login.js
Page({
  data : {
    phone : ''
  },
  savePhone : function(e){
    this.setData({phone:e.detail.value})
  },
  getCode : function(){
    var page = this;
    wx.request({
      url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Login/checkPwd',
      data: {data:JSON.stringify({"phone":page.data.phone})},
      method: 'POST',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){
        if(res.data.status == 1){
          wx.request({
            url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Login/send',
            data: {data:JSON.stringify({"phone":page.data.phone})},
            method: 'POST',
            header: {
                'content-type': 'application/x-www-form-urlencoded'
            },
            success: function(res){
              console.log(res)
            }
          })
        }
      }
    })
  },
  formSubmit: function(e) {
    var formData = e.detail.value;
    var unionId = wx.getStorageSync('unionId');
    wx.request({
      url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Login/samllBind',
      data: {data:JSON.stringify({"uuid":unionId,"phone":formData.phone,"code":formData.code,"pwd":''})},
      method: 'POST',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){console.log(res)
        if(res.data.status == 1){
          wx.setStorageSync('uid', res.data.data);
          wx.navigateBack()
        }else if(res.data.status == 0){
          wx.showToast({
            title : res.data.msg
          })
        }
      }
    })
  }
})