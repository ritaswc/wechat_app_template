var _loading_svg = '<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-hourglass" style="background:none"><defs><clipPath ng-attr-id="{{config.cpid}}" id="lds-hourglass-cpid-943b587b2a65e8"><rect x="0" y="28.2737" width="100" height="21.7263"><animate attributeName="y" calcMode="spline" values="0;50;0;0;0" keyTimes="0;0.4;0.5;0.9;1" dur="2.2" keySplines="0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7" begin="0s" repeatCount="indefinite"></animate><animate attributeName="height" calcMode="spline" values="50;0;0;50;50" keyTimes="0;0.4;0.5;0.9;1" dur="2.2" keySplines="0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7" begin="0s" repeatCount="indefinite"></animate></rect><rect x="0" y="71.7263" width="100" height="28.2737"><animate attributeName="y" calcMode="spline" values="100;50;50;50;50" keyTimes="0;0.4;0.5;0.9;1" dur="2.2" keySplines="0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7" begin="0s" repeatCount="indefinite"></animate><animate attributeName="height" calcMode="spline" values="0;50;50;0;0" keyTimes="0;0.4;0.5;0.9;1" dur="2.2" keySplines="0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7;0.3 0 1 0.7" begin="0s" repeatCount="indefinite"></animate></rect></clipPath></defs><g transform="translate(50,50)"><g transform="scale(0.9)"><g transform="translate(-50,-50)"><g transform="rotate(0)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;0 50 50;180 50 50;180 50 50;360 50 50" keyTimes="0;0.4;0.5;0.9;1" dur="2.2s" begin="0s" repeatCount="indefinite"></animateTransform><path ng-attr-clip-path="url(#{{config.cpid}})" ng-attr-fill="{{config.sand}}" d="M54.864,50L54.864,50c0-1.291,0.689-2.412,1.671-2.729c9.624-3.107,17.154-12.911,19.347-25.296 c0.681-3.844-1.698-7.475-4.791-7.475H28.908c-3.093,0-5.472,3.631-4.791,7.475c2.194,12.385,9.723,22.189,19.347,25.296 c0.982,0.317,1.671,1.438,1.671,2.729v0c0,1.291-0.689,2.412-1.671,2.729C33.84,55.836,26.311,65.64,24.117,78.025 c-0.681,3.844,1.698,7.475,4.791,7.475h42.184c3.093,0,5.472-3.631,4.791-7.475C73.689,65.64,66.16,55.836,56.536,52.729 C55.553,52.412,54.864,51.291,54.864,50z" clip-path="url(#lds-hourglass-cpid-943b587b2a65e8)" fill="#6b6b71"></path><path ng-attr-fill="{{config.frame}}" d="M81,81.5h-2.724l0.091-0.578c0.178-1.122,0.17-2.243-0.022-3.333C76.013,64.42,68.103,54.033,57.703,50.483l-0.339-0.116 v-0.715l0.339-0.135c10.399-3.552,18.31-13.938,20.642-27.107c0.192-1.089,0.2-2.211,0.022-3.333L78.276,18.5H81 c2.481,0,4.5-2.019,4.5-4.5S83.481,9.5,81,9.5H19c-2.481,0-4.5,2.019-4.5,4.5s2.019,4.5,4.5,4.5h2.724l-0.092,0.578 c-0.178,1.122-0.17,2.243,0.023,3.333c2.333,13.168,10.242,23.555,20.642,27.107l0.338,0.116v0.715l-0.338,0.135 c-10.4,3.551-18.31,13.938-20.642,27.106c-0.193,1.09-0.201,2.211-0.023,3.333l0.092,0.578H19c-2.481,0-4.5,2.019-4.5,4.5 s2.019,4.5,4.5,4.5h62c2.481,0,4.5-2.019,4.5-4.5S83.481,81.5,81,81.5z M73.14,81.191L73.012,81.5H26.988l-0.128-0.309 c-0.244-0.588-0.491-1.538-0.28-2.729c2.014-11.375,8.944-20.542,17.654-23.354c2.035-0.658,3.402-2.711,3.402-5.108 c0-2.398-1.368-4.451-3.403-5.108c-8.71-2.812-15.639-11.979-17.653-23.353c-0.211-1.191,0.036-2.143,0.281-2.731l0.128-0.308 h46.024l0.128,0.308c0.244,0.589,0.492,1.541,0.281,2.731c-2.015,11.375-8.944,20.541-17.654,23.353 c-2.035,0.658-3.402,2.71-3.402,5.108c0,2.397,1.368,4.45,3.403,5.108c8.71,2.812,15.64,11.979,17.653,23.354 C73.632,79.651,73.384,80.604,73.14,81.191z" fill="#bbbbc2"></path></g></g></g></g></svg>';
if (!String.prototype.replaceAll) {
    String.prototype.replaceAll = function (s1, s2) {
        return this.replace(new RegExp(s1, "gm"), s2);
    };
}
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
    html += '<button type="button" class="btn btn-secondary alert-cancel-btn">' + cancelText + '</button>';
    html += '<button type="button" class="btn btn-primary alert-confirm-btn">' + confirmText + '</button>';
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

    var input_params_html = '';
    if (args.input_params) {
        for (var i in args.input_params) {
            if (typeof args.input_params[i] == 'string') {
                args.input_params[i] = args.input_params[i].replaceAll("\"", "&quot;");
            }
            input_params_html += ' ' + i + '="' + args.input_params[i] + '"';
        }
    }
    console.log(args);
    console.log(input_params_html);
    var html = '';
    html += '<div class="modal fade" data-backdrop="static" id="' + id + '">';
    html += '<div class="modal-dialog modal-sm" role="document">';
    html += '<div class="modal-content" style="box-shadow: 0 1px 5px rgba(0,0,0,.25)">';
    html += '<div class="modal-header">';
    html += '<h6 class="modal-title">' + title + '</h6>';
    html += '</div>';
    html += '<div class="modal-body">';
    html += '<div>' + content + '</div>';
    html += '<div class="mt-3"><input class="form-control" ' + input_params_html + '></div>';
    html += '</div>';
    html += '<div class="modal-footer">';
    html += '<button type="button" class="btn btn-secondary alert-cancel-btn">' + cancelText + '</button>';
    html += '<button type="button" class="btn btn-primary alert-confirm-btn">' + confirmText + '</button>';
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
    html += '<button type="button" class="btn btn-primary alert-confirm-btn">' + confirmText + '</button>';
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

$.myToastHide = function () {
    $(document).on("hidden.bs.modal", "#myToast", function () {
        $(this).remove();
    });
    $("#myToast").modal("hide");
};

$.myToast = function (args) {
    args = args || {};
    $.myToastHide();
    var content = args.title ? args.title : (args.content ? args.content : "");
    var timeout = args.timeout ? args.timeout : 3000;
    var callback = args.callback || function () {
    };
    var html = '';
    html += '<div class="modal" data-backdrop="static" id="myToast" aria-hidden="true">';
    html += '<div class="modal-dialog modal-sm" role="document">';
    html += '<div style="text-align: center;color: #fff">';
    html += '<div class="toast-content">' + content + '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $("body").append(html);
    $("#myToast").modal("show");
    setTimeout(function () {
        $.myToastHide();
        callback();
    }, timeout);
};

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

    plupload: function (args) {
        var $$this = this;
        $$this.each(function () {
            var _this = this;
            var $this = $(this);
            if (_this.uploader) {
                return;
            }
            if ($this.attr("id"))
                var browse_button = $this.attr("id");
            else {
                var browse_button = $.randomString();
                $this.attr("id", browse_button);
            }
            _this.uploader = new plupload.Uploader({
                browse_button: browse_button,
                url: args.url || "",
            });
            _this.uploader.bind("FilesAdded", function (uploader, files) {
                uploader.start();
                if (args.beforeUpload && typeof args.beforeUpload == "function")
                    args.beforeUpload($this, _this);
                uploader.disableBrowse(true);
            });
            _this.uploader.bind("FileUploaded", function (uploader, file, responseObject) {
                if (responseObject.status == 200) {

                }
                var res = JSON.parse(responseObject.response);
                if (args.success && typeof args.success == "function")
                    args.success(res, _this, $this);
            });
            _this.uploader.bind("UploadComplete", function (uploader, files) {
                uploader.destroy();
                _this.uploader = false;
                setTimeout(function () {
                    $(_this).plupload(args);
                }, 1);
            });
            _this.uploader.init();

        });

    }

});


$(document).ready(function () {

    //Yii2 POST表单添加_csrf
    $("form[method=post]").each(function () {
        if (this._csrf == undefined)
            $(this).append('<input name="_csrf" value="' + _csrf + '" type="text" style="display: none">');
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
                $(this).css("height", newHeight);
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
            var return_url = form.attr("data-return");
            var timeout = form.attr("data-timeout");
            var btn = $(this);
            var error = form.find(".form-error");
            var success = form.find(".form-success");
            error.hide();
            success.hide();
            btn.btnLoading("正在提交");
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                dataType: "json",
                success: function (res) {
                    if (res.code == 0) {
                        success.html(res.msg).show();
                        if (return_url) {
                            if (timeout)
                                timeout = 1000 * parseInt(timeout);
                            else
                                timeout = 1500;
                            setTimeout(function () {
                                location.href = return_url;
                            }, timeout);
                        } else {
                            btn.btnReset();
                        }
                    }
                    if (res.code == 1) {
                        error.html(res.msg).show();
                        btn.btnReset();
                    }
                }
            });
            return false;
        });

        $(document).on("submit", ".auto-submit-form", function () {
            var form = $(this);
            var return_url = form.attr("data-return");
            var timeout = form.attr("data-timeout");
            var btn = form.find(".submit-btn");
            var error = form.find(".form-error");
            var success = form.find(".form-success");
            error.hide();
            success.hide();
            btn.btnLoading("正在提交");
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                dataType: "json",
                success: function (res) {
                    if (res.code == 0) {
                        success.html(res.msg).show();
                        if (return_url) {
                            if (timeout)
                                timeout = 1000 * parseInt(timeout);
                            else
                                timeout = 1500;
                            setTimeout(function () {
                                location.href = return_url;
                            }, timeout);
                        } else {
                            btn.btnReset();
                        }
                    }
                    if (res.code == 1) {
                        btn.btnReset();
                        error.html(res.msg).show();
                    }
                }
            });
            return false;
        });
    })();

    //单图片上传
    /*
    (function () {
        var image_picker_list = $(".image-picker");
        if (image_picker_list.length == 0)
            return;
        image_picker_list.each(function (index) {
            var picker = this;
            var el = $(this);
            var btn = el.find(".image-picker-btn");
            var url = el.attr("data-url");
            var input = el.find(".image-picker-input");
            var view = el.find(".image-picker-view");


            function uploaderInit() {

                var el_id = $.randomString(32);
                btn.attr("id", el_id);


                var uploader = new plupload.Uploader({
                    browse_button: el_id,
                    url: url
                });
                uploader.bind("Init", function (uploader) {
                    $(".moxie-shim").find("input").attr("accept", "image/*").prop("multiple", false);
                });
                uploader.bind("FilesAdded", function (uploader, files) {
                    uploader.start();
                    btn.btnLoading("正在上传");
                    uploader.disableBrowse(true);
                });
                uploader.bind("FileUploaded", function (uploader, file, responseObject) {
                    if (responseObject.status == undefined || responseObject.status != 200) {
                        return true;
                    }
                    var res = $.parseJSON(responseObject.response);
                    if (res.code != 0)
                        return true;

                    var _multiple = el.attr("data-multiple");
                    if (_multiple && _multiple == "true") {
                        var _name = el.attr("data-name");
                        var _responsive = el.find(".image-picker-view").attr("data-responsive");
                        var _tip = el.find(".picker-tip").first().text();
                        var _html = '';
                        _html += '<div class="image-picker-view-item">';
                        _html += '<input class="image-picker-input" type="hidden" name="' + _name + '" value="' + (res.data.url) + '" >';
                        _html += '<div class="image-picker-view" data-responsive="' + _responsive + '" style="background-image: url(' + res.data.url + ')">';
                        _html += '<span class="picker-tip">' + _tip + '</span>';
                        _html += '<span class="picker-delete">×</span>';
                        _html += '</div>';
                        _html += '</div>';
                        el.find(".image-picker-view-item").last().after(_html);
                        $.toResponsive();
                        updateEmptyPicker(el);
                    }
                    else {
                        input.val(res.data.url).change();
                        view.css("background-image", "url('" + res.data.url + "')");
                    }
                });
                uploader.bind("UploadComplete", function (uploader, files) {
                    btn.btnReset();
                    uploader.destroy();
                    uploaderInit();
                });
                uploader.init();
            }

            uploaderInit();
        });

        $(document).on("click", ".image-picker-view .picker-delete", function () {
            var picker = $(this).parents(".image-picker");
            var multiple = picker.attr("data-multiple");
            if (multiple && multiple == "true") {
                $(this).parents(".image-picker-view-item").remove();
                updateEmptyPicker(picker);
            } else {
                var image_picker_view_item = $(this).parents(".image-picker-view-item");
                image_picker_view_item.find(".image-picker-input").val("").change();
                image_picker_view_item.find(".image-picker-view").css("background-image", "");
            }
        });

        function updateEmptyPicker(picker) {
            if (picker.find(".image-picker-view-item").length > 1) {
                picker.find(".picker-empty-preview").hide();
            } else {
                picker.find(".picker-empty-preview").show();
            }
        }

    })();
    */


    /*
    $(".new-image-picker-btn").pickImage({
        success: function (res, _this) {
            var el = $(_this).parents(".image-picker");
            if (el.attr("data-multiple")) {
                var _name = el.attr("data-name");
                var _responsive = el.find(".image-picker-view").attr("data-responsive");
                var _tip = el.find(".picker-tip").first().text();
                var _html = '';
                _html += '<div class="image-picker-view-item">';
                _html += '<input class="image-picker-input" type="hidden" name="' + _name + '" value="' + (res.data.url) + '" >';
                _html += '<div class="image-picker-view" data-responsive="' + _responsive + '" style="background-image: url(' + res.data.url + ')">';
                _html += '<span class="picker-tip">' + _tip + '</span>';
                _html += '<span class="picker-delete">×</span>';
                _html += '</div>';
                _html += '</div>';
                el.find(".image-picker-view-item").last().after(_html);
                $.toResponsive();
                updateEmptyPicker(el);
            } else {
                $(_this).parents(".image-picker").find(".image-picker-input").val(res.data.url).change();
                $(_this).parents(".image-picker").find(".image-picker-view").css("background-image", "url(" + res.data.url + ")");
            }


            function updateEmptyPicker(picker) {
                if (picker.find(".image-picker-view-item").length > 1) {
                    picker.find(".picker-empty-preview").hide();
                } else {
                    picker.find(".picker-empty-preview").show();
                }
            }
        }
    });
    */

});