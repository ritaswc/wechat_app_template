// pages/details/details.js
Page({
  data:{
    news:{},
    array:{},
		light:true
  },
	change:function(){
      this.data.light=!this.data.light;
			this.setData({light:this.data.light});
			console.log(this.data.light)
	}
  ,
  onLoad:function(options){
    // #页面初始化 options为页面跳转所带来的参数
    var array=getApp().array;
    this.setData({news:array})  
    console.log(array); 
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