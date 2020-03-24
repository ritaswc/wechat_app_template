if (typeof wx === 'undefined') var wx = getApp().core;
// pages/pt/list/list.js


Page({

  /**
   * 页面的初始数据
   */
  data: {
        cid:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) { getApp().page.onLoad(this, options);
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function (options) { getApp().page.onReady(this);
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function (e) {
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
  
  },
  lower:function (e){
  }
})