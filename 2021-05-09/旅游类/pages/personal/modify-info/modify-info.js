// pages/personal/xiugai/xingai.js
Page({
  data: {
    userInfo: {},
    avatarUrl: "",
    date: "",
    array: ['北京市', '天津市', '上海市', '重庆市', '河北省', '河南省', '云南省', '辽宁省', '黑龙江省', '湖南省', '安徽省', '山东省', '新疆维吾尔', '江苏省', '浙江省', '江西省', '湖北省', '广西壮族', '甘肃省', '山西省', '内蒙古', '陕西省', '吉林省', '福建省', '贵州省', '广东省', '青海省', '西藏', '四川省', '宁夏回族', '海南省', '台湾省', '香港', '澳门', '其他'],
    index: 0,
    checkednan: true,
    checkednv: false
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数

    let date = new Date();
    this.setData({
      date: date.getFullYear() + '-' + formatDate((date.getMonth() + 1)) + "-" + formatDate(date.getDay()),
      userInfo: JSON.parse(options.userInfo),
      avatarUrl: JSON.parse(options.userInfo).avatarUrl
    });
    function formatDate(n) {
      return n < 10 ? "0" + n : n;
    }
  },
  bindDateChange(e) {
    this.setData({
      date: e.detail.value
    });
  },
  bindPosChange(e) {
    this.setData({
      index: e.detail.value
    });
  },
  changeSex(e) {
    if (e.detail.value == "男") {
      this.setData({
        checkednan: true,
        checkednv: false
      });
    } else {
      this.setData({
        checkednan: false,
        checkednv: true
      });
    }
  },
  changeHeadImg() {
    let _this = this;
    wx.chooseImage({
      count: 1, // 最多可以选择的图片张数，默认9
      sizeType: ['original', 'compressed'], // original 原图，compressed 压缩图，默认二者都有
      sourceType: ['album', 'camera'], // album 从相册选图，camera 使用相机，默认二者都有
      success: function (res) {
        _this.setData({
          avatarUrl: res.tempFilePaths[0]
        });
      }
    })
  },
  bindsubmit(e) {
    let data = this.data;
    let val = {
      info: e.detail.value,
      date: data.date,
      array: data.array[data.index],
      checkednv: data.checkednv,
      checkednan: data.checkednan,
    }
    // 上传头像
    // wx.uploadFile({
    //   url: 'https://String',
    //   filePath:data.avatarUrl,
    //   name:'name',
    //   // header: {}, // 设置请求的 header
    //   // formData: {}, // HTTP 请求中其他额外的 form data
    //   success: function(res){
    //     // success
    //   }
    // })
    // 发送基本信息到数据库，保存
    // wx.request({
    //   url: 'https://',
    //   data:val,
    //   method: 'post', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
    //   // header: {}, // 设置请求的 header
    //   success: function(res){
    //     // success
    wx.showToast({
        title: '保存成功',
        icon: 'success',
        duration: 2000
      })
    //           setTimeout(function(){
    //   wx.hideToast()
    // },2000)
    //   }
    // })
  }
})