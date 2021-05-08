var base = getApp();
Page({
  data: {
    loaded: false,
    addresslist: []
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    var _this = this;
    this.getOrderList();
    wx.setNavigationBarTitle({
      title: '我的地址簿'
    })
  },
  getOrderList: function () {
    var _this = this;

    base.get({ c: "UserCenter", m: "GetAllAddress" }, function (d) {
      var dt = d.data;
      if (dt.Status == "ok") {
        var arr = [];
        for (var i = 0; i < dt.Tag.length; i++) {
          var obj = dt.Tag[i];
          if (i == 0) {
            obj.isDefault = true;
          }
          arr.push(obj);

        }
        _this.setData({
          loaded: true,
          addresslist: arr
        })

      }
    })
  },
  toDelete: function (e) {
    var _this = this;
    var id = e.currentTarget.dataset.aid;
    wx.showModal({
      title: '',
      content: '确定要删除该地址？',
      success: function (res) {
        if (res.confirm) {
          base.get({ c: "UserCenter", m: "DelAddressByID", id: id }, function (d) {
            var dt = d.data;
            if (dt.Status == "ok") {
              var arr = [];
              for (var i = 0; i < dt.Tag.length; i++) {
                var obj = dt.Tag[i];
                if (i == 0) {
                  obj.isDefault = true;
                }
                arr.push(obj);

              }
              _this.setData({
                loaded: true,
                addresslist: arr
              })

            }
          })
        }
      }
    })


  },
  toDefault: function (e) {
    var _this = this;
    var id = e.currentTarget.dataset.aid;
    base.get({ c: "UserCenter", m: "SetDefaultAddr", id: id }, function (d) {
      var dt = d.data;
      if (dt.Status == "ok") {
        var arr = [];
        for (var i = 0; i < dt.Tag.length; i++) {
          var obj = dt.Tag[i];
          if (i == 0) {
            obj.isDefault = true;
          }
          arr.push(obj);

        }
        _this.setData({
          loaded: true,
          addresslist: arr
        })

      }
    })
  }

})