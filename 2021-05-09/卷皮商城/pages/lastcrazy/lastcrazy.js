// 新增start
var listData = require('../../utils/listData.js')
// 新增end

var app = getApp()
Page({
  data: {
    listInfo: {},
    test:""
  },
  onLoad: function () {
    console.log('onLoad')
    console.log(listData.formatListData())
    this.setData({
      listInfo: listData.formatListData().list,
      test: "hello"
    })   
  }
})
