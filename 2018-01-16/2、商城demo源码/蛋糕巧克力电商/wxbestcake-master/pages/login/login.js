// pages/login/login.js

var base = getApp();
Page({
  data: {
    phone: "",
    pwd: ""
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数  
  },
  changephone: function (e) {
    this.setData({
      phone: e.detail.value
    });
  },
  changepwd: function (e) {
    this.setData({
      pwd: e.detail.value
    });
  },
  submit: function () {
    var _this = this;
    base.get({ c: "User", m: "Login", phone: _this.data.phone, pwd: _this.data.pwd, }, function (d) {
      var dt = d.data;
      if (dt.Status == "ok") {
        base.user.userid = dt.Tag.Uid;
        base.user.sessionid = dt.Tag.SessionId;
        base.user.jzb = dt.Tag.Money;
        base.user.exp = dt.Tag.Exp;
        base.user.phone = dt.Tag.Phone;
        base.user.levels = dt.Tag.Levels;
        base.user.headimg = dt.Tag.HeadImgPath;
        wx.switchTab({
          url: '../user/user'
        })
      }
      else {
        wx.showModal({
          showCancel: false,
          title: '',
          content: dt.Msg
        });

      }
    })

  }
})