var app = getApp();
// pages/user/shoucang.js
Page({
  data:{
    page:1,
    productData:[],
  },
  onLoad:function(options){
    this.loadProductData();
  },
  onShow:function(){
    // 页面显示
  },
  removeFavorites:function(e){
    var that = this;
    var ccId = e.currentTarget.dataset.favId;

    wx.showModal({
      title: '提示',
      content: '你确认移除吗',
      success: function(res) {

        res.confirm && wx.request({
          url: app.d.hostUrl + 'ztb/productZBT/RemoveCollectCategory',
          method:'post',
          data: {
            ccId: ccId,
          },
          header: {
            'Content-Type':  'application/x-www-form-urlencoded'
          },
          success: function (res) {
            //--init data
            var data = res.data;
            console.log(data);
            //todo
            if(data.result == 'ok'){
              that.data.productData.length =0;
              that.loadProductData();
            }
          },
        });

      }
    });
  },
  loadProductData:function(){
    var that = this;
    console.log(this.data);
    wx.request({
      url: app.d.hostUrl + '/ztb/productZBT/GetCollectCategoryList',
      method:'post',
      data: {
        userId: app.d.userId,
        pageindex: that.data.page,
        pagesize:100,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        console.log(res);
        //--init data
        var data = res.data.data;
        that.initProductData(data);

        that.setData({
          productData:that.data.productData.concat(data),
        });
        //endInitData
      },
    });
  },
  initProductData: function (data){
    for(var i=0; i<data.length; i++){
      //console.log(data[i]);
      var item = data[i];

      item.Price = item.Price/100;
      item.ImgUrl = app.d.hostImg + item.ImgUrl;

    }
  },
});