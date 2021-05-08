module.exports = {
    loading: (str) => {
        wx.showLoading({
            title: str
        });
    },
    loadingEnd: () => {
        wx.hideLoading();
    },
    errorDialog: (msg, callback) => {
        wx.showModal({
            title: '错误',
            content: msg,
            showCancel: false,
            success: function(res) {
                if (res.confirm) {
                    callback();
                }
            }
        })
    }
}