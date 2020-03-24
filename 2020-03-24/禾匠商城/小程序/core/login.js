module.exports = function(e) {
    var t = this, r = t.page.currentPage;
    t.page.currentPageOptions;
    r && "pages/index/index" === r.route && "my" === t.platform || this.request({
        url: this.api.share.index,
        success: function(e) {
            0 == e.code && (t.page.setPhone(), t.trigger.run(t.trigger.events.login));
        }
    });
};