
var common = {
    format: function () {
        var args = arguments;
        return args[0].replace(/{(\d+)}/g, function (m, num) {
            num = +num + 1;
            return typeof args[num] != 'undefined'
                ? args[num]
                : m;
        });
    },
    dateFormat: function (fmt, date) {
        if (!date) {
            date = new Date();
        }
        else {
            date = new Date(date);
        }
        var o = {
            "M+": date.getMonth() + 1, //月份 
            "d+": date.getDate(), //日 
            "h+": date.getHours(), //小时 
            "m+": date.getMinutes(), //分 
            "s+": date.getSeconds(), //秒 
            "q+": Math.floor((date.getMonth() + 3) / 3), //季度 
            "S": date.getMilliseconds() //毫秒 
        };
        if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var k in o)
            if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    },
    JsonDateToDateString: function (date, fmt) {
        var date = new Date(parseInt(date.slice(6, -2)));
        var fmt = fmt || "yyyy-MM-dd";
        return common.dateFormat(fmt, date);
    },
    JsonDateToDateTimeString: function (date) {
        var date = new Date(parseInt(date.slice(6, -2)));
        return common.dateFormat("yyyy-MM-dd hh:mm:ss", date);
    },
    addDate: function (date, days) {//date当前日期  days 加减天数  正数为加 负数为减
        var d = new Date(date);
        d.setDate(d.getDate() + days);
        var month = d.getMonth() + 1;
        var day = d.getDate();
        if (month < 10) {
            month = "0" + month;
        }
        if (day < 10) {
            day = "0" + day;
        }
        var val = d.getFullYear() + "-" + month + "-" + day;
        return val;
    }
}


module.exports =
    {
        JsonDateToDateString: common.JsonDateToDateString,
        JsonDateToDateTimeString: common.JsonDateToDateTimeString,
        addDate:common.addDate
    }