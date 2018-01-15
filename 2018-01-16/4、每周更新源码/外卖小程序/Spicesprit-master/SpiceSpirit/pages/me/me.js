Page({
  data:{
    userHeaderUrl:"../../images/useravatar.png",
    optionNamas:['我的订单','我的优惠','麻小e卡','收货地址','设置','关于我们'],
    optionIcons:['../../images/mine_1.png','../../images/mine_2.png','../../images/mine_3.png','../../images/mine_4.png','../../images/mine_5.png','../../images/mine_6.png',]
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    
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
    
  },
  makePhone:function(){
    wx.showModal({
      title:"提示",
      content:"你将使用运营商拨打电话4008166188",
      success:function(res){
        if (res.confirm) {
              wx.makePhoneCall({
                phoneNumber:'4008166188'
              })
          }
      }
    })
  },
  selectoption:function(event){
    console.log(event)
    let index = event.currentTarget.dataset.index
    switch(parseInt(index)){
      case 0:
            this.navigationTo("order",'')
            break;
      case 1:
            this.navigationTo("coupon",'')
            break;
      case 2:
            this.navigationTo("ecard",'')
            break;
      case 3:
            this.navigationTo("address",'')
            break;
      case 4:
            this.navigationTo("setting",'')
            break;
      case 5:
            this.navigationTo("aboutus",'')
            break;
      default:
            break;
    }
  },
  navigationTo:function(pageName,params){
    console.log('跳转' + '../' + pageName + '/' + pageName + params)
    wx.navigateTo({
      url: '../' + pageName + '/' + pageName + params,
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  }
})