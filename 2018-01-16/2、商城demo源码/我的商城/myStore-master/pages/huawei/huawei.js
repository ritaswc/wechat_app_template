// pages/huawei/huawei.js
Page({
  data: {
    title: "",
    shopList: [
      {
        shopID:"1",
        imgUrl: "../../img/huawei_zhuanqu_1.jpg",
        shopName: "【年货节|买赠99元大礼包】Huawei/华为 畅享6全网通4G智能手机",
        price: "￥1299",
        sales: "3401人付款"
      },
      {
         shopID:"2",
        imgUrl: "../../img/huawei_zhuanqu_2.jpg",
        shopName: "【年货节】Huawei/华为 M3 平板电脑 8.4英寸哈曼卡顿联合设计",
        price: "￥2288",
        sales: "5386人付款"
      },
      {
         shopID:"3",
        imgUrl: "../../img/huawei_zhuanqu_3.jpg",
        shopName: "【年货节|直降20元】华为路由 WS832 智能无线路由器1200M",
        price: "￥249",
        sales: "2560人付款"
      },
      {
         shopID:"4",
        imgUrl: "../../img/huawei_zhuanqu_4.jpg",
        shopName: "【年货节|下单立减200|送178元礼】Huawei/华为 G9 青春版4G手机",
        price: "￥1499",
        sales: "5462人付款"
      },
      {
         shopID:"5",
        imgUrl: "../../img/huawei_zhuanqu_5.jpg",
        shopName: "【年货节|直降100元起】Huawei/华为 华为畅享5S 双卡双待4G手机",
        price: "￥999",
        sales: "8336人付款"
      },
      {
         shopID:"6",
        imgUrl: "../../img/huawei_zhuanqu_6.jpg",
        shopName: "【年货节|年度旗舰】Huawei/华为 Mate 9 32/64GB智能手机限量抢",
        price: "￥3999",
        sales: "3.1万人付款"
      },
    ],
  },

itemClick: function(e){
    wx.navigateTo({
      url: '../../pages/shopinfo/shopinfo?id=' +e.currentTarget.dataset.id,
    })
},

  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    console.log(options);
    this.setData({
      title: options.itemType
    });
    wx.setNavigationBarTitle({ title: this.data.title })

  },
  onReady: function () {
    // 页面渲染完成
    
  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  }
})