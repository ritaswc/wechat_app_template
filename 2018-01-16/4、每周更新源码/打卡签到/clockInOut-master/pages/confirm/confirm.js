const Api = require('../../utils/api.js')

Page({
  data: {
    companyName: '',
    companyLocation: '',
    checkButton: true,
    winWidth: '',
    winHeight: '',
  },

  onLoad: function (options) {
    // 获取系统信息
    wx.getSystemInfo({ 
      success: (res) => {
        this.setData({
          winWidth: res.windowWidth,
          winHeight: res.windowHeight
        })
      }
    })

    this.setData({
      id: options.companyId,
      token: wx.getStorageSync('token')
    })

  },

  onShow: function() {
    this.getCompanyInfo()
  },

  //获取单个公司信息
  getCompanyInfo: function() {
    wx.request({
      url: Api.companyDetail + this.data.id + '?token=' + this.data.token,
      data: {},
      method: 'GET',
      success: (res) => {
        console.log(res)
        if(res.statusCode == 200) {
          this.setData({
            companyName: res.data.name,
            companyLocation: res.data.address,
          })
        }  
      }
    })
  },

  checkBtn_click: function (event) {

    wx.showModal({
      title: '申请加入公司',
      content: '确定加入该公司吗？这无法变更，请仔细考虑',
      success: (res) => {
        if (res.confirm) {
          wx.request({
            url: Api.joinCompany + this.data.token,
            data: {
              companyId: this.data.id
            },
            method: 'POST',
            success: (res) => {
              wx.redirectTo({
                url: '/pages/audit/audit?companyName=' + this.data.companyName + '&message=' + res.data.message
             })
            }
          })
        }
        else {
          wx.navigateBack({
            delta: 1, // 回退前 delta(默认为1) 页面
          })
        }
      }
    })

  }

})