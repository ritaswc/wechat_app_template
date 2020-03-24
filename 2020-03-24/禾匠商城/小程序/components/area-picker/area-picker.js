var area_picker = {
    page: null,
    data: null,
    old_value: [ 0, 0, 0 ],
    result: [ null, null, null ],
    init: function(a) {
        var l = this;
        l.page = a.page, l.data = a.data, l.page.showAreaPicker = function() {
            l.page.setData({
                area_picker_show: !0
            });
        }, l.page.hideAreaPicker = function() {
            l.page.setData({
                area_picker_show: !1
            });
        };
        var r = l.data[0].list || [], s = [];
        return 0 < r.length && (s = r[0].list || []), l.page.setData({
            area_picker_province_list: l.data,
            area_picker_city_list: r,
            area_picker_district_list: s
        }), l.result[0] = l.data[0] || null, l.data[0].list && (l.result[1] = l.data[0].list[0], 
        l.data[0].list[0].list && (l.result[2] = l.data[0].list[0].list[0])), l.page.areaPickerChange = function(a) {
            var e = a.detail.value[0], t = a.detail.value[1], i = a.detail.value[2];
            a.detail.value[0] != l.old_value[0] && (i = t = 0, r = l.data[e].list, s = r[0].list, 
            l.page.setData({
                area_picker_city_list: [],
                area_picker_district_list: []
            }), setTimeout(function() {
                l.page.setData({
                    area_picker_city_list: r,
                    area_picker_district_list: s
                });
            }, 0)), a.detail.value[1] != l.old_value[1] && (i = 0, s = l.data[e].list[t].list, 
            l.page.setData({
                area_picker_district_list: []
            }), setTimeout(function() {
                l.page.setData({
                    area_picker_district_list: s
                });
            }, 0)), a.detail.value[2], l.old_value[2], l.old_value = [ e, t, i ], l.result[0] = l.data[e], 
            l.result[1] = l.data[e].list[t], l.result[2] = l.data[e].list[t].list[i];
        }, l.page.areaPickerConfirm = function() {
            l.page.hideAreaPicker(), l.page.onAreaPickerConfirm && l.page.onAreaPickerConfirm(l.result);
        }, this;
    }
};

module.exports = area_picker;