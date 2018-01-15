const Api = require('../../utils/api.js')

Page({
  data: {
    lists: [],
    winWidth: '',
    winHeight: ''
  },
  onLoad: function () {
    //设置token
    this.setData({
      token: wx.getStorageSync('token')
    })

    // 获取系统信息 
    wx.getSystemInfo({
      success: (res) => {
        this.setData({
          winWidth: res.windowWidth,
          winHeight: res.windowHeight
        })
      }
    })
  },

  onShow: function() {
    this.getCompanyList()
  },

  //获取已创建的公司列表
  getCompanyList: function() {
    wx.request({
      url: Api.companyList + this.data.token,
      data: {},
      method: 'GET',
      success:  (res) => {
        if(res.statusCode == 200) {
          this.setData({
            lists: res.data
          })
        }   
      }
    })
  },

  //事件处理函数
  item_click: function (event) {
    var id = event.currentTarget.dataset.companyid
    wx.navigateTo({ 
      url: '/pages/confirm/confirm?companyId=' + id 
    })
  },
})
