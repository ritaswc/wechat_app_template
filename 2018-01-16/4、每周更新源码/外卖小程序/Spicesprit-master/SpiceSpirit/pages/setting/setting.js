Page({
  data:{
    
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
  chooseOption:function(event){
    let index = parseInt(event.currentTarget.dataset.index) 
    if(index == 1){
      this.navigationTo('cphone','')
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