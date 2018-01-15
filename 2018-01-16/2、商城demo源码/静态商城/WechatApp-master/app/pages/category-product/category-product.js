import request from "../../lib/request";
const app = getApp();
import serviceData from '../../data/config';

Page({
    data : {
        products:[],
        currentPage:1,
        perPage : 5
    },
    onLoad(option){
        var categoryId = option.id;
        var pageData = new Object();
        pageData.page = this.data.currentPage;
        pageData.per_page = this.data.perPage;
        //request({path:'/categories/' + categoryId + '/products', data: pageData})
        //.then(({data:products}) => this.setData({products}));
        this.setData({products: serviceData.categoryData});
        /*wx.setNavigationBarTitle({
          title: option.title,
          success: function(res) {
            // success
          }
        })*/
    },
    navigateToProduct(event) {
        var productId = event.currentTarget.dataset.goodsId;
        wx.navigateTo({
        url: '../products/products?id=' + productId
        });
    },
    lower : function(option){
        var categoryId = option.id;
        console.log('lower more products data');
        wx.showNavigationBarLoading();
        var that = this;
        setTimeout(()=>{
            wx.hideNavigationBarLoading();
            var nextPageData = new Object();
            nextPageData.per_page = this.data.perPage;
            nextPageData.page = this.data.currentPage +1;
            var products = serviceData.categoryData;
            this.setData({currentPage:++this.data.currentPage});
            this.setData({products:this.data.products.concat(products)});//concat 拼接在一起

      }, 1000);
  },
});