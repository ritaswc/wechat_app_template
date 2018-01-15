//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    motto: 'MiHome_Store',
    userInfo: {},
    indicatorDots: true,
    autoplay: true,
    interval: 3000,
    duration: 100,
    newgoods:[
      { 
        "hg_pic":"http://img14.yiguoimg.com/e/ad/2016/160914/585749449477366062_260x320.jpg"
      },{
        "hg_pic":"http://img09.yiguoimg.com/e/ad/2016/161011/585749449909281099_260x320.jpg"
      },{
        "hg_pic":"http://img12.yiguoimg.com/e/ad/2016/160914/585749449480249646_260x320.jpg"
      }
    ],
    hotgoods:[
      {
        "more_pic":"http://img10.yiguoimg.com/e/ad/2016/161008/585749449862226248_778x303.jpg"
      },{
        "more_pic":"http://img14.yiguoimg.com/e/ad/2016/160929/585749449767461181_778x303.jpg"
      },{
        "more_pic":"http://img12.yiguoimg.com/e/ad/2016/161009/585749449871663433_778x303.jpg"
      },{
        "more_pic":"http://img10.yiguoimg.com/e/ad/2016/161008/585749449862226248_778x303.jpg"
      },{
        "more_pic":"http://img14.yiguoimg.com/e/ad/2016/160929/585749449767461181_778x303.jpg"
      },{
        "more_pic":"http://img12.yiguoimg.com/e/ad/2016/161009/585749449871663433_778x303.jpg"
      },{
        "more_pic":"http://img10.yiguoimg.com/e/ad/2016/161008/585749449862226248_778x303.jpg"
      },{
        "more_pic":"http://img14.yiguoimg.com/e/ad/2016/160929/585749449767461181_778x303.jpg"
      },{
        "more_pic":"http://img12.yiguoimg.com/e/ad/2016/161009/585749449871663433_778x303.jpg"
      },{
        "more_pic":"http://img10.yiguoimg.com/e/ad/2016/161008/585749449862226248_778x303.jpg"
      }
    ],
    banner_list:[
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
   "banner": [
          {
            "pic_url": "/images/icons/iocn_home_01.png",
            "title":"新品尝鲜",
          },
          {
            "pic_url": "/images/icons/iocn_home_02.png",
            "title":"订单查询",
          },
          {
            "pic_url": "/images/icons/iocn_home_03.png",
            "title":"水果精选",
          },
          {
            "pic_url": "/images/icons/iocn_home_04.png",
            "title":"优惠券",
          }
        ]
      }
    ]},
  onPullDownRefresh: function () {
    console.log('onPullDownRefresh')
  },
  scroll: function(e){
    //console.log(e)
  },

  //事件处理函数
  bindViewTap: function () {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
 
  onLoad: function () {
    //var that = this;
    // wx.request({
    //         url: 'http://m.htmlk.cn/json-test/list.json',
    //         header: {
    //             'Content-Type': 'application/json'
    //         },
    //         success: function(res) {
    //           that.setData({
    //                hotgoods:res.data
    //            });  
    //         }
    //     })
        
    //调用应用实例的方法获取全局数据
   
  }
})
