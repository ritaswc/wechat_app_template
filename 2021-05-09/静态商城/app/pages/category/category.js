import request from '../../lib/request';
import serviceData from '../../data/config';

Page({
  data: {
    categories: []
  },
  navigateToCategoryProduct(event) {
    var categoryId = event.currentTarget.dataset.cateId;
    wx.navigateTo({
      url: '../category-product/category-product?id=' + categoryId,
    })
  },
  onLoad() {
      this.setData({ categories: serviceData.cateData});
  }
});
