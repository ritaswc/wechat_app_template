Page({
  data:{
    current_id:1,
    show_addcard:false,
    ecards:[
      {
        "price":500,
        "left":400,
        "date_validity":"2000-1-1",
        "id":1
      }
    ]
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
  selectCategory:function(event){
      let index = parseInt(event.currentTarget.dataset.index);
      this.setData({current_id:index})
  },
  addCard:function(){
    this.setData({show_addcard:true})
  },
  cancal_add:function(){
    this.setData({show_addcard:false})
  },
  option_tap:function(event){
    
  }
})