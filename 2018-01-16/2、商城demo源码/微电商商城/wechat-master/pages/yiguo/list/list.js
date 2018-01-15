var app = getApp()
Page({
  data:{
    current: 0,
    listgoods:[],  
    swiper:{
      indicatorDots: false,
      autoplay: false,
      interval: 5000,
      duration: 1000
      }
    
  },
  onPullDownRefresh: function () {
    console.log('onPullDownRefresh')
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var that = this;
    wx.request({
            url: 'http://m.htmlk.cn/json-test/lists.json',
            header: {
                'Content-Type': 'application/json'
            },
            success: function(res) {
              //var odata=res.data;
              //console.log(odata);
              // for(var i=0;i<res.data.length-1;i++){
              //    //console.log(res.data[i].price);
              //   for(var j=0;j<res.data.length-i-1;j++){
              //          // console.log(parseInt(res.data[j].price)+"-----"+parseInt(res.data[j+1].price));
              //     if(parseInt(res.data[j].price)<parseInt(res.data[j+1].price)){
              //       var temp=res.data[j];
              //           res.data[j]=res.data[j+1];
              //           res.data[j+1]=temp;
              //     }
              //   }
              // }
               switch1(res.data);
               console.log(res.data);
               that.setData({
                   listgoods:res.data
               });
            }
        })
    function switch1(odata){
        for(var i=0;i<odata.length-1;i++){
                 //console.log(odata[i].price);
                for(var j=0;j<odata.length-i-1;j++){
                       // console.log(parseInt(odata[j].price)+"-----"+parseInt(odata[j+1].price));
                  if(parseInt(odata[j].price)<parseInt(odata[j+1].price)){
                    var temp=odata[j];
                        odata[j]=odata[j+1];
                        odata[j+1]=temp;
                  }
                }
        }
    }    
  },
  lookdetail:function(e){
    
    var lookid=e.currentTarget.dataset;
    console.log(e.currentTarget.dataset.id);
    wx.navigateTo({
      url:"/pages/yiguo/detail/detail?id="+lookid.id
    })
  },
  switchSlider:function(e){
    this.setData({
      current:e.target.dataset.index
    })
  },
  changeSlider:function(e){
    this.setData({
      current: e.detail.current
    })
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }

})
