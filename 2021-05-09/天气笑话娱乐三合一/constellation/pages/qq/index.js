Page({
  data:{
    qq:'',
    result:'请输入QQ号码查询',
    detail:'----'
  },
  onLoad:function(options){
    // 生命周期函数--监听页面加载
    
  },
  onShareAppMessage: function() {
    // 用户点击右上角分享
    return {
      title: 'title', // 分享标题
      desc: 'desc', // 分享描述
      path: 'path' // 分享路径
    }
  }, 
  loadData: function(qq) {
      var that = this;
      var key = "e32c2619ad9beec999e729afcfb3cce7";
      var url = "http://japi.juhe.cn/qqevaluate/qq";
      wx.request({
        url: url,
        data: {
            key: key,
            qq: qq
        },
        method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        // header: {}, // 设置请求的 header
        success: function(res){
          // success
          console.log(res);
          that.setData({
              qq: qq,
              result: res.data.result.data.conclusion,
              detail: res.data.result.data.analysis
          });
        }
      })
  },
  changeQQ: function(e) {
      var qq = e.detail.value;
      //console.log(qq);
      this.setData({qq: qq});
  },
  queryData: function(e) {
      var qq = this.data.qq;
      if(qq=='') {
        wx.showToast({title: 'QQ号码为空！', icon:"loading"});
      } else {
        this.loadData(qq);
      }
  }
})