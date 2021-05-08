//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    windowHeight: 0,

    motto: 'Hello World',
    userInfo: {},

    bodyShow: false,
    
    portUrl: 'https://tech11.cn/a/taiji/user/AccountOpen',
    portType: 8,

    bgImg: '../../image/bgImg.jpg',
    bgInput: '../../image/bgInput.png',
    bgSubmit: '../../image/btnSubmit.png',
    imgTitle: '../../image/title.png',
    imgBottomText: '../../image/bottomText.png',
    imgT9Logo: '../../image/iconLogo.png',

    phoneNumberInit: '',
    userNameInit: '',
    phoneNumber: '',
    userName: '',
    sendLoading: false,
    toastShow: false,
    toastMask: false,
    toastIcon: '',
    toastTitle: '提示语',
    toastTemp: null
  },
  onShow () {
    this.setData({
      phoneNumberInit: '',
      userNameInit: ''
    })
  },
  //事件处理函数
  bindViewTap () {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  //校验
  validate () {
    var that = this;
    var phone = that.data.phoneNumber;
    var userName = that.data.userName;
    
    //是否在提交中
    // if(that.data.sendLoading) return false;
    
    //去除两边空格
    var trim = function(str) {
      return str.replace(/(^\s*)|(\s*$)/g, ""); 
    };

    if(trim(phone).length == 0) {
      that.showToast({
        icon: 'phone',
        title: '请输入正确的手机号'
      })
      return false;
    }

    var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
    if(!reg.test(trim(phone))) {
      that.showToast({
        icon: 'phone',
        title: '请输入正确的手机号'
      })
      return false;
    }

    if(trim(userName).length == 0) {
      that.showToast({
        icon: 'user',
        title: '请输入姓名'
      })
      return false;
    }
    return true;
  },
  // 显示提示框
  showToast (opts) {
    var that = this;
    var title = opts.title || '';
    var icon = opts.icon || '';
    var mask = opts.mask || false;
    var duration = opts.duration || 3000;
    that.setData({
      toastShow: true,
      toastTitle: title,
      toastMask: mask,
      toastIcon: icon
    })
    if(duration == 'along') return;
    clearTimeout(that.data.toastTemp);
    that.setData({
      toastTemp: setTimeout(function() {
        that.setData({
          toastShow: false,
          toastMask: false
        })
      }, duration) 
    })
  },
  // 隐藏提示框
  hideToast (opts) {
    this.setData({
      toastShow: false,
      toastTitle: '',
      toastIcon: '',
      duration: '',
      toastMask: false
    })
  },
  // 监听手机号
  bindPhoneNumber (e) {
    this.data.phoneNumber = e.detail.value;
  },
  //
  bindUserName (e) {
    this.data.userName = e.detail.value;
  },
  //提交
  onSubmit () {
    // console.log('submit')
    // console.log(encodeURIComponent(JSON.stringify(this.data.userInfo)))
    // console.log(JSON.stringify(this.data.userInfo));
    // return false;

    var that = this;
    if(!that.validate()) return;

    that.showToast({
      title: '提交中...',
      icon: 'loading',
      mask: true,
      duration: 'along'
    })
    that.sendRequest();
  },
  // 提交
  sendRequest () {
    var that = this;
    var phone = this.data.phoneNumber;
    var userName = this.data.userName;
    // phone = '13430614810';
    // userName = 'hetianhe';
    // console.log(phone, userName);
    
    var sucCallback = function() {
      that.hideToast();
      wx.navigateTo({
        url: '../result/result'
      })
    };
    var hasOpenCallback = function(msg) {
      that.hideToast();
      that.showToast({
        title: msg,
        icon: 'tips',
        duration: 4000
      })
    };
    var errCallback = function(msg) {
      that.hideToast();
      that.showToast({
        title: msg,
        icon: 'tips',
        duration: 4000
      })
    };

    // var randomResult = function() {
    //   // var randomNum = Math.floor(Math.random()*(3)) + 1;
    //   var randomNum = that.data.userName;
    //   randomNum = 1;
    //   if(randomNum == 1) {
    //     sucCallback();
    //   } else if(randomNum == 2) {
    //     hasOpenCallback('hasOpenCallback');
    //   } else if(randomNum == 3) {
    //     errCallback('error');
    //   }
    // };
    // setTimeout(function() {
    //   randomResult();
    // }, 4000);
    // return false;

    wx.request({
      url: that.data.portUrl,
      data: {
        type: that.data.portType,
        phone: phone,
        name: userName,
        tssign: (new Date()).getTime(),
        appInfo: 'cow_xcx',
        note: encodeURIComponent(JSON.stringify(this.data.userInfo))
      },
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {
        var data = res.data.data;
        var code = data.code;
        var message = data.message;
        console.log(data);
        // code:0  message:预约成功
        // code:-1 message:该手机号已经预约
        if(code == 0) {
          sucCallback();
        } else if(code == -1) {
          hasOpenCallback(message);
        } else {
          errCallback(message);
        }
      }
    })
  },
  onLoad () {
    // console.log('onLoad')
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
      // console.log(that.data.userInfo)
    })
    that.setData({
      windowHeight: app.globalData.systemInfo.windowHeight
    });
  },
  onReady () {
    var that = this;
    that.setData({
      bodyShow: true
    })
  },
  // 定义分享
  onShareAppMessage () {
    return {
      title: '泰九智投产品预约',
      desc: '泰九智能投顾产品热销中，欢迎预约购买！',
      path: '/pages/index/index'
    }
  }
})
