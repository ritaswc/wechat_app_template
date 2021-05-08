// pages/auxStore/goods_item/goods_item.js
var app=getApp();
Page({
  data:{
    hov:'hovClass',
    navItem:[],
    goodsItem:[]
  },
  selectEvent:function(e){
      var that=this;
      var index=e.currentTarget.dataset.index;
      console.log(index);
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var that=this;
    var area_id=options.area_id;
    var brand_id=JSON.parse(options.brand_id);
    // console.log(brand_id);
    that.setData({
        navItem:brand_id
    })
    // console.log(area_id,brand_id);
    var data=[];
    for(var i=0;i<brand_id.length;i++){
        var id=brand_id[i].cat_id;
        var obj={
          id:id
        }
        app.getGoodsList(area_id,id,function(success){
           obj.goods=success.data.data;
            // that.setData({
            //      goodsItem:success.data.data
            // })
        })
        data.push(obj);
    }
    console.log(data);
   
  },
  globalData:{
      goodsItem:null
  }
})