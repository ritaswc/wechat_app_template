var base = getApp();
var common=require('../../utils/common.js');
Page({
    data: {
        arrTime: ['选择配送时间', '10:00-11:00', '11:00-12:00', '12:00-13:00', '13:00-14:00', '14:00-15:00', '15:00-16:00', '16:00-17:00', '17:00-18:00'],
        objectArrTime: [
            { id: 0, name: '选择配送时间' },
            { id: 1, name: '10:00-11:00' },
            { id: 2, name: '11:00-12:00' },
            { id: 3, name: '12:00-13:00' },
            { id: 4, name: '13:00-14:00' },
            { id: 5, name: '14:00-15:00' },
            { id: 6, name: '15:00-16:00' },
            { id: 7, name: '16:00-17:00' },
            { id: 8, name: '17:00-18:00' }],
        arrTimeIndex: 0,
        addr: "",
        addresslist: [],
        addrShow: false,
        scrollTop: 100,
        selectedID: -1,
        oinfo: {
            OrderSource: "all|web",
            Consignee: "",
            Cellphone: "",
            City: "",
            District: "",
            Address: "",
            DeliveryDate: "",
            DeliveryTime: "",
            Payment: "",
            Remarks: "",
            TotalPrice: 0
        },
        dateStart: "2017-01-01",
        dateEnd: "2017-01-01"
    },
    bindTimeChange: function (e) {
        var _this = this;
        if (e.detail.value > 0) {
            _this.setData({
                arrTimeIndex: e.detail.value,
                "oinfo.DeliveryTime": _this.data.arrTime[e.detail.value]
            });
        }
    },
    bindDateChange: function (e) {
        this.setData({
            "oinfo.DeliveryDate": e.detail.value
        })
    },
    myaddrChange: function () {//触摸选择地址
        this.setData({ addrShow: true });
    },
    myaddrCancel: function () {//点击地址簿中取消按钮
        this.setData({ addrShow: false });
    },
    closeaddr:function(){//触摸遮罩层关闭地址选项
          this.setData({ addrShow: false });
    },
    toSelect: function (e) {//选中地址
        var _this = this;
        var id = e.currentTarget.dataset.aid;
        _this.setData({ selectedID: id });
        for (var i = 0; i < _this.data.addresslist.length; i++) {
            if (_this.data.addresslist[i].id == id) {
                _this.setData({
                    "oinfo.City": _this.data.addresslist[i].city,
                    "oinfo.District": _this.data.addresslist[i].area,
                    "oinfo.Consignee": _this.data.addresslist[i].name,
                    "oinfo.Cellphone": _this.data.addresslist[i].phone,
                    "oinfo.Address": _this.data.addresslist[i].address,
                    addr: _this.data.addresslist[i].city + ' ' + _this.data.addresslist[i].area + ' ' + _this.data.addresslist[i].address,
                    addrShow: false
                });
                break;
            }
        }
    },
    onLoad: function (e) {
        var _this = this;
        var now=new Date();
        if (base.user.islogin()) {
            if (e.from && e.from == "cart") {
                var l = base.cart.getList();
                for (var i = 0; i < l.length; i++) {
                    l[i].img = base.path.res + 'images/ksk/item/w_127/' + l[i].name + '.jpg'

                }
                _this.setData({
                    plist: l,
                    dateStart: common.addDate(now,1),
                    dateEnd: common.addDate(now,90)
                });
            }
        }
        this.getAddressList();
        console.log(this.data.plist);
    },
    getAddressList: function () {
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
                    addresslist: arr
                })

            }
        })
    },
    onShow: function (e) {

    },

    getTotalPrice: function () {//应付金额
        var _this = this;
        var pl = _this.data.plist;//name: p.name, price: p.price, size: p.size, num: p.num, brand: p.brand,supplyno
        var alltotal = 0;
        for (var i = 0; i < pl.length; i++) {
            if (!isNaN(pl[i].price)) {
                alltotal += parseFloat(pl[i].price);
            }
        }
        this.setData({
            "oinfo.TotalPrice": alltotal
        });
    },
    getProductList: function () {
        var _this = this;
        var arr_pro = [];
        var pl = _this.data.plist;//name: p.name, price: p.price, size: p.size, num: p.num, brand: p.brand,supplyno
        for (var i = 0; i < pl.length; i++) {
            arr_pro.push({
                ProductName: pl[i].name,
                Price: pl[i].price,
                Size: pl[i].size,
                Num: pl[i].num,
                CakeNo: 0,
                OType: 0,
                IType: 0,
                SupplyNo: pl[i].supplyno,
                //生日内容
                IsCutting: 0,
                CutNum: 0,
                BrandCandleType: 0,
                Remarks: '',
                Premark: null,//生产备注
            });
        }
        return arr_pro;
    },
    valid: function () {
        var _this = this;
        var err = "";
        if (!_this.data.oinfo.Consignee) {
            err = "请选择收货人信息！";
            wx.showModal({
                showCancel: false,
                title: '',
                content: err
            })
            return false;
        }
        if (!_this.data.oinfo.DeliveryDate) {
            err = "请选择配送日期！";
            wx.showModal({
                showCancel: false,
                title: '',
                content: err
            })
            return false;
        }
        if (!_this.data.oinfo.DeliveryTime) {
            err = "请选择配送时间段！";
            wx.showModal({
                showCancel: false,
                title: '',
                content: err
            })
            return false;
        }
        return true;
    },
    submit: function () {
        var _this = this;
        if (_this.valid()) {
            _this.getTotalPrice();
            var obj = {};
            obj.UserName = base.user.phone;
            obj.UserPhone = base.user.phone;
            obj.OrderSource = _this.data.oinfo.OrderSource;
            obj.Consignee = _this.data.oinfo.Consignee;
            obj.Cellphone = _this.data.oinfo.Cellphone;
            obj.City = _this.data.oinfo.City;
            obj.District = _this.data.oinfo.District;
            obj.Address = _this.data.oinfo.Address;
            obj.DeliveryDate = _this.data.oinfo.DeliveryDate;
            obj.DeliveryTime = _this.data.oinfo.DeliveryTime;
            obj.Payment = _this.data.oinfo.Payment;
            obj.Uid = base.user.userid;
            obj.Remarks = _this.data.oinfo.Remarks;
            obj.TotalPrice = _this.data.oinfo.TotalPrice;
            obj.TotalPrice = obj.TotalPrice < 0 ? 0 : obj.TotalPrice;
            var oplArr = _this.getProductList();
            var oal = [];
            base.post({
                c: "OrderCenter", m: "AddOrder", p: JSON.stringify(obj), proInfo: JSON.stringify(oplArr), oalInfo: JSON.stringify(oal)
            }, function (d) {
                console.log(d)
                var dt = d.data;
                if (dt.Status == "ok") {
                    base.cart.clear();
                    wx.redirectTo({
                        url: "../payment/payment?oid=" + dt.Tag
                    })
                }

            })

        }
    }
})