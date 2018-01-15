var base=getApp();
module.exports = {
    show: function (nm, brand,index) {
        if (brand == 0) {
            wx.previewImage({
                current: base.path.res + "images-2/classical-detail/" + encodeURI(nm) + "/" + encodeURI(nm) + "-" + index + ".jpg",
                urls: (function () {
                    var _list = [];
                    for (var i = 1; i <= 4; i++) {
                        _list.push(base.path.res + "images-2/classical-detail/" + encodeURI(nm) + "/" + encodeURI(nm) + "-" + i + ".jpg");
                    }
                    return _list;
                })()
            });
        } else {
            wx.previewImage({
                current: base.path.res + "images/ksk/item/" + encodeURI(nm) + ".jpg",
                urls: [base.path.res + "images/ksk/item/" + encodeURI(nm) + ".jpg"]
            });
        }
    }
}