
var base = getApp();
Page({
  data: {
    loaded: false,
    id: 0,
    mode: "add",
    consigee: "",
    phone: "",
    address: "",
    arrayCity: [],
    objectArrayCity: [],
    arrayDistrict: [],
    objectArrayDistrict: [],
    indexCity: 0,
    indexDistrict: 0,
    editArea: ""//修改专用初始化字段
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    var _this = this;
    _this.setData({
      mode: options.mod,
      id: options.id
    });
    _this.getCity(_this.preLoad);//先获取城市，城市获取成功再执行加载其他内容过程。

  },
  preLoad: function () {
    var _this = this;
    if (_this.data.mode == "edit") {
      base.get({ c: "UserCenter", m: "getAddressById", id: _this.data.id }, function (d) {
        var dt = d.data;
        if (dt.Status == "ok") {
          for (var i = 0; i < _this.data.objectArrayCity.length; i++) {
            if (_this.data.objectArrayCity[i].name == dt.Tag.city) {
              _this.setData({ indexCity: _this.data.objectArrayCity[i].id });
              break;
            }
          }
          _this.setData({
            loaded: true,
            consigee: dt.Tag.name,
            phone: dt.Tag.phone,
            address: dt.Tag.address,
            editArea: dt.Tag.area
          })
          _this.getAreaByCity(_this.data.arrayCity[_this.data.indexCity], _this.preAreaByEdit);
        }
      })
    }
    else {
      _this.getAreaByCity(_this.data.arrayCity[0]);
      _this.setData({
        loaded: true
      })
    }
  },
  preAreaByEdit: function () {//编辑状态初始化加载区域
    var _this = this;
    for (var i = 0; i < _this.data.objectArrayDistrict.length; i++) {
      if (_this.data.objectArrayDistrict[i].name == _this.data.editArea) {
        _this.setData({ indexDistrict: _this.data.objectArrayDistrict[i].id });
        break;
      }
    }
  },
  getCity: function (call) {//获取所有城市
    var _this = this;
    base.get({ c: "CityCenter", m: "GetCitys" }, function (d) {
      var dt = d.data;
      if (dt.Status == "ok") {
        var arr_objcity = [];
        var arr_city = [];
        for (var i = 0; i < dt.Tag.length; i++) {
          arr_city.push(dt.Tag[i].City);
          arr_objcity.push({ id: i, name: dt.Tag[i].City });
        }
        _this.setData({
          arrayCity: arr_city,
          objectArrayCity: arr_objcity
        })
        if (call) call();
      }
    })
  },
  getAreaByCity: function (city, call) {
    var _this = this;
    base.get({ c: "CityCenter", m: "GetAreaByCity", city: city }, function (d) {
      var dt = d.data;
      if (dt.Status == "ok") {
        var arr_objArea = [];
        var arr_Area = [];
        for (var i = 0; i < dt.Tag.length; i++) {
          arr_Area.push(dt.Tag[i].District);
          arr_objArea.push({ id: i, name: dt.Tag[i].District });
        }
        _this.setData({
          arrayDistrict: arr_Area,
          objectArrayDistrict: arr_objArea
        })
        if (call) call();
      }
    })
  },
  bindPickerChangeCity: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      indexCity: e.detail.value
    })
    this.getAreaByCity(this.data.arrayCity[e.detail.value]);
  },
  bindPickerChangeDistrict: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      indexDistrict: e.detail.value
    })
  },
  changeName: function (e) {
    this.setData({
      consigee: e.detail.value
    })
  },
  changePhone: function (e) {
    this.setData({
      phone: e.detail.value
    })
  },
  changeAddress: function (e) {
    this.setData({
      address: e.detail.value
    })
  },
  submit: function () {
    var _this = this;
    if (_this.valid()) {
      var addr = {
        city: _this.data.arrayCity[_this.data.indexCity],
        area: _this.data.arrayDistrict[_this.data.indexDistrict],
        address: _this.data.address,
        name: _this.data.consigee,
        phone: _this.data.phone,
        c: "UserCenter"
      };
      if (_this.data.mode == "edit") {
        addr.id = _this.data.id;
        addr.m = "UpdateAddress";
      }
      else {
        addr.m = "AddAddress";
      }
      base.get(addr, function (d) {
        var dt = d.data;
        if (dt.Status == "ok") {
          wx.redirectTo({
            url: "../../user/myaddress/myaddress"
          })
        }
        else {
          wx.showModal({
            showCancel: false,
            title: '',
            content: dt.Msg
          });

          console.log(dt.Msg);
        }
      })


    }

  },
  valid: function () {
    var _this = this;
    var err = "";
    if (!_this.data.consigee) {
      err = "请填写收货人姓名！";
      wx.showModal({
        showCancel: false,
        title: '',
        content: err
      })
      return false;
    }
    if (!_this.data.phone) {
      err = "请填写收货人手机号码！";
      wx.showModal({
        showCancel: false,
        title: '',
        content: err
      })
      return false;
    }
    if (!_this.phoneRegex(_this.data.phone)) {
      err = "手机号码格式不正确！";
      wx.showModal({
        showCancel: false,
        title: '',
        content: err
      })
      return false;
    }
    // if (_this.data.area) {
    //   v.focArea = false;
    //   return showMsg("请选择区域！", "focArea");
    // }
    if (!_this.data.address) {
      err = "请填写详细收货地址！";
      wx.showModal({
        showCancel: false,
        title: '',
        content: err
      })
      return false;
    }
    return true;

  },
  phoneRegex: function (val) {
    var regex = /^1[3|4|5|7|8][0-9]\d{8}$/;
    if (!regex.test(val)) {      
      return false;
    }
    return true;
  }
})
