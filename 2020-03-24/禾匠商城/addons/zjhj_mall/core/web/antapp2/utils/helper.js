function time() {
    let time = Math.round(new Date().getTime() / 1000);
    return parseInt(time);
}

function formatTime(date) {
    var year = date.getFullYear()
    var month = date.getMonth() + 1
    var day = date.getDate()

    var hour = date.getHours()
    var minute = date.getMinutes()
    var second = date.getSeconds()


    return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}

function formatData(date) {
    var year = date.getFullYear()
    var month = date.getMonth() + 1
    var day = date.getDate()

    var hour = date.getHours()
    var minute = date.getMinutes()
    var second = date.getSeconds()


    return [year, month, day].map(formatNumber).join('-');
}

function formatNumber(n) {
    n = n.toString()
    return n[1] ? n : '0' + n
}

module.exports = {
    formatTime: formatTime,
    formatData: formatData,
    scene_decode: function(scene) {
        var _str = scene + "";
        var _str_list = _str.split(",");
        var res = {};
        for (var i in _str_list) {
            var _tmp_str = _str_list[i];
            var _tmp_str_list = _tmp_str.split(":");
            if (_tmp_str_list.length > 0 && _tmp_str_list[0]) {
                res[_tmp_str_list[0]] = _tmp_str_list[1] || null;
            }
        }
        return res;
    },
    time: time,

    objectToUrlParams: function(obj, urlencode) {
        let str = "";
        for (let key in obj) {
            str += "&" + key + "=" + (urlencode ? encodeURIComponent(obj[key]) : obj[key]);
        }
        return str.substr(1);
    },
    inArray: function(val, arr) {
        return arr.some(function(v) {
            return val === v;
        })
    },
    min: function(var1, var2) {
        var1 = parseFloat(var1);
        var2 = parseFloat(var2);
        return var1 > var2 ? var2 : var1;
    },

    max: function (var1, var2) {
        var1 = parseFloat(var1);
        var2 = parseFloat(var2);
        return var1 < var2 ? var2 : var1;
    },

};