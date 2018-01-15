// pages/index/index.js
var app = getApp()
Page({
  data: {
    select: '0',
    firstShow: null,
    searchToggle: false,
    error: false,
    state: null,
    bol: [
      {
        flag: false,
      },
      {
        flag: false,
      },
      {
        flag: false,
      },
      {
        flag: false,
      },
      {
        flag: false,
      },
      {
        flag: false,
      },
      {
        flag: false,
      },
    ]
  },
  onLoad: function () {
    var self = this;
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 10000
    })
    var list = "Citylist";
    wx.request({
      url: 'https://www.youyuwei.com/apiweb/city?list=' + list + '',
      method: 'GET',
      header: {
        'Accept': 'application/json'
      },
      success: function (res) {
        wx.hideToast();
        self.setData({
          firstShow: res.data.data.list[0].citylist,
          state: res.data.data.list
        })
      },
    })
  },
  showList: function (e) {
    var id = e.currentTarget.id;
    var mark = this.data.mark;
    var list = this.data.state;
    var bol = this.data.bol;
    if (id != "中国") {
      for (var i = 0; i < list.length; i++) {
        if (list[i].statename == id) {
          bol[i].flag = !bol[i].flag
        } else {
          bol[i].flag = false
        }
      }
      this.setData({
        bol: bol
      })
    } else {
      //点击切换回默认样式
      this.setData({
        firstShow: list[0].citylist,
        select: "0",
        countryname: ""
      })
    }
  },
  //改变右侧城市列表
  changeContent: function (e) {
    var self = this;
    var name = e.currentTarget.dataset.name;
    var arr = this.data.state;
    for (var i = 1; i < arr.length; i++) {
      var countrylist = arr[i].countrylist
      for (var j = 0; j < countrylist.length; j++) {
        if (countrylist[j].countryname == name) {
          var citylist = countrylist[j].citylist
          self.setData({
            firstShow: citylist,
            countryname: name,
            select: "10"
          })
        }
      }
    }
  },
  oninput: function (e) {
    var that = this;
    var val = e.detail.value
    if (val != "") {
      wx.request({
        url: 'https://www.youyuwei.com/apiweb/xcxsearch?keyword=' + val + '',
        method: 'GET',
        success: function (res) {
          var content = res.data.data.content;
          if (content == undefined) {
            var arr = res.data.data.list
            var name = arr[0].name;
            that.setData({
              searchToggle: true,
              about: arr,
              error: false,
              name: name
            })
          } else {
            that.setData({
              not: content,
              error: true
            })
          }
        },
      })
    } else {
      that.setData({
        searchToggle: false,
      })
    }
  }
})