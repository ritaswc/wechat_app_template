Page({
  data:{
      detailgood:{},
      onPullDownRefresh: function () {
          console.log('onPullDownRefresh')
      }
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    console.log(options);
    var id=options.id;
    var that=this;
        wx.request({
            url: 'http://m.htmlk.cn/json-test/lists.json',
            header: {
                'Content-Type': 'application/json'
            },
            success: function(res) {
               
               for(var i=0;i<res.data.length;i++){
                 //console.log(res.data[i].id);
                   if(res.data[i].id==id){
                   //  console.log(res.data[i]);
                      that.setData({
                        detailgood:res.data[i]
                      });
                      console.log(that.data.detailgood);
                    }
               }
             
               
            }
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
