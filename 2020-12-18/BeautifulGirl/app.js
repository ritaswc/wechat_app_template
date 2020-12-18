//app.js
App({
  onLaunch: function () {
    //调用API从本地缓存中获取数据
    let types = wx.getStorageSync("types");
    if(!types){
      wx.setStorageSync("types", this.globalData.types);
    }
  },
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
    api:{
      listBaseUrl:"https://route.showapi.com/959-1?showapi_appid=25744&showapi_sign=f3807528bd5d4a4ea6b2027e8286e0dc&type=",
      albumBaseurl:"https://route.showapi.com/959-2?id=%id%&showapi_appid=25744&showapi_sign=f3807528bd5d4a4ea6b2027e8286e0dc",
    },
    currentType:'',
    types:[
      {
        title:"比基尼",
        value:"bijini",
        is_show:true
      },
      {
        title:"制服",
        value:"zhifu",
        is_show:true
      },
      {
        title:"写真艺术",
        value:"nvyou",
        is_show:true
      },
      {
        title:"性格美女",
        value:"xingge",
        is_show:true
      },
      {
        title:"模特",
        value:"mote",
        is_show:true
      },
      {
        title:"剧照",
        value:"yingshi",
        is_show:true
      },
      {
        title:"自拍",
        value:"tpzp",
        is_show:true
      },
      {
        title:"丝袜",
        value:"siwa",
        is_show:true
      },
      {
        title:"裙装",
        value:"qunzhuang",
        is_show:true
      },
      {
        title:"情趣",
        value:"qingqu",
        is_show:true
      },
    ]
  },
  
})