function time() {
    var t = Math.round(new Date().getTime() / 1e3);
    return parseInt(t);
}

function formatTime(t) {
    var e = t.getFullYear(), r = t.getMonth() + 1, n = t.getDate(), a = t.getHours(), o = t.getMinutes(), u = t.getSeconds();
    return [ e, r, n ].map(formatNumber).join("/") + " " + [ a, o, u ].map(formatNumber).join(":");
}

function formatData(t) {
    var e = t.getFullYear(), r = t.getMonth() + 1, n = t.getDate();
    t.getHours(), t.getMinutes(), t.getSeconds();
    return [ e, r, n ].map(formatNumber).join("-");
}

function formatNumber(t) {
    return (t = t.toString())[1] ? t : "0" + t;
}

module.exports = {
    formatTime: formatTime,
    formatData: formatData,
    scene_decode: function(t) {
        var e = (t + "").split(","), r = {};
        for (var n in e) {
            var a = e[n].split(":");
            0 < a.length && a[0] && (r[a[0]] = a[1] || null);
        }
        return r;
    },
    time: time,
    objectToUrlParams: function(t, e) {
        var r = "";
        for (var n in t) r += "&" + n + "=" + (e ? encodeURIComponent(t[n]) : t[n]);
        return r.substr(1);
    },
    inArray: function(e, t) {
        return t.some(function(t) {
            return e === t;
        });
    },
    min: function(t, e) {
        return t = parseFloat(t), (e = parseFloat(e)) < t ? e : t;
    },
    max: function(t, e) {
        return (t = parseFloat(t)) < (e = parseFloat(e)) ? e : t;
    }
};