import Promise from '../../lib/promiseEs6Fix';
import request from '../../lib/request';
import resource from '../../lib/resource';
import serviceData from '../../data/config';

function homeSay(name) {
  console.log(`Hello ${name} !`);
}
module.exports.hSay = homeSay;
var app = getApp();
Page({
  data: {
    shop_id: app.globalData.shop_id,
    shop_info:[],
    banners: [],
    activities: [],
    features: [],
    currentPage: 1,
  },
  navigateToProduct(event) {
    var productId = event.currentTarget.dataset.goodsId;
    wx.navigateTo({
      url: '../products/products?id=' + productId
    });
  },
  navigateToActivity(event) {
    var activityType = event.currentTarget.dataset.activityType;
    var activityId = event.currentTarget.dataset.activityId;
    var activityTitle = event.currentTarget.dataset.activityTitle;
    var activityUrl;
    switch (activityType) {
      case 1:
        activityUrl = "../category-product/category-product?id=" + activityId + '&title=' + activityTitle;
        break;
      case 2:
        activityUrl = "../products/products?id=" + activityId;
        break;
      case 3:
        activityUrl = event.currentTarget.dataset.activityUrl;
        break;
      default:
        break;
    }
    console.log(activityUrl);
    wx.navigateTo({
      url: activityUrl
    });
  },
  onLoad() {
    this.setData({ shop_info: app.globalData.shopInfo });
    this.setData({banners: serviceData.bannerData, activities:serviceData.activityData, features:serviceData.featureData});
    //发起多个请求
    // const requests = ['/banners?shop_id=2' + this.data.shop_id,
    //     '/activities',
    //     '/features?shop_id=' + this.data.shop_id + '&per_page=5'
    //   ]
    //   .map(path => (
    //     request({ path })
    //     .then(response => response.data)
    //     .catch(() => [])
    //   ));
    // Promise.all(requests).then(([banners, activities, features]) => {
    //   features.forEach(item => {
    //     item.goods_price = item.goods_price.toFixed(2);
    //   })
    //   this.setData({ banners, activities, features });
    //   console.log(23);
    // },([banners, activities, features]) => {
    //   console.log([banners, activities, features]);
    // },([banners, activities, features]) => {
    //   console.log([banners, activities, features]);
    // });
  },
  lower: function() {
    console.log('lower more features data');
    wx.showNavigationBarLoading();
    var that = this;
    setTimeout(() => {
      wx.hideNavigationBarLoading();
      var nextPageData = new Object();
      nextPageData.per_page = 5;
      nextPageData.page = this.data.currentPage + 1;
      nextPageData.shop_id = this.data.shop_id;
      var features = serviceData.featureData;
      if (features.length != 0) {
        this.setData({ currentPage: ++this.data.currentPage });
        this.setData({ features: this.data.features.concat(features) }); //concat 拼接在一起
      }
      // request({ path: '/features', data: nextPageData }).then(({ data: features }) => {
      //   if (features.length != 0) {
      //     this.setData({ currentPage: ++this.data.currentPage });
      //     this.setData({ features: this.data.features.concat(features) }); //concat 拼接在一起
      //   }
      // });
    }, 1000);
  }
});
