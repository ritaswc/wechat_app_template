Page({
  data:{
    name:'',
    today:{},
    year:{}
  },
  onLoad:function(options){
    // 生命周期函数--监听页面加载
    var name = options.name;
    this.setData({name: name});
    this.loadData(name, "today");
    this.loadData(name, "year");
  },
  onShareAppMessage: function() {
    // 用户点击右上角分享
    return {
      title: 'title', // 分享标题
      desc: 'desc', // 分享描述
      path: 'path' // 分享路径
    }
  },

  loadData: function(name, type) {
      var that = this;
      var key = "057d56db14bcf4dc5d6f8f5736b0df95";
      var url = "http://web.juhe.cn:8080/constellation/getAll";
      wx.request({
        url: url,
        data: {
            consName: name,key:key,
            type:type
        },
        method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        // header: {}, // 设置请求的 header
        success: function(res){
          // success
          if("today"==type) {
            var data = res.data;
            that.setData({
              today: {
                datetime:data.datetime,
                all:data.all.replace("%", ""),
                color:data.color,
                health: data.health.replace("%", ""),
                love: data.love.replace("%", ""),
                money: data.money.replace("%", ""),
                number:data.number,
                QFriend: data.QFriend,
                summary: data.summary,
                work: data.work.replace("%", "")
              }
            });
          } else if("year"==type) {
            console.log(res);
            that.setData({year: res.data});
          }
        }
      })
  },
  goBack: function() {
    wx.navigateBack({
      delta: 1 // 回退前 delta(默认为1) 页面
    })
  }
})