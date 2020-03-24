module.exports = {
    upload: function(o) {
        var t = getApp();
        function r(e) {
            "function" == typeof o.start && o.start(e), t.core.uploadFile({
                url: o.url || t.api.default.upload_image,
                filePath: e.path,
                name: o.name || "image",
                formData: o.data || {},
                success: function(e) {
                    200 == e.statusCode ? "function" == typeof o.success && (e.data = JSON.parse(e.data), 
                    o.success(e.data)) : "function" == typeof o.error && o.error("上传错误：" + e.statusCode + "；" + e.data), 
                    o.complete();
                },
                fail: function(e) {
                    "function" == typeof o.error && o.error(e.errMsg), o.complete();
                }
            });
        }
        (o = o || {}).complete = o.complete || function() {}, o.data = o.data || {}, t.core.chooseImage({
            count: 1,
            success: function(e) {
                if (e.tempFiles && 0 < e.tempFiles.length) {
                    var t = e.tempFiles[0];
                    r(t);
                } else "function" == typeof o.error && o.error("请选择文件"), o.complete();
            },
            fail: function(e) {
                "function" == typeof o.error && (o.error("请选择文件"), o.complete());
            }
        });
    }
};