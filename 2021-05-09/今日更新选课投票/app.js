const openIdUrl = require('./config').openIdUrl

App({
  onLaunch: function () {
    console.log('App Launch')
    var courseInfos = "[{\"courseNo\":\"001\",\"courseName\":\"用户体验设计/陈妍\",\"courseDesc\":\"腾讯传奇部门CDC倾情传授\",\"courseType\":\"艺术课\",\"courseScore\":\"9.5\",\"evaluation\":[{\"name\":\"纯干货\",\"data\":90},{\"name\":\"给分高\",\"data\":95},{\"name\":\"实践性\",\"data\":100},{\"name\":\"签到少\",\"data\":666}]},{\"courseNo\":\"002\",\"courseName\":\"动漫欣赏与实践/王伟\",\"courseDesc\":\"教你学会动漫欣赏\",\"courseType\":\"艺术课\",\"courseScore\":9.4,\"evaluation\":[{\"name\":\"纯干货\",\"data\":85},{\"name\":\"给分高\",\"data\":45},{\"name\":\"实践性\",\"data\":35},{\"name\":\"签到少\",\"data\":85}]},{\"courseNo\":\"003\",\"courseName\":\"计算机动画/许捷\",\"courseDesc\":\"教你学会计算机动画\",\"courseType\":\"艺术课\",\"courseScore\":\"9.4\",\"evaluation\":[{\"name\":\"纯干货\",\"data\":75},{\"name\":\"给分高\",\"data\":55},{\"name\":\"实践性\",\"data\":25},{\"name\":\"签到少\",\"data\":95}]},{\"courseNo\":\"004\",\"courseName\":\"国画技法/李维红\",\"courseDesc\":\"教你学会国画技法\",\"courseType\":\"艺术课\",\"courseScore\":\"9.4\",\"evaluation\":[{\"name\":\"纯干货\",\"data\":95},{\"name\":\"给分高\",\"data\":95},{\"name\":\"实践性\",\"data\":95},{\"name\":\"签到少\",\"data\":95}]},{\"courseNo\":\"005\",\"courseName\":\"导演制作/王强\",\"courseDesc\":\"教你学会导演制作\",\"courseType\":\"艺术课\",\"courseScore\":\"9.4\",\"evaluation\":[{\"name\":\"纯干货\",\"data\":95},{\"name\":\"给分高\",\"data\":95},{\"name\":\"实践性\",\"data\":95},{\"name\":\"签到少\",\"data\":95}]},{\"courseNo\":\"006\",\"courseName\":\"场景设计/王伟\",\"courseDesc\":\"教你学会场景设计\",\"courseType\":\"艺术课\",\"courseScore\":\"9.3\",\"evaluation\":[{\"name\":\"纯干货\",\"data\":45},{\"name\":\"给分高\",\"data\":35},{\"name\":\"实践性\",\"data\":25},{\"name\":\"签到少\",\"data\":15}]}]";
    var userInfo = "{\"userName\":\"未设置\",\"userCollege\":\"未设置\",\"userProfession\":\"未设置\",\"userStudentId\":\"未设置\",\"userMobile\":\"未设置\"}";
    var courseInfosJsonObject = JSON.parse(courseInfos);
    wx.setStorage({
      key: 'courseInfos',
      data: courseInfosJsonObject,
      success: function(res){
        console.log("课程信息存储成功")
      },
      fail: function() {
        console.log("课程信息存储失败")
      },
      complete: function() {
        // complete
      }
    })
    var userInfoJsonObject = JSON.parse(userInfo);
    wx.setStorage({
      key: 'userInfo',
      data: userInfoJsonObject,
      success: function(res){
        console.log("用户信息存储成功")
      },
      fail: function() {
        console.log("用户信息存储失败")
      },
      complete: function() {
        // complete
      }
    })
  },
  onShow: function () {
    console.log('App Show')
  },
  onHide: function () {
    console.log('App Hide')
  },
  globalData: {
    hasLogin: false,
    openid: null,
    courseInfos: null,
    userInfo: null,
  },

  // lazy loading openid
  getUserOpenId: function(callback) {
    var self = this

    if (self.globalData.openid) {
      callback(null, self.globalData.openid)
    } else {
      wx.login({
        success: function(data) {
          wx.request({
            url: openIdUrl,
            data: {
              code: data.code
            },
            success: function(res) {
              console.log('拉取openid成功', res)
              self.globalData.openid = res.data.openid
              callback(null, self.globalData.openid)
            },
            fail: function(res) {
              console.log('拉取用户openid失败，将无法正常使用开放接口等服务', res)
              callback(res)
            }
          })
        },
        fail: function(err) {
          console.log('wx.login 接口调用失败，将无法正常使用开放接口等服务', err)
          callback(err)
        }
      })
    }
  },

  // 获取课程数据
  getCourseInfos: function(callback) {
    var self = this
    wx.getStorage({
      key: 'courseInfos',
      success: function(res){
        self.globalData.courseInfos = res.data
        callback(res.data)
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
   // 根据标签获取课程数据
  getCourseInfosByTag: function(callback) {
    var self = this

  },

  // 获取用户信息
  getUserInfo: function(callback) {
    var self = this
    wx.getStorage({
      key: 'userInfo',
      success: function(res){
        self.globalData.userInfo = res.data
        callback(res.data)
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
 

})
