var base = getApp();
var jzData = require('../../utils/jzData.js');
var preview=require('../../utils/preview.js');
Page({
    data: {
        brand: 0,
        loaded: false,
        cartNum: 0,
    },
    onLoad: function (e) {
        var brand = e && e.brand ? e.brand : 0;
        this.setData({ brand: brand });
        var _this = this;
        if (brand == 0) {//经典系列
            var key = e.pname || "极地牛乳";
            var obj = base.cake.getByName(key);
            if (obj) {
                _this.setData({ loaded: true });
                this.initCake(obj);
            } else {
                base.get({ c: "Product", m: "GetCakeByName", City: "上海", ProName: key }, function (d) {
                    _this.setData({ loaded: true });
                    var data = d.data;
                    if (data.Status == "ok") {
                        _this.initCake(data.Tag);
                    }
                });
            }
        } else {//吉致系列
            var obj = jzData.data[e.pname];
            this.initCake(obj);
            _this.setData({ loaded: true });
        }
    },
    initCake: function (d) {
        var _this = this;
        wx.setNavigationBarTitle({ title: d.Name });
        this.setData({
            imgMinList: (function () {
                var _list = [];
                if (_this.data.brand == 0) {
                    for (var i = 1; i <= 4; i++) {
                        _list.push(base.path.res + "images-2/classical-detail/" + d.Name + "/w_400/" + d.Name + "-" + i + ".jpg");
                    }
                } else {
                    _list.push(base.path.res + "images/ksk/item/w_400/" + d.Name + ".jpg");
                }
                return _list;
            })(),
            name: d.Name,
            num: 1,
            des: d.Means,
            resource: d.Resourse,
            fresh: d.KeepFresh,
            current: {
                size: d.CakeType[0].Size,
                price: d.CakeType[0].CurrentPrice,
                supplyno: d.CakeType[0].SupplyNo,
                des: d.CakeType[0].PackingList
            },
            CakeType: d.CakeType
        });
    },
    onShow: function (e) {
        this.setData({ cartNum: base.cart.getNum() });
    },
    previewImg: function (e) {
        preview.show(this.data.name,this.data.brand,e.currentTarget.dataset.index)
    },
    changeCurrent: function (e) {
        var s = e.currentTarget.dataset.size;
        var p = e.currentTarget.dataset.price;
        var sno = e.currentTarget.dataset.supplyno;
        if (s && p && this.data.current.size != s) {
            this.setData({ "current.size": s, "current.price": p, "current.supplyno": sno })
        }
    },
    addCart: function () {
        var _this = this;
        if (base.cart.add({
            supplyno: this.data.current.supplyno,
            name: this.data.name,
            size: this.data.current.size,
            price: this.data.current.price,
            num: this.data.num,
            brand:this.data.brand
        })) {
            this.setData({ cartNum: base.cart.getNum() })
            base.modal({
                title: '加入成功！',
                content: "跳转到购物车或留在当前页",
                showCancel: true,
                cancelText: "留在此页",
                confirmText: "去购物车",
                success: function (res) {
                    if (res.confirm) {
                        _this.goc();
                    }
                }
            })
            // base.toast({
            //     title: '加入成功',
            //     icon: 'success',
            //     duration: 1500
            // })
        }
    },
    goCart: function () {
        if (!base.cart.exist(this.data.current.supplyno)) {
            base.cart.add({
                supplyno: this.data.current.supplyno,
                name: this.data.name,
                size: this.data.current.size,
                price: this.data.current.price,
                num: this.data.num
            })
        }
        this.goc();
    },
    goc: function () {
        var _this = this;
        base.cart.ref = "../cakeDetail/cakeDetail?pname=" + _this.data.name + "&brand=" + _this.data.brand;
        wx.switchTab({
            url: "../cart/cart"
        })
    },
    _go: function () {
        var _this = this;
        wx.navigateTo({
            url: "../buy/buy?type="+_this.data.brand+"&price=" + _this.data.current.price
        })
    }
});