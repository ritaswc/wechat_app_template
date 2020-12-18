Page({
  data:{
    // text:"这是一个页面"
    detail:{}
  },
  onLoad:function(options){
    var id = options.id;
    var t = this;
    wx.request({
      url: 'https://www.v2ex.com/api/topics/hot.json',
      success: function(res){
          var d;
        for(var i=0; i<res.data.length; i++){
            if(id == res.data[i].id){
                d = res.data[i];
            }
        }
        t.setData({
          detail: d
        });
        console.log(t.data.detail);
      }
    });
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