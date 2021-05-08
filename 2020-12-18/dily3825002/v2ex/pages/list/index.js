Page({
  data: {
    list: []
  },
  onLoad: function(){
    this.getData();
  },
  getData: function(){
    var t = this;
    wx.request({
      url: 'https://www.v2ex.com/api/topics/hot.json',
      success: function(res){
        console.log(res);
        t.setData({
          list: t.data.list.concat(res.data.map(function (item) {
            return item;
          }))
        });
      }
    });
  },
  showDetail: function(e){
    var url = '../detail/index?id=' + e.currentTarget.id;
    wx.navigateTo({
      url: url
    })
    console.log(url);
  }
})