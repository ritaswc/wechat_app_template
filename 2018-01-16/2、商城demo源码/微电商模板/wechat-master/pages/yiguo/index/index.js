//index.js
//获取应用实例
var app = getApp()

Page({
  data: {
    toView: "",
    motto: 'MiHome_Store',
    userInfo: {},
    indicatorDots: true,
    autoplay: true,
    interval: 3000,
    duration: 100,
    newgoods: [
      {
        "hg_pic": "http://img14.yiguoimg.com/e/ad/2016/160914/585749449477366062_260x320.jpg"
      }, {
        "hg_pic": "http://img09.yiguoimg.com/e/ad/2016/161011/585749449909281099_260x320.jpg"
      }, {
        "hg_pic": "http://img12.yiguoimg.com/e/ad/2016/160914/585749449480249646_260x320.jpg"
      }
    ],
    hotgoods: [],
    banner_list: [
      {
        "banner": [
          {
            "pic_url": "http://img09.yiguoimg.com/e/ad/2016/160825/585749448986042649_800x400.jpg",
          },
          {
            "pic_url": "http://img11.yiguoimg.com/e/ad/2016/160927/585749449690947899_800x400.jpg",
          },
          {
            "pic_url": "http://img14.yiguoimg.com/e/ad/2016/160923/585749449636290871_800x400.jpg",
          },
          {
            "pic_url": "http://img13.yiguoimg.com/e/ad/2016/160914/585749449480315182_800x400.jpg",
          },
          {
            "pic_url": "http://img14.yiguoimg.com/e/ad/2016/161010/585749449889390922_800x400.jpg",
          }
        ]
      },
      {
        "banner": []
      }
    ]
  },
  onPullDownRefresh: function () {
    console.log('onPullDownRefresh')
  },
  scroll: function (e) {
    if (this.data.toView == "top") {
      this.setData({
        toView: ""
      })
    }
  },

  //事件处理函数
  bindViewTap: function () {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  tap: function () {
    this.setData({
      toView: "top"
    })
  },
  onLoad: function () {

    //调用应用实例的方法获取全局数据
    var that = this;
   

    //通过原生调取数据
    wx.request({
      url: 'https://wxapi.hotapp.cn/api/get',
      data: {
        appkey: 'hotapp25781921',
        key: 'hot1'
      },
      method: "GET",
      header: {
        "content-type": "application/json"
      },
      success: function (a) {
        console.log(a)
        return "function" == typeof b && b(a.data)
      },
      fail: function (err) {
        console.log(err)
        return "function" == typeof b && b(!1)
      }
    })
    //fecth调用
    var fekchobj = {
      R_GET: function (url, params) {
        if (params) {
          let paramsArray = []
          Object.keys(params).forEach(key => paramsArray.push(key + '=' + encodeURIComponent(params[key])))
          if (url.search(/\?/) === -1) {
            url += '?' + paramsArray.join('&')

          } else {
            url += '&' + paramsArray.join('&')
          }
        }

        return new Promise(function (resolve, reject) {
          fetch(url)
            .then((response) => {
              if (response.ok) {
                return response.json()

              } else {
                reject('服务器繁忙，请稍后再试；\r\nCode:' + response.status)
              }
            })
            .then((response) => {
              if (response && response.status) {
                resolve(response)//response.status 是与服务器端的约定，非0就是错误
              } else {
                reject(response)//response也是与服务器端的约定
              }
            })
            .catch((err) => {
              reject(_parseErrorMsg(err))
            })
        })
      }
    }
    //监测错误
    try{
     
    }catch(e){
      console.log(e)
    }
   console.log(fekchobj)
    


  }
})
