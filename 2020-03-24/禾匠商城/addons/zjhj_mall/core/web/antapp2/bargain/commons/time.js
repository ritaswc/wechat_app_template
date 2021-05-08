if (typeof wx === 'undefined') var wx = getApp().core;
var time = {
    page: null,
    time_list: {
        hour: '00',
        minute: '00',
        second: '00'
    },
    init: function(page) {
        var _page = this;
        this.page = page;
        page.setData({
            time_list: _page.time_list,
            intval: []
        });
        page.setTimeOver = function() {
            var page = _page.page;
            var setIntval = setInterval(function() {
                if (page.data.reset_time <= 0) {
                    clearInterval(setIntval);
                    return;
                }
                var reset_time = page.data.reset_time - 1;
                var time_list = page.setTimeList(reset_time);
                page.setData({
                    reset_time: reset_time,
                    time_list: time_list
                });
            }, 1000);
        };
    },
};
module.exports = time;