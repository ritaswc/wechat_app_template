module.exports = {
    upload: function (args) {
        var app = getApp();
        args = args || {};
        args.complete = args.complete || function () {
        };
        args.data = args.data || {};
        app.core.chooseImage({
            count: 1,
            success: function (e) {
                if (e.tempFiles && e.tempFiles.length > 0) {
                    var file = e.tempFiles[0];
                    upload(file);
                } else {
                    if (typeof args.error == 'function') {
                        args.error('请选择文件');
                    }
                    args.complete();
                }
            },
            fail: function (e) {
                if (typeof args.error == 'function') {
                    args.error('请选择文件');
                    args.complete();
                }
            },
        });

        function upload(file) {
            if (typeof args.start == 'function') {
                args.start(file);
            }
            app.core.uploadFile({
                url: args.url || app.api.default.upload_image,
                filePath: file.path,
                name: args.name || 'image',
                formData: args.data || {},
                success: function (e) {
                    if (e.statusCode == 200) {
                        if (typeof args.success == 'function') {
                            e.data = JSON.parse(e.data);
                            args.success(e.data);
                        }
                    } else {
                        if (typeof args.error == 'function') {
                            args.error('上传错误：' + e.statusCode + '；' + e.data);
                        }
                    }
                    args.complete();

                },
                fail: function (e) {
                    if (typeof args.error == 'function') {
                        args.error(e.errMsg);
                    }
                    args.complete();

                },
            });
        }

    },
};