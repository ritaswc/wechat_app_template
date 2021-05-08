//index.js


import S_request from '../../utils/requestService.js';
import CONFIG from '../../config.js';
import collect from '../../utils/collect.js';
let app = getApp();
let [curPageNumber, curPageRequsetNumber] = [1, 1];//设置当前页面数量,请求数量

Page({
  data: {
    userInfo: {},
    swiperData : {
      "indicatorDots" : true,
      "duration" : 300,
      "autoplay" : true,
      "interval" : 3000,
      "imgMode" : "scaleToFill",
      "data" : []
    },
    goodsData: [],
    collect : {
      data : [],
      actionSheetHidden : true,
      createCollectName : ""
    },
    curPageNumber : 1,
    loading : { //页面loading
      hidden : false,
      msg : "加载中...",
      isViewHidden : true
    },
    toast : { //页面消息提示
      hidden : true,
      icon   : "clear",
      msg : ""
    }
  },
  //加载完成
  onLoad: function () {
    console.log('onLoad');
    this.setData({
      systemInfo : app.getSystemInfo()
    });
    S_request.index.getGoodsList(curPageNumber, (goodsData, swiperData) => {
        if(goodsData.statusCode == CONFIG.CODE.REQUESTERROR) {
          this.setData({
            "toast.hidden" : false,
            "toast.icon"   : "clear",
            "toast.msg" : "请求超时",
            "loading.hidden" : true
          });
          return;
        }
        this.setData({
          goodsData : goodsData,
          'swiperData.data' : swiperData
        });
      curPageNumber += 1;
      app.MLoading(this, curPageRequsetNumber);
    });

  },
  //跳转商品详情页
  showGoodsDetailPage : function(e){
    let data = e.currentTarget.dataset;
      wx.navigateTo({
        url : "/pages/goodsDetail/detail?id="+ data.id
      })
  },

  //展开收藏夹
  showCollect : function(e){
    collect.showCollect(this, e);
  },
  //关闭收藏夹
  closeCollect : function(e){
    collect.closeCollect(this, e);
  },
  //添加收藏夹
  createCollect : function(e){
    collect.createCollect(this, e);
  },
  //选择收藏夹收藏商品
  selectCollect : function(e){
    collect.selectCollect(this, e);
  },
  //加载更多商品
  loadMoreGoods : function(e){
    console.log(e);
    this.setData({
      "loading.hidden" : false
    })
    S_request.index.getGoodsList(curPageNumber, (res) => {
        this.setData({
          goodsData : [...this.data.goodsData, ...res],
          curPageNumber : this.data.curPageNumber + 1,
          "loading.hidden" : true
        });
      curPageNumber += 1;
    })
  },
  //请求超时提醒
  toastChange : function(){
    this.setData({
      "toast.hidden" : true
    });
  }
});
