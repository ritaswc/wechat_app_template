// pages/cart/cart.js
var postData = require("../../data/post-data.js");
Page({
  data: {
    cartListShow: true,
    showModal: false,
    postList: postData.postList
  },
  onLoad: function (options) {
    //this.setData({
    // postList: postData.postList
    //});
    if (this.data.postList.length < 1) {
      this.setData({
        showModal: true
      });
    }
  },
  plus: function (e) {
    var that = this;
    var index = e.currentTarget.dataset.index;
    var num = that.data.postList[index].num;
    if (num > 1) {
      num--;
    } else {
      wx.showModal({
        title: '',
        content: '是否删除此菜品?',
        success: function (res) {
          if (res.confirm) {
            carts.splice(index, 1);
            that.setData({
              postList: carts
            });
            if (that.data.postList.length < 1) {
              that.setData({
                cartListShow: false,
                showModal: true
              });
            }
          } else if (res.cancel) {
            return;
          }
        }
      })
    }
    var carts = that.data.postList;
    carts[index].num = num;
    that.setData({
      postList: carts
    });
    //this.data.postList[index].num;
  },
  add: function (e) {
    var index = e.currentTarget.dataset.index;
    var num = this.data.postList[index].num;
    num++;
    var carts = this.data.postList;
    carts[index].num = num;
    this.setData({
      postList: carts
    });
  },
  delThisFood: function (e) {
    var that = this;
    var index = e.currentTarget.dataset.index;
    var carts = that.data.postList;
    wx.showModal({
      title: '',
      content: '是否删除此菜品?',
      success: function (res) {
        if (res.confirm) {
          carts.splice(index, 1);
          that.setData({
            postList: carts
          });
          if (that.data.postList.length < 1) {
            that.setData({
              cartListShow: false,
              showModal: true
            });
          }
        } else if (res.cancel) {
          return;
        }
      }
    })
  }
})