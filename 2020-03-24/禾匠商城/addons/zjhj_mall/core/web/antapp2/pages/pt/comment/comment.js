if (typeof wx === 'undefined') var wx = getApp().core;
// pages/pt/comment/comment.js


var is_no_more = false;
var is_loading = false;
var p = 2;
Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) { getApp().page.onLoad(this, options);
        is_no_more = false;
        is_loading = false;
        p = 2;
        var self = this;
        self.setData({
            gid: options.id,
        });
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.group.comment,
            data: {
                gid: options.id,
            },
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 1) {
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        showCancel: false,
                        success: function (e) {
                            if (e.confirm) {
                                getApp().core.navigateBack();
                            }
                        }
                    });
                }

                if (res.code == 0) {
                    if (res.data.comment.length==0){
                        getApp().core.showModal({
                            title: "提示",
                            content: '暂无评价',
                            showCancel: false,
                            success: function (e) {
                                if (e.confirm) {
                                    getApp().core.navigateBack();
                                }
                            }
                        });
                    }
                    self.setData({
                        comment: res.data.comment,
                    });
                }
                self.setData({
                    show_no_data_tip: (self.data.comment.length == 0),
                });

            }
        });

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function (options) { getApp().page.onReady(this);
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function (options) { getApp().page.onShow(this);
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function (options) { getApp().page.onHide(this);
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function (options) { getApp().page.onUnload(this);
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function (options) { getApp().page.onPullDownRefresh(this);
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function (options) { getApp().page.onReachBottom(this);
        var self = this;
        if (is_loading || is_no_more)
            return;
        is_loading = true;
        getApp().request({
            url: getApp().api.group.comment,
            data: {
                gid: self.data.gid,
                page: p,
            },
            success: function (res) {
                if (res.code == 0) {
                    var comment = self.data.comment.concat(res.data.comment);
                    self.setData({
                        comment: comment,
                    });
                    if (res.data.comment.length == 0) {
                        is_no_more = true;
                    }
                }
                p++;
            },
            complete: function () {
                is_loading = false;
            }
        });
  },
  /**
   * 图片放大
   */
  bigToImage: function (e) {
      var urls = this.data.comment[e.target.dataset.index]['pic_list'];
      getApp().core.previewImage({
          current: e.target.dataset.url, // 当前显示图片的http链接
          urls: urls // 需要预览的图片http链接列表
      })
  }
})