var _loading_svg = '<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-hourglass" style="background:none"><defs><clipPath ng-attr-id="{{config.cpid}}" id="lds-hourglass-cpid-943b587b2a65e8"><rect x="0" y="28.2737" width="100" height="21.7263"><animate attributeName="y" calcMode="spline" values="0;50;0;0;0" keyTimes="0;0.4;0.5;0.9;1" dur="2.2" keySplines="0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7" begin="0s" repeatCount="indefinite"></animate><animate attributeName="height" calcMode="spline" values="50;0;0;50;50" keyTimes="0;0.4;0.5;0.9;1" dur="2.2" keySplines="0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7" begin="0s" repeatCount="indefinite"></animate></rect><rect x="0" y="71.7263" width="100" height="28.2737"><animate attributeName="y" calcMode="spline" values="100;50;50;50;50" keyTimes="0;0.4;0.5;0.9;1" dur="2.2" keySplines="0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7" begin="0s" repeatCount="indefinite"></animate><animate attributeName="height" calcMode="spline" values="0;50;50;0;0" keyTimes="0;0.4;0.5;0.9;1" dur="2.2" keySplines="0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7" begin="0s" repeatCount="indefinite"></animate></rect></clipPath></defs><g transform="translate(50,50)"><g transform="scale(0.9)"><g transform="translate(-50,-50)"><g transform="rotate(0)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;0 50 50;180 50 50;180 50 50;360 50 50" keyTimes="0;0.4;0.5;0.9;1" dur="2.2s" begin="0s" repeatCount="indefinite"></animateTransform><path ng-attr-clip-path="url(#{{config.cpid}})" ng-attr-fill="{{config.sand}}" d="M54.864,50L54.864,50c0-1.291,0.689-2.412,1.671-2.729c9.624-3.107,17.154-12.911,19.347-25.296 c0.681-3.844-1.698-7.475-4.791-7.475H28.908c-3.093,0-5.472,3.631-4.791,7.475c2.194,12.385,9.723,22.189,19.347,25.296 c0.982,0.317,1.671,1.438,1.671,2.729v0c0,1.291-0.689,2.412-1.671,2.729C33.84,55.836,26.311,65.64,24.117,78.025 c-0.681,3.844,1.698,7.475,4.791,7.475h42.184c3.093,0,5.472-3.631,4.791-7.475C73.689,65.64,66.16,55.836,56.536,52.729 C55.553,52.412,54.864,51.291,54.864,50z" clip-path="url(#lds-hourglass-cpid-943b587b2a65e8)" fill="#6b6b71"></path><path ng-attr-fill="{{config.frame}}" d="M81,81.5h-2.724l0.091-0.578c0.178-1.122,0.17-2.243-0.022-3.333C76.013,64.42,68.103,54.033,57.703,50.483l-0.339-0.116 v-0.715l0.339-0.135c10.399-3.552,18.31-13.938,20.642-27.107c0.192-1.089,0.2-2.211,0.022-3.333L78.276,18.5H81 c2.481,0,4.5-2.019,4.5-4.5S83.481,9.5,81,9.5H19c-2.481,0-4.5,2.019-4.5,4.5s2.019,4.5,4.5,4.5h2.724l-0.092,0.578 c-0.178,1.122-0.17,2.243,0.023,3.333c2.333,13.168,10.242,23.555,20.642,27.107l0.338,0.116v0.715l-0.338,0.135 c-10.4,3.551-18.31,13.938-20.642,27.106c-0.193,1.09-0.201,2.211-0.023,3.333l0.092,0.578H19c-2.481,0-4.5,2.019-4.5,4.5 s2.019,4.5,4.5,4.5h62c2.481,0,4.5-2.019,4.5-4.5S83.481,81.5,81,81.5z M73.14,81.191L73.012,81.5H26.988l-0.128-0.309 c-0.244-0.588-0.491-1.538-0.28-2.729c2.014-11.375,8.944-20.542,17.654-23.354c2.035-0.658,3.402-2.711,3.402-5.108 c0-2.398-1.368-4.451-3.403-5.108c-8.71-2.812-15.639-11.979-17.653-23.353c-0.211-1.191,0.036-2.143,0.281-2.731l0.128-0.308 h46.024l0.128,0.308c0.244,0.589,0.492,1.541,0.281,2.731c-2.015,11.375-8.944,20.541-17.654,23.353 c-2.035,0.658-3.402,2.71-3.402,5.108c0,2.397,1.368,4.45,3.403,5.108c8.71,2.812,15.64,11.979,17.653,23.354 C73.632,79.651,73.384,80.604,73.14,81.191z" fill="#bbbbc2"></path></g></g></g></g></svg>';
$.randomString = function (len) {
    len = len || 32;
    var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
    /****默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1****/
    var maxPos = $chars.length;
    var pwd = '';
    for (i = 0; i < len; i++) {
        pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
};

$.myConfirm = function (args) {
    args = args || {};
    var title = args.title || "提示";
    var content = args.content || "";
    var confirmText = args.confirmText || "确认";
    var cancelText = args.cancelText || "取消";
    var confirm = args.confirm || function () {
    };
    var cancel = args.cancel || function () {
    };
    var id = $.randomString();
    var html = '';
    html += '<div class="modal fade" data-backdrop="static" id="' + id + '">';
    html += '<div class="modal-dialog modal-sm" role="document">';
    html += '<div class="modal-content" style="box-shadow: 0 1px 5px rgba(0,0,0,.25)">';
    html += '<div class="modal-header">';
    html += '<h6 class="modal-title">' + title + '</h6>';
    html += '</div>';
    html += '<div class="modal-body">' + content + '</div>';
    html += '<div class="modal-footer">';
    html += '<button type="button" class="btn btn-sm btn-secondary alert-cancel-btn">' + cancelText + '</button>';
    html += '<button type="button" class="btn btn-sm btn-primary alert-confirm-btn">' + confirmText + '</button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $("body").append(html);
    $("#" + id).modal("show");
    $(document).on("click", "#" + id + " .alert-confirm-btn", function () {
        $("#" + id).modal("hide");
        confirm();
    });
    $(document).on("click", "#" + id + " .alert-cancel-btn", function () {
        $("#" + id).modal("hide");
        cancel();
    });
};

$.myPrompt = function (args) {
    args = args || {};
    var title = args.title || "提示";
    var content = args.content || "";
    var confirmText = args.confirmText || "确认";
    var cancelText = args.cancelText || "取消";
    var confirm = args.confirm || function () {
    };
    var cancel = args.cancel || function () {
    };
    var id = $.randomString();
    var html = '';
    html += '<div class="modal fade" data-backdrop="static" id="' + id + '">';
    html += '<div class="modal-dialog modal-sm" role="document">';
    html += '<div class="modal-content" style="box-shadow: 0 1px 5px rgba(0,0,0,.25)">';
    html += '<div class="modal-header">';
    html += '<h6 class="modal-title">' + title + '</h6>';
    html += '</div>';
    html += '<div class="modal-body">';
    html += '<div>' + content + '</div>';
    html += '<div class="mt-3"><input class="form-control form-control-sm"></div>';
    html += '</div>';
    html += '<div class="modal-footer">';
    html += '<button type="button" class="btn btn-sm btn-secondary alert-cancel-btn">' + cancelText + '</button>';
    html += '<button type="button" class="btn btn-sm btn-primary alert-confirm-btn">' + confirmText + '</button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $("body").append(html);
    $("#" + id).modal("show");
    $(document).on("click", "#" + id + " .alert-confirm-btn", function () {
        $("#" + id).modal("hide");
        var val = $("#" + id).find(".form-control").val();
        confirm(val);
    });
    $(document).on("click", "#" + id + " .alert-cancel-btn", function () {
        $("#" + id).modal("hide");
        var val = $("#" + id).find(".form-control").val();
        cancel(val);
    });
};

$.myAlert = function (args) {
    args = args || {};
    var title = args.title || "提示";
    var content = args.content || "";
    var confirmText = args.confirmText || "确认";
    var confirm = args.confirm || function () {
    };
    var id = $.randomString();
    var html = '';
    html += '<div class="modal fade" data-backdrop="static" id="' + id + '">';
    html += '<div class="modal-dialog modal-sm" role="document">';
    html += '<div class="modal-content" style="box-shadow: 0 1px 5px rgba(0,0,0,.25)">';
    html += '<div class="modal-header">';
    html += '<h6 class="modal-title">' + title + '</h6>';
    html += '</div>';
    html += '<div class="modal-body">' + content + '</div>';
    html += '<div class="modal-footer">';
    html += '<button type="button" class="btn btn-sm btn-primary alert-confirm-btn">' + confirmText + '</button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $("body").append(html);
    $("#" + id).modal("show");
    $(document).on("click", "#" + id + " .alert-confirm-btn", function () {
        $("#" + id).modal("hide");
        confirm();
    });
};

$.myToastHide = function (id) {
    $(document).on("hidden.bs.modal", "#myToast", function () {
        $(this).remove();
    });
    $("#" + id).modal("hide");
    $("#" + id).remove();
};

$.myToast = function (args) {
    args = args || {};
    $.myToastHide();
    var content = args.title ? args.title : (args.content ? args.content : "");
    var timeout = args.timeout ? args.timeout : 3000;
    var callback = args.callback || function () {
    };
    var html = '';
    var id = $.randomString();
    html += '<div class="modal my-toast" data-backdrop="static" id="' + id + '" aria-hidden="true">';
    html += '<div class="modal-dialog modal-sm" role="document">';
    html += '<div style="text-align: center;color: #fff">';
    html += '<div class="toast-content">' + content + '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $("body").append(html);
    $("#" + id).modal("show");
    setTimeout(function () {
        $.myToastHide(id);
        callback();
    }, timeout);
};

/**
 * title
 * */
$.myLoading = function (args) {
    args = args || {};
    var title = args.title || "Loading";
    if ($("#myLoading").length > 0) {
        $("#myLoading .loading-title").html(title);
    } else {
        var html = '';
        html += '<div class="modal" data-backdrop="static" id="myLoading" aria-hidden="true">';
        html += '<div class="modal-dialog modal-sm" role="document">';
        html += '<div style="text-align: center;color: #fff">';
        html += '<div style="width: 5rem;height: 5rem;display: inline-block">' + _loading_svg + '</div>';
        html += '<div class="loading-title">' + title + '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        $("body").append(html);
    }
    $("#myLoading").modal("show");
};

$.myLoadingHide = function () {
    $("#myLoading").modal("hide");
};

$.formSubmit = function (args) {
    if (!args.el)
        return;
    var form = $(args.el);
    var return_url = form.attr("return");
    var btn = form.find(".submit-btn");
    var data = {};
    if (form.attr("action"))
        data["url"] = form.attr("action");
    data["type"] = form.attr("method") ? form.attr("method") : "get";
    data["data"] = form.serialize();
    data["dataType"] = args.dataType || "json";
    if (!args.success)
        data["success"] = function (res) {
            if (res.code == 0) {
                $.myToast({
                    content: res.msg,
                    callback: function () {
                        if (return_url)
                            location.href = return_url;
                    }
                });
            } else {
                $.myToast({
                    content: res.msg,
                });
                btn.btnReset();
            }
        };
    else
        data["success"] = args.success;

    if (!args.error)
        data["error"] = function (res) {

        };
    else
        data["error"] = args.error;

    if (!args.complete)
        data["complete"] = function (res) {

        };
    else
        data["complete"] = args.complete;

    btn.btnLoading("正在提交");
    $.ajax(data);

};

$.fn.extend({
    btnLoading: function (loadingText, showIcon) {
        loadingText = loadingText || "Loading";
        showIcon = showIcon || true;
        this[0].originalText = this.html();
        this.html(loadingText).addClass("disabled btn-loading");
        this.prop("disabled", true);
        return this;
    },
    btnReset: function () {
        this.html(this[0].originalText);
        this.removeClass("disabled btn-loading");
        this.prop("disabled", false);
        return this;
    },
    formSubmit: function (args) {
        args["el"] = this.length > 0 ? this[0] : null;
        $.formSubmit(args);
    },

    /**
     * {
     * url:'上传接收端'
     * success:function(element,responseData,httpStatusCode)
     * progress:function(element,percent)
     * }
     * */
    plupload: function (args) {
        if (typeof plupload !== 'object') {
            throw '缺少plupload上传组件，详见：http://www.bootcdn.cn/plupload/\n或者使用<script src="https://cdn.bootcss.com/plupload/2.3.4/plupload.full.min.js"></script>';
        }
        var $this = this;
        $this.each(function () {
            pluploadInit(this);
        });


        function pluploadInit(e) {
            var $e = $(e);
            var id = $e.attr("id");
            if (!id) {
                id = $.randomString(32);
                $e.attr("id", id);
            }
            var url = $e.attr('data-url');
            if (url === '' || url === undefined)
                url = (args.url ? args.url : '');
            var uploader = new plupload.Uploader({
                browse_button: id,
                runtimes: 'html5',
                multi_selection: false,
                url: url,
                filters: {
                    max_file_size: '8mb',
                    mime_types: [
                        {title: "Image files", extensions: "jpg,gif,png,bmp"}
                    ]
                },
                multipart_params: args.multipart_params || {}
            });

            uploader.bind('Init', function (uploader) {

            });

            uploader.bind('FilesAdded', function (uploader, files) {
                setTimeout(function () {
                    uploader.start();
                }, 10);
            });

            uploader.bind('BeforeUpload', function (uploader, file) {
                uploader.disableBrowse(true);
            });

            uploader.bind('UploadProgress', function (uploader, file) {
                if (typeof args.progress === 'function') {
                    args.progress(e, file.percent);
                }
            });

            uploader.bind('FileUploaded', function (uploader, file, responseObject) {
                //console.log(responseObject);
                if (typeof args.success === 'function') {
                    args.success(e, responseObject.response, responseObject.status);
                }
            });

            uploader.bind('UploadComplete', function (uploader, files) {
                uploader.disableBrowse(false);
            });

            uploader.init();

        }

    },
});


$(document).ready(function () {

    //Yii2 POST表单添加_csrf
    $("form[method=post]").each(function () {
        if (this._csrf == undefined)
            $(this).append('<input name="_csrf" value="' + _csrf + '" type="hidden">');
    });

    //元素自动保持比例
    (function () {
        $.toResponsive = function () {
            $("*[data-responsive]").each(function (i) {
                var originWidth = parseFloat($(this).css("width"));
                var responsive = $(this).attr("data-responsive");
                var sizeData = responsive.split(":");
                var width = parseFloat(sizeData[0]);
                var height = parseFloat(sizeData[1]);
                var newHeight = height * originWidth / width;
                $(this).css("height", newHeight)
            });
        };

        $(document).ready(function () {
            $.toResponsive();
        });
        window.onresize = function () {
            $.toResponsive();
        };
    })();

    //表单自动提交
    (function () {
        $(document).on("click", ".auto-submit-form .submit-btn", "click", function () {
            var form = $(this).parents("form");
            try {
                form.formSubmit({});
            } catch (e) {
                console.log(e);
            }
            return false;
        });

        $(document).on("submit", ".auto-submit-form", function () {
            var form = $(this);
            try {
                form.formSubmit({});
            } catch (e) {
                console.log(e);
            }
            return false;
        });
    })();

});