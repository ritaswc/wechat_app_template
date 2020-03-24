if (typeof wx === 'undefined') var wx = getApp().core;
var area_picker = {
    page: null,
    data: null,
    old_value: [0, 0, 0],
    result: [null, null, null],
    init: function (args) {
        var picker = this;
        picker.page = args.page;
        picker.data = args.data;
        picker.page.showAreaPicker = function () {
            picker.page.setData({
                area_picker_show: true,
            });
        };
        picker.page.hideAreaPicker = function () {
            picker.page.setData({
                area_picker_show: false,
            });
        };

        var city_list = picker.data[0].list || [];
        var district_list = [];
        if (city_list.length > 0)
            district_list = city_list[0].list || [];

        picker.page.setData({
            area_picker_province_list: picker.data,
            area_picker_city_list: city_list,
            area_picker_district_list: district_list,
        });

        picker.result[0] = picker.data[0] || null;
        if (picker.data[0].list) {
            picker.result[1] = picker.data[0].list[0];
            if (picker.data[0].list[0].list)
                picker.result[2] = picker.data[0].list[0].list[0];
        }

        picker.page.areaPickerChange = function (e) {
            var province_index = e.detail.value[0];
            var city_index = e.detail.value[1];
            var district_index = e.detail.value[2];
            if (e.detail.value[0] != picker.old_value[0]) {//省份改变
                city_index = 0;
                district_index = 0;
                city_list = picker.data[province_index].list;
                district_list = city_list[0].list;

                picker.page.setData({
                    area_picker_city_list: [],
                    area_picker_district_list: [],
                });
                setTimeout(function () {
                    picker.page.setData({
                        area_picker_city_list: city_list,
                        area_picker_district_list: district_list,
                    });
                }, 0);

            }
            if (e.detail.value[1] != picker.old_value[1]) {//城市改变
                district_index = 0;
                district_list = picker.data[province_index].list[city_index].list;
                picker.page.setData({
                    area_picker_district_list: [],
                });
                setTimeout(function () {
                    picker.page.setData({
                        area_picker_district_list: district_list,
                    });
                }, 0);
            }
            if (e.detail.value[2] != picker.old_value[2]) {//区改变
            }
            picker.old_value = [province_index, city_index, district_index];
            picker.result[0] = picker.data[province_index];
            picker.result[1] = picker.data[province_index].list[city_index];
            picker.result[2] = picker.data[province_index].list[city_index].list[district_index];
        };


        picker.page.areaPickerConfirm = function () {
            picker.page.hideAreaPicker();
            if (picker.page.onAreaPickerConfirm)
                picker.page.onAreaPickerConfirm(picker.result);
        };
        return this;
    },
};
module.exports = area_picker;