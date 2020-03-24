var _showdown = require("./showdown.js"), _showdown2 = _interopRequireDefault(_showdown), _html2json = require("./html2json.js"), _html2json2 = _interopRequireDefault(_html2json);

function _interopRequireDefault(e) {
    return e && e.__esModule ? e : {
        default: e
    };
}

function _defineProperty(e, a, t) {
    return a in e ? Object.defineProperty(e, a, {
        value: t,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : e[a] = t, e;
}

var realWindowWidth = 0, realWindowHeight = 0;

function wxParse() {
    var e = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : "wxParseData", a = 1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : "html", t = 2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : '<div class="color:red;">数据不能为空</div>', i = arguments[3], r = arguments[4], n = i, o = {};
    if ("html" == a) o = _html2json2.default.html2json(t, e); else if ("md" == a || "markdown" == a) {
        var d = new _showdown2.default.Converter().makeHtml(t);
        o = _html2json2.default.html2json(d, e);
    }
    o.view = {}, void (o.view.imagePadding = 0) !== r && (o.view.imagePadding = r);
    var s = {};
    s[e] = o, n.setData(s), n.wxParseImgLoad = wxParseImgLoad, n.wxParseImgTap = wxParseImgTap, 
    n.wxParseVideoFull = function(e) {
        n.setData({
            wxParseVideoFullScreen: e.detail.fullScreen
        });
    };
}

function wxParseImgTap(e) {
    var a = e.target.dataset.src, t = e.target.dataset.from;
    void 0 !== t && 0 < t.length && wx.previewImage({
        current: a,
        urls: this.data[t].imageUrls
    });
}

function wxParseImgLoad(e) {
    var a = e.target.dataset.from, t = e.target.dataset.idx;
    void 0 !== a && 0 < a.length && calMoreImageInfo(e, t, this, a);
}

function calMoreImageInfo(e, a, t, i) {
    var r, n = t.data[i];
    if (n && 0 != n.images.length) {
        var o = n.images, d = wxAutoImageCal(e.detail.width, e.detail.height, t, i), s = o[a].index, l = "" + i, m = !0, h = !1, w = void 0;
        try {
            for (var g, u = s.split(".")[Symbol.iterator](); !(m = (g = u.next()).done); m = !0) {
                l += ".nodes[" + g.value + "]";
            }
        } catch (e) {
            h = !0, w = e;
        } finally {
            try {
                !m && u.return && u.return();
            } finally {
                if (h) throw w;
            }
        }
        var f = l + ".width", v = l + ".height";
        t.setData((_defineProperty(r = {}, f, d.imageWidth), _defineProperty(r, v, d.imageheight), 
        r));
    }
}

function wxAutoImageCal(e, a, t, i) {
    var r, n = 0, o = 0, d = {}, s = t.data[i].view.imagePadding;
    return realWindowHeight, (r = realWindowWidth - 2 * s) < e ? (o = (n = r) * a / e, 
    d.imageWidth = n, d.imageheight = o) : (d.imageWidth = e, d.imageheight = a), d;
}

function wxParseTemArray(e, a, t, i) {
    for (var r = [], n = i.data, o = null, d = 0; d < t; d++) {
        var s = n[a + d].nodes;
        r.push(s);
    }
    e = e || "wxParseTemArray", (o = JSON.parse('{"' + e + '":""}'))[e] = r, i.setData(o);
}

function emojisInit() {
    var e = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : "", a = 1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : "/wxParse/emojis/", t = arguments[2];
    _html2json2.default.emojisInit(e, a, t);
}

wx.getSystemInfo({
    success: function(e) {
        realWindowWidth = e.windowWidth, realWindowHeight = e.screenHeight;
    }
}), module.exports = {
    wxParse: wxParse,
    wxParseTemArray: wxParseTemArray,
    emojisInit: emojisInit
};