
var app = getApp();

Page({
  data : {
    addressList : {}
  },
  addAddress : function(e){
    var address_id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../add/add?address_id=' + address_id
    })
  },
  onLoad : function(options){
    this.uid = wx.getStorageSync('uid');
  },
  onShow : function(){
    this.loadAddressList();
  },
  loadAddressList : function(){
    var page = this;
    wx.request({
      url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Address/getAddress',
      data: {data:JSON.stringify({uid: this.uid,openid : null})},
      method: 'POST',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){
         page.setData({addressList:res.data.data})
         wx.setStorageSync('addressList', res.data.data)
      }
    })
  },
  setDefaultAddress : function(e){
    var detail = e.detail.value;
    var page = this;
    wx.request({
      url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Address/saveDefault',
      data: {data:JSON.stringify({uid: this.uid,openid : null,address_id : detail[0]})},
      method: 'POST',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){
         wx.redirectTo({
           url: '/pages/mine/address/list/list'
         })
      },
      fail: function(res) {
        // fail
      }
    })
  },
  delAddress : function(e){
    var address_id = e.currentTarget.dataset.id;
    var page = this;
    wx.showModal({
      title: '确定删除地址吗！',
      success: function(res) {
        if (res.confirm) {
          wx.request({
            url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Address/delAddress',
            data: {data:JSON.stringify({uid: page.uid,openid : null,address_id : address_id})},
            method: 'POST',
            header: {
                'content-type': 'application/x-www-form-urlencoded'
            },
            success: function(res){
              wx.redirectTo({
                url: '/pages/mine/address/list/list'
              })
            }
          })
        } 
      }
    })
  },
  backHome : function(e){
    app.backHome();
  }
})