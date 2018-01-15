Page({
  data:{
    orderSates:["代付款","代配送","配送中","已完成","已取消"],
    current_id:0
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
  selectSection:function(event){
      let index = parseInt(event.currentTarget.dataset.index);
      this.setData({current_id:index})
  }
})