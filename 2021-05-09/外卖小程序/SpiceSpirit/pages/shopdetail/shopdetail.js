Page({
  data:{
    icons:["../../images/index_small.png","../../images/me_sel.png","../../images/buycar_small.png"],
    titles:["首页","我的","购物车"]
  },
  onLoad:function(options){
    // 生命周期函数--监听页面加载
    console.log("options" + options)
    this.setData({good:options})
  },
  onReady:function(){
    // 生命周期函数--监听页面初次渲染完成
    
  },
  onShow:function(){
    // 生命周期函数--监听页面显示
    
  },
  onHide:function(){
    // 生命周期函数--监听页面隐藏
    
  },
  onUnload:function(){
    // 生命周期函数--监听页面卸载
    
  },
  onPullDownRefresh: function() {
    // 页面相关事件处理函数--监听用户下拉动作
    
  },
  onReachBottom: function() {
    // 页面上拉触底事件的处理函数
    
  },
  onShareAppMessage: function() {
    // 用户点击右上角分享
    return {
      title: 'title', // 分享标题
      desc: 'desc', // 分享描述
      path: 'path' // 分享路径
    }
  },
  tabClick:function(event){
     let index = parseInt(event.currentTarget.dataset.index)
     if(index == 0){
       wx.navigateBack({
         delta: 1
       })
     }else if(index == 1){
       wx.switchTab({
         url: '../me/me'
       })
     }else{
       wx.navigateTo({
         url: '../buycar/buycar'
       })
     }
  }
})