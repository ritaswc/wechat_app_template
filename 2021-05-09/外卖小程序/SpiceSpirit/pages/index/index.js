const app = getApp()
let height = 0
Page({
  data:{
    foodCategorys:["虾蟹","特色美食","预订商品","主食酒水"],
    current_id:0,
    city:'北京市',
    screenHeight:parseInt(app.globalData.height) * 2,
    hot_goods:[
      {
        "name":"麻辣龙虾尾",
        "price":"89",
        "id":0,
        "pic":"../../images/MNX.png",
        "sale_num":99
      },
      {
        "name":"嗨麻嗨辣小龙虾",
        "price":"89",
        "id":1,
        "pic":"../../images/MX.png",
        "sale_num":99
      },
      {
        "name":"麻辣小龙虾",
        "price":"89",
        "id":2,
        "pic":"../../images/MXJP.png",
        "sale_num":99
      },
    ],
    common_goods:[
      {
        "name":"麻辣豆皮",
        "price":"89",
        "id":0,
       "pic":"http://123.56.182.28/images/201604/1460351377410581585.jpg",
        "sale_num":99
      },
      {
        "name":"麻辣海白菜",
        "price":"89",
        "id":1,
        "pic":"http://123.56.182.28/images/201604/1460351328442917106.jpg",
        "sale_num":99
      },
      {
        "name":"微辣兔头",
        "price":"89",
        "id":2,
        "pic":"http://123.56.182.28/images/201604/1460351296256062421.jpg",
        "sale_num":99
      },
      {
        "name":"微辣鸭舌",
        "price":"89",
        "id":3,
        "pic":"http://123.56.182.28/images/201604/1460351255750519007.jpg",
        "sale_num":99
      },
      {
        "name":"麻辣牛蹄筋",
        "price":"89",
        "id":4,
        "pic":"http://123.56.182.28/images/201604/1460351205527158641.jpg",
        "sale_num":99
      },
      {
        "name":"麻辣小竹笋",
        "price":"89",
        "id":5,
        "pic":"http://123.56.182.28/images/201604/1460351146788043235.jpg",
        "sale_num":99
      }
    ]
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    let res = wx.getSystemInfoSync()
    height = res.windowHeight
    console.log("app" + app.globalData.height)
  },
  onReady:function(){
    // 页面渲染完成
     console.log(app.objcToString(this.data.hot_goods[0])) 
  },
  onShow:function(){
    // 页面显示
    
  },
  onHide:function(){
    // 页面隐藏
    
  },
  onUnload:function(){
    // 页面关闭
    
  },
  selectSection:function(event){
    console.log(event)
      let index = parseInt(event.currentTarget.dataset.index);
      this.setData({current_id:index})
  },
  showdetail:function(event){
      let good = {}
      let index = parseInt(event.currentTarget.dataset.index)
      if(this.data.current_id == 0){
        good = this.data.hot_goods[index]
      }else{
        good = this.data.common_goods[index]
        console.log(good);
      }
      let params = app.objcToString(good)
      console.log("点击了" + params)
      wx.navigateTo({
        url: '../shopdetail/shopdetail?' + params,
        success: function(res){
          // success
        },
        fail: function() {
          // fail
        },
        complete: function() {
          // complete
        }
      })
  },
  onShareAppMessage: function () {
    return {
      title: '麻小外卖',
      desc: '小程序分享测试',
      path: '/pages/index/index'
    }
  },
  chooseAddress:function(){
    wx.navigateTo({
      url: '../addAddress/addAddress',
      success: function(res){
      },
      fail: function() {
      }
    })
  },
  // 跳转到购物车界面
  showshopcar:function(){
    wx.navigateTo({
         url: '../buycar/buycar'
       })
  }
})
