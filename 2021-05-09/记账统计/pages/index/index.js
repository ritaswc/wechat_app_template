//index.js

const requestUrl = require('../../config').requestUrl
var pageIndex = 1;
var pageSize = 20;

var loadFlag = false;

var getDataList = function (that) {

  if (loadFlag == false) {
    loadFlag = true

    wx.request({
      url: requestUrl + 'wxIndex.ashx',

      data: {
        pageIndex: pageIndex,
        pageSize: pageSize
      },

      success: function (res) {

        var indexList = that.data.indexList;
        for (var i = 0; i < res.data.ChinaValue.length; i++) {
          indexList.push(res.data.ChinaValue[i]);
        }

        that.setData({
          indexList: indexList
        });
        pageIndex++
        loadFlag = false
      }

    })
  }
}

Page({
  data: {
    userInfo: {},
    indexList: [],
    scrollHeight: 0
  },

  //添加一笔新账单
  bindNewTap: function () {
    wx.navigateTo({
      url: '../new/new'
    })
  },

  //长按封面图 重新加载
  bindRefresh: function () {
    pageIndex = 1

    this.setData({
      indexList: []
    })

    getDataList(this)
  },

  //点击标签
  bindTagTap: function (e) {
    wx.navigateTo({
      url: '../result/result?KeyWord=' + e.currentTarget.dataset.id
    })
  },

  //图片预览
  bindShowImage: function (e) {
    wx.previewImage({
      urls: [e.target.dataset.url]
    })
  },

  //底部弹出菜单
  bindItemTap: function (e) {
    var that = this
    wx.showActionSheet({
      itemList: ['查看', '修改', '删除'],
      success: function (res) {
        if (res.tapIndex == 0) {
          wx.navigateTo({
            url: '../result/result?ID=' + e.currentTarget.dataset.id
          })
        }
        if (res.tapIndex == 1) {
          wx.navigateTo({
            url: '../new/new?ID=' + e.currentTarget.dataset.id
          })
        }
        if (res.tapIndex == 2) {
          wx.showModal({
            title: '提示',
            content: '该操作不可恢复，确认删除该账单？',
            success: function (res) {
              if (res.confirm) {
                wx.request({
                  url: requestUrl + 'wxDelete.ashx?ID=' + e.currentTarget.dataset.id,

                  success: function (res) {
                    if (res.data.ChinaValue[0].Result == 'True') {

                      wx.showToast({
                        title: '已删除',
                        mask: true,
                        duration: 500
                      })
                      pageIndex = 1

                      that.setData({
                        indexList: []
                      })

                      getDataList(that)
                    }
                  }

                })
              }
            }
          })
        }
      }
    })
  },

  onLoad: function () {
    var that = this

    //调用应用实例的方法获取全局数据
    var app = getApp()
    app.getUserInfo(function (userInfo) {
      //更新数据
      that.setData({
        userInfo: userInfo
      })
    })

    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          scrollHeight: res.screenHeight
        })
      }
    })

    getDataList(that)
  },

  //滑动到底部自动加载
  bindDownLoad: function () {
    var that = this
    getDataList(that)
  },

  onShow: function (options) {

    var that = this
    wx.getStorage({
      key: 'IsUpdate',
      success: function (res) {
        if (res.data) {
          pageIndex = 1

          that.setData({
            indexList: []
          })
          getDataList(that)
        }

        wx.setStorage({
          key: "IsUpdate",
          data: false
        })
      }
    })
  }
})