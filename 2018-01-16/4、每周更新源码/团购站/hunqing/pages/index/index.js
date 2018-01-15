//index.js
//获取应用实例
const app = getApp()
Page({
  data: {
    width: app.systemInfo.windowWidth,
    height: app.systemInfo.windowHeight,
    banner: ['http://i.dxlfile.com/adm/material/2016_12_12/20161212135600242250.jpg',
      'http://i.dxlfile.com/adm/material/2017_01_04/2017010411165785666.jpg',
      'http://i.dxlfile.com/adm/material/2017_01_04/20170104140739205869.jpg',
      'http://i.dxlfile.com/adm/material/2017_01_16/20170116171332214897.jpg'],
    functions: [
      {
        url: '../../images/i01.png',
        name: '婚礼策划',
        id: '01'
      },
      {
        url: '../../images/i02.png',
        name: '婚纱摄影',
        id: '02'
      },
      {
        url: '../../images/i03.png',
        name: '婚宴酒店',
        id: '03'
      },
      {
        url: '../../images/i04.png',
        name: '婚礼用车',
        id: '04'
      },
      {
        url: '../../images/i05.png',
        name: '婚礼用品',
        id: '05'
      },
      {
        url: '../../images/i06.png',
        name: '金银首饰',
        id: '06'
      },
    ],

    goods: [
      {
        url: 'http://p1.meituan.net/wedding/5c683d257d0a418c146308b455bb5b582651471.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '热烈如初',
        price: '13800',
        oldprice: '19800',
        sell: '5',
        address: '二环路东五段万达广场8单元2101(近成仁公交站)',
        km: '1.1km'
      },
      {
        url: 'http://p1.meituan.net/wedding/adf460e1e88714cb30e118387de0b09e3536225.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '全包好超值无敌到爆宇宙套餐',
        price: '8800',
        oldprice: '10800',
        sell: '20',
        address: '东大街芷泉段88号时代豪庭(香格里拉酒店)',
        km: '1.8km'
      },
      {
        url: 'http://p0.meituan.net/wedding/4972ddf9c2067c193f6408f006f818c02213163.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '林中奇缘',
        price: '15800',
        oldprice: '20800',
        sell: '15',
        address: '总府路46号1-4楼(盐市口红旗商场斜对面)',
        km: '2.4km'
      },
      {
        url: 'http://p1.meituan.net/wedding/8a40a46c24c3f812586853aa5d5cb56d3134895.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '清新云系婚礼经典款',
        price: '12900',
        oldprice: '15800',
        sell: '25',
        address: '天府新区益州大道588号益州国际写字楼10楼',
        km: '3.4km'
      },
      {
        url: 'http://p1.meituan.net/wedding/5c683d257d0a418c146308b455bb5b582651471.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '热烈如初',
        price: '13800',
        oldprice: '19800',
        sell: '5',
        address: '二环路东五段万达广场8单元2101(近成仁公交站)',
        km: '1.1km'
      },
      {
        url: 'http://p1.meituan.net/wedding/adf460e1e88714cb30e118387de0b09e3536225.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '全包好超值无敌到爆宇宙套餐',
        price: '8800',
        oldprice: '10800',
        sell: '20',
        address: '东大街芷泉段88号时代豪庭(香格里拉酒店)',
        km: '1.8km'
      },
      {
        url: 'http://p0.meituan.net/wedding/4972ddf9c2067c193f6408f006f818c02213163.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '林中奇缘',
        price: '15800',
        oldprice: '20800',
        sell: '15',
        address: '总府路46号1-4楼(盐市口红旗商场斜对面)',
        km: '2.4km'
      },
      {
        url: 'http://p1.meituan.net/wedding/8a40a46c24c3f812586853aa5d5cb56d3134895.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '清新云系婚礼经典款',
        price: '12900',
        oldprice: '15800',
        sell: '25',
        address: '天府新区益州大道588号益州国际写字楼10楼',
        km: '3.4km'
      }
    ]
  },

  onLoad: function () {

  },

  fucClick(event){
    const id = event.currentTarget.dataset.id;
    console.log(id);
    wx.navigateTo({
      url: '../storelist/storelist',
    })

  },
  goodDetail(event){
    wx.navigateTo({
      url: '../goods/goods',
    })
  }

})
