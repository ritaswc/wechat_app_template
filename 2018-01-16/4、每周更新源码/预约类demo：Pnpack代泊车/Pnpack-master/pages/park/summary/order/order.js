Page({
  data: {
    array: [],
    carData: [],
    index: 0,
    dateHan: "",
    timeHan: "",
    dateTake: "",
    timeTake: "",
    pointInfo: '',
    starttime: '',
    point_id: '',
    carnumber: '',
    brandname: '',
    detailcase: '',
    totalmoney: ''
  },
  onLoad: function (options) {
    var vm = this
    var date = new Date()
    var nowYear = date.getFullYear()
    var nowMonth = date.getMonth() + 1
    var nowDay = date.getDate()
    var nowHour = date.getHours()
    var nowMinute = date.getMinutes() + 1
    if( date.getMinutes() > 30 ) {

    }
    if (nowMonth < 10) {
      nowMonth = "0" + nowMonth;
    }
    if (nowHour < 10) {
      nowHour = "0" + nowHour;
    }
    if (nowMinute < 10) {
      nowMinute = "0" + nowMinute;
    }
    if (nowDay < 10) {
      nowDay = "0" + nowDay;
    }
    vm.setData({
      dateHan: nowYear + '-' + nowMonth + '-' + nowDay
    })
    vm.setData({
      timeHan: nowHour + ':' + nowMinute
    })
    vm.setData({
      dateTake: nowYear + '-' + nowMonth + '-' + nowDay
    })
    vm.setData({
      timeTake: nowHour + 3 + ':' + nowMinute
    })
    vm.setData({
      point_id: options.id
    })
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/parking/point-info',
      data: {
        point_id: options.id
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        console.log(res.data.data.info.address)
        vm.setData({
          pointInfo: res.data.data.info.address
        })
      }
    })
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/cars/my-car-list',
      header: {//请求头
        "Content-Type": "application/x-www-form-urlencoded"
      },
      data: {
        openid: wx.getStorageSync('openid')
      },
      method: "POST",//get为默认方法/POST
      success: function (res) {

        var carData = []
        var carnumber = []
        var brandname = []
        for (var x in res.data.message) {
          carData.push(res.data.message[x].brand_name + '--' + res.data.message[x].car_name)
          carnumber = res.data.message[x].car_name
          brandname = res.data.message[x].brand_name
        }
        vm.setData({
          carData: carData
        })
        vm.setData({
          carnumber: carnumber
        })
        vm.setData({
          brandname: brandname
        })
      }
    })
  },
  bindPickerChange: function (e) {
    this.setData({
      index: e.detail.value
    })
  },
  bindDateHan: function (e) {
    var vm = this
    vm.setData({
      dateHan: e.detail.value
    })
  },
  bindTimeHan: function (e) {
    var vm = this
    vm.setData({
      timeHan: e.detail.value
    })
  },
  bindDateTake: function (e) {
    var vm = this
    vm.setData({
      dateTake: e.detail.value
    })
  },
  bindTimeTake: function (e) {
    var vm = this
    console.log('picker发送选择改变，携带值为', e.detail.value)
    vm.setData({
      timeTake: e.detail.value
    })
  },
  bindSuccess: function () {
    var vm = this
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/get-best-case',
      header: {//请求头
        "Content-Type": "application/x-www-form-urlencoded"
      },
      data: {
        point_id: vm.data.point_id,
        car_id: 59,
        starttime: vm.data.dateHan + ' ' + vm.data.timeHan,
        endtime: vm.data.dateTake + ' ' + vm.data.timeTake
      },
      method: "POST",//get为默认方法/POST
      success: function (res) {
        vm.setData({
          detailcase: res.data.data.detailcase
        })
        vm.setData({
          totalmoney: res.data.data.totalmoney
        })
        wx.showModal({
          title: '预约付款',
          confirmText: '微信支付',
          content: '根据您提交的泊车时间，系统计算出您本次泊车的最优方案为【 '
          + res.data.data.detailcase + ' 】共需要支付的费用为【 '
          + res.data.data.totalmoney + ' 】元',
          success: function (res) {
            if (res.confirm) {
              wx.request({
                url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/save-order',
                data: {
                  openid: wx.getStorageSync('openid'),  //微信唯一标识openid
                  point_id: vm.data.point_id,  // 泊车点编号
                  carnumber: '京A88588',  //  车牌
                  brandname: '宝马',  //  车辆品牌
                  starttime: vm.data.dateHan + ' ' + vm.data.timeHan,  //  交车时间
                  endtime: vm.data.dateTake + ' ' + vm.data.timeTake,  //  还车时间
                  detailcase: vm.data.detailcase,  //  最佳收费方案
                  totalmoney: vm.data.totalmoney  //   总金额
                },
                header: {
                  "Content-Type": "application/x-www-form-urlencoded"
                },
                method: "POST",
                success: function (res) {
                  console.log(res.data.data)
                  wx.requestPayment({
                    'timeStamp': res.data.data.timeStamp,//时间戳从1970年1月1日00:00:00至今的秒数,即当前的时间
                    'nonceStr': res.data.data.nonceStr,//随机字符串，长度为32个字符以下。
                    'package': res.data.data.package,//统一下单接口返回的 prepay_id 参数值，提交格式如：prepay_id=*
                    'signType': 'MD5',//签名算法，暂支持 MD5
                    'paySign': res.data.data.paySign,//签名
                    'success': function (item) {
                      wx.showToast({
                        title: '支付成功',
                        duration: 2000,
                        success: function () {
                          wx.showModal({
                            title: '完成预约',
                            confirmText: '返回首页',
                            content: '您已成功完成预约，请您在交车时间到达泊车点，我们会派专人为您服务，您可在我的订单查看本次泊车订单，进行相关操作！',
                            success: function (res) {
                              if (res.confirm) {
                                wx.navigateBack({
                                  delta: 3
                                })
                              }
                            }
                          })

                        }
                      })
                    },
                    'fail': function (item) {
                      wx.showToast({
                        title: '支付失败',
                        duration: 2000
                      })
                    }
                  })
                }
              })
            }
          }
        })
      }
    })
  }
})