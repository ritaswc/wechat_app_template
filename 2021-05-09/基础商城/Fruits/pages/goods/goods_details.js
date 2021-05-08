
//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    //motto: 'Hello World',
    //userInfo: {},
   /* goods_src: [
      {
          name: '乡味购',
          describe: '新疆特级碧乡味购】新疆特级碧乡味购】新疆特级碧乡味购】',
          price: '59.9',
          zan: '1',
          picture: {
              src: '../../images/ll.png',
              src: '../../images/tus.png',
              src: '../../images/tus.png'
          }
      }
    ],*/
    details: [
       {
          name: '乡味购',
          classes: '特级',
          origines: '新疆',
          packages: '袋装',
          guige: '500',
          weight: '500',
          weidao: '原味',
          send_way: '圆通快递',
          baoyou: '0',
          expire_in: '12个月',
          store_way: '置于阴凉干燥通风处，密闭贮存',
          describe: '新疆特级碧乡味购】新疆特级碧乡味购】新疆特级碧乡味购】',
          price: '59.9',
          picture: [
              '../../images/ll.png',
              '../../images/tus.jpg',
              '../../images/tus.jpg'
          ],
          goods_src: '../../images/tpx.jpg'
        }
    ],
    pingjia: [
        {
        nickname: '绿柚子',
        touxiang_src: '../../images/tx.png',
        time: '1分钟前',
        content: '非常好！非常好！'
        },
        {
        nickname: '绿柚子',
        touxiang_src: '../../images/tx.png',
        time: '1分钟前',
        content: '非常好！非常好！'
        }
    ],
    indicatorDots: true,
    autoplay: true,
    interval: 5000,
    duration: 1000,
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
    console.log('onLoad')
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    })
  }
})
