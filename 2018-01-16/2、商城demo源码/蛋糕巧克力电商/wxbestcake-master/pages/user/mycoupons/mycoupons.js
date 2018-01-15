var base = getApp();

Page({
  data: {
    // text:"这是一个页面"
    actionSheetHidden: true,
    actionSheetItems: [
      { bindtap: 'Menu1', txt: '支付宝' },
      { bindtap: 'Menu2', txt: '微信' },
      { bindtap: 'Menu3', txt: '极致币' }
    ],
    menu: '请选择支付方式'
  },
  actionSheetTap: function () {
    this.setData({
      actionSheetHidden: !this.data.actionSheetHidden
    })
  },
  actionSheetbindchange: function () {
    this.setData({
      actionSheetHidden: !this.data.actionSheetHidden
    })
  },
  bindMenu1: function () {
    this.setData({
      menu: "支付宝",
      actionSheetHidden: !this.data.actionSheetHidden
    })
  },
  bindMenu2: function () {
    this.setData({
      menu: "微信",
      actionSheetHidden: !this.data.actionSheetHidden
    })
  },
  bindMenu3: function () {
    this.setData({
      menu: "极致币",
      actionSheetHidden: !this.data.actionSheetHidden
    });

  },
  submit: function () {
    var _this = this;

    var obj = {};
    obj.UserName = base.user.phone;
    obj.UserPhone = base.user.phone;
    obj.OrderSource = "PC|web";
    obj.Consignee = "小刘";
    obj.Cellphone = "13696672529";
    obj.City = "上海";
    obj.District = "普陀区";
    obj.Address = "普陀科技大厦";
    obj.DeliveryDate = "2017-3-1";
    obj.DeliveryTime = "10:00-11:00";
    obj.Payment = "支付宝";
    obj.Uid = base.user.userid;
    obj.Remarks = "";
    obj.TotalPrice = 168;
    obj.TotalPrice = obj.TotalPrice < 0 ? 0 : obj.TotalPrice;
    var oplArr = [{
      ProductName: "极地牛乳",
      Price: 188,
      Size: "1.2磅",
      Num: 1,
      CakeNo: 0,
      OType: 0,
      IType: 0,
      SupplyNo: "KSK-0001-1",
      //生日内容
      IsCutting: 0,
      CutNum: 0,
      BrandCandleType: 0,
      Remarks: '',
      Premark: null,//生产备注
    }
    ];
    var oal = [];
    base.post({
      c: "OrderCenter", m: "AddOrder", p: JSON.stringify(obj), proInfo: JSON.stringify(oplArr), oalInfo: JSON.stringify(oal)
      }, function(d) {
        console.log(d)
        if (d.Status == "ok") { }
      
    })

  }
})