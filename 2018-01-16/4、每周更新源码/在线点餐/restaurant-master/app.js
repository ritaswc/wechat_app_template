//app.js
App({
  // 生命周期函数--监听小程序初始化
  onLaunch: function () {
    //调用API从本地缓存中获取数据
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs)
  },
  // 生命周期函数--监听小程序显示
  onShow:function(){
  	wx.getUserInfo({
	  success: function(res) {
	  	console.log(res);
	    var userInfo = res.userInfo
	  }
	})
  },

  // 生命周期函数--监听小程序隐藏
  onHide:function(){},

  // 开发者可以添加任意的函数或数
  // 据到Object参数中，用this可以访问
  getUserInfo:function(cb){
    var that = this
    if(this.globalData.userInfo){
      typeof cb == "function" && cb(this.globalData.userInfo)
    }else{
      //调用登录接口
      wx.login({
        success: function () {
          wx.getUserInfo({
            success: function (res) {
              that.globalData.userInfo = res.userInfo
              typeof cb == "function" && cb(that.globalData.userInfo)
            }
          })
        }
      })
    }
  },
  globalData:{
    userInfo: null,
    goods: {
			1: {
				id: 1,
				name: '果盘3',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 1014,
				price: 120
			},
			2: {
				id: 2,
				name: '龙舌兰',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 1029,
				price: 100
			},
			3: {
				id: 3,
				name: '方便面',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 1030,
				price: 5
			},
			4: {
				id: 4,
				name: '粉丝',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 1059,
				price: 5
			},
			5: {
				id: 5,
				name: '果盘1',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 1029,
				price: 130
			},
			6: {
				id: 6,
				name: '果盘2',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 1064,
				price: 150
			},
			7: {
				id: 7,
				name: '锐澳',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 814,
				price: 200
			},
			8: {
				id: 8,
				name: '尊尼获加',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 124,
				price: 220
			},
			9: {
				id: 9,
				name: '芝士华',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 102,
				price: 300
			}
		},
  }
})