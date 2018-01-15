// pages/result/result.js

const requestUrl = require('../../config').requestUrl
var pageIndex = 1;
var pageSize = 20;

var loadFlag = false;
var loadResult = true;
var abid = 0;
var keyWord = '';
var datetime = '';
var year = '';
var month = '';
var cid = '';

var getDataList = function (that) {

  if (loadFlag == false && loadResult == true) {
    loadFlag = true

    wx.request({
      url: requestUrl + 'wxIndex.ashx',

      data: {
        ID: abid,
        pageIndex: pageIndex,
        pageSize: pageSize,
        KeyWord: keyWord,
        DateTime: datetime,
        Year: year,
        Month: month,
        CID: cid
      },

      success: function (res) {
        if (typeof (res.data.ChinaValue) != 'undefined') {
          if (res.data.ChinaValue.length < pageSize) {
            loadResult = false
          }

          var indexList = that.data.indexList;
          for (var i = 0; i < res.data.ChinaValue.length; i++) {
            indexList.push(res.data.ChinaValue[i]);
          }

          that.setData({
            indexList: indexList
          })
          pageIndex++
          loadFlag = false

          if (that.data.indexList.length == 0) {
            that.setData({
              noResultHidden: false,
              bottomLineHidden: true
            })
          }
        }
      }
    })
  }
}

Page({
  data: {
    userInfo: {},
    indexList: [],
    scrollHeight: 0,
    noResultHidden: true,
    bottomLineHidden: false
  },

  bindTagTap: function (e) {
    wx.redirectTo({
      url: '../result/result?KeyWord=' + e.currentTarget.dataset.id
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

                      wx.setStorage({
                        key: "IsUpdate",
                        data: true
                      })

                      wx.switchTab({
                        url: '../index/index'
                      })
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

  //图片预览
  bindShowImage: function (e) {
    wx.previewImage({
      urls: [e.target.dataset.url]
    })
  },

  onLoad: function (options) {
    //读取参数
    abid = typeof (options.ID) == 'undefined' ? '' : options.ID
    keyWord = typeof (options.KeyWord) == 'undefined' ? '' : options.KeyWord
    datetime = typeof (options.DateTime) == 'undefined' ? '' : options.DateTime
    year = typeof (options.Year) == 'undefined' ? '' : options.Year
    month = typeof (options.Month) == 'undefined' ? '' : options.Month
    cid = typeof (options.CID) == 'undefined' ? '' : options.CID

    pageIndex = 1
    loadFlag = false
    loadResult = true
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

  bindDownLoad: function () {
    //   该方法绑定了页面滑动到底部的事件
    var that = this

    getDataList(that)
  }

})