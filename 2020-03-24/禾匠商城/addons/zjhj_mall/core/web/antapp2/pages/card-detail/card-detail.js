if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
      
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
      getApp().page.onLoad(this, options);

      var self = this;
      var user_card_id = options['user_card_id'];
      if(!user_card_id) return;

      getApp().core.showLoading({
          title: "加载中",
      });

      getApp().request({
          url: getApp().api.user.card_detail,
          data: {
              user_card_id: user_card_id,
          },
          success: function (res) {
              if (res.code == 0) {
                  if(res.data.list['is_use']===0){
                     self.getQrcode(user_card_id);
                  }
                  self.setData({
                      use: res.data.list['is_use'],
                      list: res.data.list,
                  });
              }
          },
          complete: function () {
              getApp().core.hideLoading();
          }
      });
    },

    getQrcode: function (user_card_id) {
        var self = this;
        getApp().request({
            url: getApp().api.user.card_qrcode,
            data: {
                user_card_id: user_card_id
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        qrcode: res.data.url
                    });
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                    })
                }
            },
        });
    },

    goodsQrcodeClick: function (e) {
        var src = e.currentTarget.dataset.src;
        getApp().core.previewImage({
            urls: [src],
        });
    },

})