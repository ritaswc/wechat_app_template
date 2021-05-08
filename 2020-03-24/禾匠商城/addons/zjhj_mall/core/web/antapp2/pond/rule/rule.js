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
      getApp().request({
          url: getApp().api.pond.setting,
          success: function (res) {
              if(res.code==0){
                  self.setData({
                      rule:res.data.rule
                  })
              }
          },
      });
  },
})