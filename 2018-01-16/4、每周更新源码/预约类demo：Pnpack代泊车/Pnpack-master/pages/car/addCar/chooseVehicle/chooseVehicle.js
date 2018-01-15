
Page({
  data: {
    successdata:"",
    vehicle: "未选择",
    province: "京",
 


  },
  onLoad:function(){
    var that=this;
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/cars/all-card-area-list',
      header: {//请求头
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "post",//get为默认方法/POST
      success: function (res) {
        
        that.setData({
          successdata: res.data.data,
      

        })
      }
    })

  },
  radioChange: function (e) {
    this.setData({
      province: e.detail.value,
    })
  },
  formSubmit: function (e) {
 if(e.detail.value.input==''){
  wx.showToast({
  title: '请将车牌号填写完整',
  icon: 'fail',
  duration: 1000
})

 }else{
  wx.navigateBack();
     var pages = getCurrentPages();
      var currPage = pages[pages.length - 1];   //当前页面
      var prevPage = pages[pages.length - 2];  //上一个页面
      prevPage.setData({
      vehicle:e.detail.value.radioValue+e.detail.value.input,
     

      })
   

 }
   
  }
})