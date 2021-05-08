// pages/auxStore/auxGoods/auxGoods.js
var app=getApp();
Page({
  data:{
    mask_background:false,
    mask_search:false,
    searchMessage:'',
    newSearch:'取消',
    currIndex:0,
    theCity:'1',//城市选择
    cityName:'上海',//城市姓名
    brand_id:'',//商品品牌ID列表
    cataList:[
     
    ],
    cataItems:[

    ]
  },
  cityEvent:function(){
       wx.navigateTo({
         url: '../cityChoose/cityChoose',
         success: function(res){
           // success
         }
       })
  },
  searchEvent:function(event){
      var that=this;
      this.setData({
          mask_background:true,
          mask_search:true
      })
  },
  selectIndex:function(event){
        var that=this;
        var index=parseInt(event.currentTarget.dataset.index);
        // console.log(index);
        var data=[];
        app.getCataList(1,function(success){
            var cataName=success.data.data;
            that.setData({
                cataItems:cataName[index].cat_children
            })
        })
        
        self=this;
        this.setData({
           currIndex:index
        })
  },
  //商品列表
  goodsEvent:function(e){
      var that=this;
      var area_id=that.data.theCity;
      var brand_id=e.currentTarget.dataset.brandid;
      // console.log(area_id,brand_id);
      wx.navigateTo({
        url: '../goods_item/goods_item?area_id='+area_id+'&brand_id='+JSON.stringify(brand_id),
        success: function(res){
          // success
        }
      })
  },
  //查询弹出层时间
  retrunEvent:function(event){
       var that=this;
        that.setData({
          mask_background:false,
          mask_search:false
      })
  },
  searchHandle:function(event){
       var that=this;
       var message=event.detail.value;
       if(!message.length){
         that.setData({
            newSearch:'取消'
         })
       }else{
           that.setData({
            newSearch:'搜索'
         })
       }
       that.setData({
           searchMessage:message
       })
  },
  messageEvent:function(event){
        var that=this;
        var message=that.data.searchMessage;
        console.log(message);
        if(message.length>0){
            
        }else{
          that.setData({
              mask_background:false,
              mask_search:false
          })
        }
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var that=this;
    var city=that.data.theCity;
    // console.log(city);
    var images=[
          {
           imgUrl:'cata_water.png'
          },
          {
            imgUrl:'cata_dian.png'
          },
          {
            imgUrl:'cata_ni.png'
          },
          {
            imgUrl:'cata_mu.png'
          },
          {
            imgUrl:'cata_oil.png'
          },
          {
            imgUrl:'cata_wujin.png'
          },
          {
            imgUrl:'cata_zhuanqu.png'
          }
    ];
    //获取辅材一级目录
    app.getCataList(city,function(success){
          var cataName=success.data.data;
          console.log(cataName);
          var data=[];
          // var data2=[];
          for(var i=0;i<cataName.length;i++){
              var  img=images[i].imgUrl;
              var  name=cataName[i].cat_name;
              var  id=cataName[i].cat_id;
              var obj={
                imgUrl:img,
                name:name,
                id:id,
                index:i
              }
              data.push(obj);
          }
          // console.log(cataName[0].cat_children);
          that.setData({
              cataList:data,
              cataItems:cataName[0].cat_children,     
          })
    })
  }
})