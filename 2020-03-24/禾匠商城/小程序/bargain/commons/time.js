var time = {
    page: null,
    time_list: {
        hour: "00",
        minute: "00",
        second: "00"
    },
    init: function(t) {
        var e = this;
        (this.page = t).setData({
            time_list: e.time_list,
            intval: []
        }), t.setTimeOver = function() {
            var i = e.page, s = setInterval(function() {
                if (i.data.reset_time <= 0) clearInterval(s); else {
                    var t = i.data.reset_time - 1, e = i.setTimeList(t);
                    i.setData({
                        reset_time: t,
                        time_list: e
                    });
                }
            }, 1e3);
        };
    }
};

module.exports = time;