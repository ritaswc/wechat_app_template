Page({
  data: {
    array: ['男', '女'],
    index: 1,
    userInfo: [],
    imgUrl: null,
    pnPark: [
      {
        page: "park",
        img: "../../images/park.png",
        title: "我要代泊",
      },
      {
        page: "trip",
        img: "../../images/park.png",
        title: "预约查看",
      },
      {
        page: "trip",
        img: "../../images/history.png",
        title: "历史形成",
      },
      {
        page: "car",
        img: "../../images/car.png",
        title: "车辆管理",
      },
    ]
  },
  onLoad: function (options) {
    var vm = this;

    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/user/user-info',
      data: {
        openid: wx.getStorageSync('openid')
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        console.log(res)
        vm.setData({
          userInfo: res.data.data
        });
        var array = ['男', '女'];
        var v = res.data.data.sex;
        for (var i in array) {
          if (array[i] == v) {
            vm.setData({
              index: i
            });
          }
        }
      }
    });
  },

  //事件处理函数
  bindViewTapPark: function (e) {
    var page = e.currentTarget.dataset.page;
    wx.redirectTo({
      url: '../' + page + '/' + page
    })
  },
  setPhotoInfo: function (e) {
    var vm = this;

    wx.chooseImage({
      count: 1, // 默认9
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function (res) {
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
        var tempFilePaths = res.tempFilePaths
      
        vm.setData({
          imgUrl: tempFilePaths
        })
          wx.setStorageSync('imgUrl', tempFilePaths)
           wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/user/modify-head-img',
      data: {
        openid: wx.getStorageSync('openid'),
        logo: wx.getStorageSync('imgUrl'),
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {

      }
    });

      }
    });
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/user/modify-head-img',
      data: {
        openid: wx.getStorageSync('openid'),
        logo: wx.getStorageSync('imgUrl'),
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {

      }
    });
  },
  bindPickerChange: function (e) {
    this.setData({
      index: e.detail.value
    })
    var sex
    if(e.detail.value=="女"){
      sex=2
    }else{
       sex=1
    }

     wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/user/modify-sex',
      data: {
        openid: wx.getStorageSync('openid'),
        sex: sex,
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {

      }
    });
  },
   bindKeyInput: function(e) {
     
     wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/user/modify-nickname',
      data: {
        openid: wx.getStorageSync('openid'),
        nickname: e.detail.value,
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {

      }
    });
  },
 
})