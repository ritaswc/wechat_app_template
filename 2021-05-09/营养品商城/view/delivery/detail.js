const china = require("../../util/china.js");
const constant = require("../../util/constant.js");
const notification = require('../../util/notification.js');
const http = require('../../util/http.js');
const util = require('../../util/util.js');

Page({
    data: {
        color: constant.color,
        is_edit: false,
        is_dialog: false,
        delivery_id: '',
        delivery_name: '',
        delivery_phone: '',
        province_list: [],
        delivery_province: "",
        city_list: [],
        delivery_city: "",
        area_list: [],
        delivery_area: "",
        province_city_area: [0, 0, 0],
        delivery_street: '',
        delivery_is_default: false
    },
    onUnload: function () {

    },
    onLoad: function (option) {
        var is_edit = false;
        var delivery_id = '';
        var province_list = [];
        var city_list = [];
        var area_list = [];

        if (typeof (option.delivery_id) != 'undefined') {
            is_edit = true;

            delivery_id = option.delivery_id;

            this.handleFind(delivery_id);
        }

        for (var i = 0; i < china.children.length; i++) {
            province_list.push(china.children[i].name);
        }

        for (var i = 0; i < china.children[0].children.length; i++) {
            city_list.push(china.children[0].children[i].name);
        }

        for (var i = 0; i < china.children[0].children[0].children.length; i++) {
            area_list.push(china.children[0].children[0].children[i].name);
        }

        this.setData({
            is_edit: is_edit,
            delivery_id: delivery_id,
            province_list: province_list,
            city_list: city_list,
            area_list: area_list
        });
    },
    onReady: function () {

    },
    onShow: function () {

    },
    onHide: function () {

    },
    onPullDownRefresh: function () {

    },
    onReachBottom: function () {

    },
    onShareAppMessage: function () {

    },
    handlDialogOpen: function () {
        this.setData({
            is_dialog: true
        });
    },
    handlDialogCancel: function () {
        this.setData({
            is_dialog: false
        });
    },
    handlDialogOK: function () {
        var province_index = this.data.province_city_area[0];
        var city_index = this.data.province_city_area[1];
        var area_index = this.data.province_city_area[2];

        var delivery_province = china.children[province_index].name;
        var delivery_city = china.children[province_index].children[city_index].name;
        var delivery_area = china.children[province_index].children[city_index].children[area_index].name;

        this.setData({
            delivery_province: delivery_province,
            delivery_city: delivery_city,
            delivery_area: delivery_area,
            is_dialog: false
        });
    },
    handPickerChange: function (event) {
        if (this.data.is_dialog) {
            var province_city_area = event.detail.value;
            var province_index = province_city_area[0];
            var city_index = province_city_area[1];
            var area_index = province_city_area[2];

            if (this.data.province_city_area[0] != province_city_area[0]) {
                city_index = 0;
                area_index = 0;
            } else if (this.data.province_city_area[1] != province_city_area[1]) {
                area_index = 0;
            }

            var city_list = [];
            var area_list = [];

            for (var i = 0; i < china.children[province_index].children.length; i++) {
                city_list.push(china.children[province_index].children[i].name);
            }

            for (var i = 0; i < china.children[province_index].children[city_index].children.length; i++) {
                area_list.push(china.children[province_index].children[city_index].children[i].name);
            }

            this.setData({
                city_list: city_list,
                area_list: area_list,
                province_city_area: [province_index, city_index, area_index]
            });
        }
    },
    handleFind: function (delivery_id) {
        http.request({
            url: '/delivery/find',
            data: {
                delivery_id: delivery_id
            },
            success: function (data) {
                var province_index = 0;
                var city_index = 0;
                var area_index = 0;

                for (var i = 0; i < china.children.length; i++) {
                    if (china.children[i].name == data.delivery_province) {
                        province_index = i;

                        break;
                    }
                }

                for (var i = 0; i < china.children[province_index].children.length; i++) {
                    if (china.children[province_index].children[i].name == data.delivery_city) {
                        city_index = i;

                        break;
                    }
                }
                console.log(city_index);

                for (var i = 0; i < china.children[province_index].children[city_index].children.length; i++) {
                    if (china.children[province_index].children[city_index].children[i].name == data.delivery_area) {
                        area_index = i;

                        break;
                    }
                }

                this.setData({
                    delivery_name: data.delivery_name,
                    delivery_phone: data.delivery_phone,
                    delivery_province: data.delivery_province,
                    delivery_city: data.delivery_city,
                    delivery_area: data.delivery_area,
                    delivery_street: data.delivery_street,
                    delivery_is_default: data.delivery_is_default
                });
            }.bind(this)
        });
    },
    handleSubmit: function (event) {
        var delivery_name = event.detail.value.delivery_name;
        var delivery_phone = event.detail.value.delivery_phone;
        var delivery_street = event.detail.value.delivery_street;
        var delivery_is_default = event.detail.value.delivery_is_default;

        if (delivery_name == '') {
            util.showFailToast({
                title: '请输入收货人'
            });

            return;
        }

        if (delivery_phone == '') {
            util.showFailToast({
                title: '请输入手机号码'
            });

            return;
        } else {
            if (!util.isPhone(delivery_phone)) {
                util.showFailToast({
                    title: '手机格式不对'
                });

                return;
            }
        }

        if (this.data.area == '') {
            util.showFailToast({
                title: '请选择省市区'
            });

            return;
        }

        if (delivery_street == '') {
            util.showFailToast({
                title: '请输入详细地址'
            });

            return;
        }

        http.request({
            url: '/delivery/' + (this.data.is_edit ? 'update' : 'save'),
            data: {
                delivery_id: this.data.delivery_id,
                delivery_name: delivery_name,
                delivery_phone: delivery_phone,
                delivery_province: this.data.delivery_province,
                delivery_city: this.data.delivery_city,
                delivery_area: this.data.delivery_area,
                delivery_street: delivery_street,
                delivery_is_default: delivery_is_default
            },
            success: function (data) {
                notification.emit('notification_delivery_index_load', data);

                util.showSuccessToast({
                    title: '保存成功',
                    success: function () {
                        wx.navigateBack();
                    }
                });
            }.bind(this)
        });
    }
});
