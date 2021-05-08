//index.js
//获取应用实例
const app = getApp()
var utils = require('../../utils/utils.js');

// let toPagedetail =app.toPagedetail

Page({
  data: {
    refreshing: false,
    nomore: false,
    pagesource:'',
    imgList:[],
    // 总量
    articleList:[],
    pageNum:0,
    swiperCurrent:0,
    navList: [
      { icon: '/image/nav-icon/Diamond.png', events: 'ToRecommond', text: '推荐' },
      { icon: '/image/nav-icon/theme.png', events: 'toEntertain', text: '娱乐' },
       { icon: '/image/nav-icon/business.png', events: 'toTopline', text: '头条' },
      { icon: '/image/nav-icon/all.png', events: 'toTechnology', text: '科技' }, 
      { icon: '/image/nav-icon/food.png', events: 'toLeisure', text: '休闲' }

    ],
    upicon:'/image/nav-icon/goup.png'

  },
  //事件处理函数

  //页面加载时
  onLoad: function () {
    wx.showToast({ title: '玩命加载中', icon: 'loading', duration: 10000 });
    var url = 'https://recod.cn:8081/api/v1.0/swiper/';
    var that =this

    //获取轮播图相关信息
    utils.myRequest({
      url: url,
      methods: 'GET',
      success: function (result) {
        // wx.hideToast()
        var valueImgList=(result.data['pagesDict']['value'])
        that.setData({
          showitem: true,
          imgList: valueImgList
        })
      },
      fail: function () {
        that.setData({
          showitem: false
        })
      },
      complete: () => {
        // 加载完毕，关闭加载提示
        wx.hideToast()
      }
    })
    //列表页获取初始数据
    this.grabListPages(0)
    that.setData({
      pageNum: this.data['pageNum']+1
    })

  },
  refreshData: function () {
    var that=this
    this.setData({
      refreshing: true,
    })
    setTimeout(() => {
      this.grabListPages(that.data['pageNum']);
      var newArticleList = this.data.articleList
      this.setData({
        articleList: newArticleList,
        refreshing: false,
        nomore: false,
      });
    }, 2000);
  },
  loadmoreData: function () {
    this.setData({
      refreshing: true,
    })
    setTimeout(() => {
      if (this.data['pageNum'] > 10) {
        this.setData({
          nomore: true,
        })
      } else {
         this.grabListPages(this.data['pageNum']);
         
      }
      this.setData({
        refreshing: false,
        pageNum: this.data['pageNum']+1
      })
    }, 2000);
  },

// 获取列表页数据
  grabListPages: function () {
    const pageNum = this.data['pageNum']
    var url2 = 'https://recod.cn:8081/api/v2.0/list_pages/?pageNum=' + pageNum;
    
    var that = this
    //获取列表页相关信息
    utils.myRequest({
      url: url2,
      methods: 'GET',
      success: function (result) {
       
       
        var arr2 = that.data['articleList']; //从data获取当前datalist数组
       
        var arr1 = (result.data['pagesDict']['value'])
       
        arr2 = arr2.concat(arr1); //合并数组
       
        that.setData({
          showitem: true,
          articleList: arr2 //合并后更新datalist
        })
      },
      fail: function () {
        that.setData({
          showitem: false
        })
      },

      complete: () => {
        // 加载完毕，关闭加载提示
        wx.hideToast()

      }
    })
     },
  //轮播图改变事件
  swiperChange: function (e) {
   
    this.setData({
      swiperCurrent: e.detail.current
    })
  },
  //轮播图图片细节,传递pagesourceWithTitle
 
 //宫格导航
  ToRecommond: function () {
    wx.navigateTo({
      url: '/pages/index/recommond/recommond',
    })
  },
  toEntertain: function () {
    wx.navigateTo({
      url: '/pages/index/entertain/entertain',
    })
  },
  toTopline: function () {
    wx.navigateTo({
      url: '/pages/index/topline/topline',
    })
  },
  toTechnology: function () {
    wx.navigateTo({
      url: '/pages/index/technology/technology',
    })
  },
  toLeisure: function () {
    wx.navigateTo({
      url: '/pages/index/leisure/leisure',
    })
  },
  handlerGobackClick(delta) {
    utils.handlerClick.GobackClick(delta)
  },
  handlerGohomeClick() {
    utils.handlerClick.GohomeClick()
  },
  //去网页细节
  toPagedetail: function (e) {
    //wxml通过data-传参数

    var pageUrl = e.currentTarget.dataset['pageurl']
    // var pagesource= JSON.stringify(e.currentTarget.dataset['pagesourcewithtitle']);
    var pagesource = encodeURIComponent(e.currentTarget.dataset['pagesourcewithtitle']);
    //普通字符串

    //这里直接这样打印会报错的，为什么？
    // console.log(pagesourceWithTitle);
    wx.navigateTo({
      url: '/pages/index/detail/detail?pagesource=' + pagesource,
    })
  },

  topageup: function (e) {
   
    wx.pageScrollTo({
      scrollTop: 5,
      duration: 300
    })
  },

})
