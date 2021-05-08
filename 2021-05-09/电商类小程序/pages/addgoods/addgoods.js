// pages/addgoods/addgoods.js
const app = getApp()
Page({
  data: {
    typeArray: [],
    groupArray: [],
    groupArr:[],
    typeindex: 0,
    groupindex: 0,
    imgurl: '',
    userid:'',
    objectArray: [
      {
        id: 0,
        name: '美国'
      },
      {
        id: 1,
        name: '中国'
      },
      {
        id: 2,
        name: '巴西'
      },
      {
        id: 3,
        name: '日本'
      }
    ],
  },
  bindPickerChangeType: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      typeindex: e.detail.value
    })
  },
  bindPickerChangeGroup: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      groupindex: e.detail.value
    })
  },
  addimg: function () {
    var that = this;
    wx.chooseImage({
      count: 1, // 默认9
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function (res) {
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
        var tempFilePaths = res.tempFilePaths
        console.log(tempFilePaths)

        that.setData({
          imgurl: tempFilePaths[0]
        })
      }
    })
  },
  formSubmit: function (e) {
    var that = this
    console.log('form发生了submit事件，携带数据为：', e.detail.value)
    var imgurl=that.data.imgurl;
    var value=e.detail.value
    wx.uploadFile({
      url: `${app.globalData.API_URL}/goods`, //仅为示例，非真实的接口地址
      filePath: imgurl,
      name: 'picture',
      formData: value,
      success: function (res) {
        var data = res.data
        console.log(res)
        wx.showModal({
          title:'添加成功',
          content: '是否返回首页',
          success: function (res) {
            if (res.confirm) {
              wx.switchTab({
                url: '/pages/index/index',
              })
            }
          }
        })
      },
      fail:function(res){
        console.log(res)

      }
      
    })

  },
  formReset: function () {
    console.log('form发生了reset事件')
  },
  onLoad: function (options) {
    //=获取群数据
    var that = this;
    var userid=wx.getStorageSync('login').mid;
    that.setData({
      userid:userid
    })
    wx.request({
      url: `${app.globalData.API_URL}/group`,
      data: {},
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        console.log(res)
        var arr = [];
        var arrid = [];
        for (var i = 0; i < res.data.length; i++) {
          console.log(res.data[i].shop_name)
          arr.push(res.data[i].shop_name)
          arrid.push(res.data[i].id)
        }
        console.log(arr)
        that.setData({
          groupArray: arr,
          groupArr: arrid
        })
        console.log(that.data.groupArr)
      },

      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
    //获取分类
     var that = this;
    wx.request({
      url: `${app.globalData.API_URL}/shop_product_cats`,//`${app.globalData.API_URL}/goods`
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        console.log(res.data)
         var arr = [];
        var arrid = [];
         for (var i = 0; i < res.data.length; i++) {
          arr.push(res.data[i].title)
          arrid.push(res.data[i].id)
        }
        that.setData({
          typeArray:arr
        })
      },

    })
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  }
})