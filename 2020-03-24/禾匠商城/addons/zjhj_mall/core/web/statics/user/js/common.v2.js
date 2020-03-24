/*
 * JavaScript MD5
 * https://github.com/blueimp/JavaScript-MD5
 *
 * Copyright 2011, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 *
 * Based on
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Version 2.2 Copyright (C) Paul Johnston 1999 - 2009
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for more info.
 */

/* global define */

;(function ($) {
    'use strict'

    /*
    * Add integers, wrapping at 2^32. This uses 16-bit operations internally
    * to work around bugs in some JS interpreters.
    */
    function safeAdd(x, y) {
        var lsw = (x & 0xffff) + (y & 0xffff)
        var msw = (x >> 16) + (y >> 16) + (lsw >> 16)
        return (msw << 16) | (lsw & 0xffff)
    }

    /*
    * Bitwise rotate a 32-bit number to the left.
    */
    function bitRotateLeft(num, cnt) {
        return (num << cnt) | (num >>> (32 - cnt))
    }

    /*
    * These functions implement the four basic operations the algorithm uses.
    */
    function md5cmn(q, a, b, x, s, t) {
        return safeAdd(bitRotateLeft(safeAdd(safeAdd(a, q), safeAdd(x, t)), s), b)
    }

    function md5ff(a, b, c, d, x, s, t) {
        return md5cmn((b & c) | (~b & d), a, b, x, s, t)
    }

    function md5gg(a, b, c, d, x, s, t) {
        return md5cmn((b & d) | (c & ~d), a, b, x, s, t)
    }

    function md5hh(a, b, c, d, x, s, t) {
        return md5cmn(b ^ c ^ d, a, b, x, s, t)
    }

    function md5ii(a, b, c, d, x, s, t) {
        return md5cmn(c ^ (b | ~d), a, b, x, s, t)
    }

    /*
    * Calculate the MD5 of an array of little-endian words, and a bit length.
    */
    function binlMD5(x, len) {
        /* append padding */
        x[len >> 5] |= 0x80 << (len % 32)
        x[((len + 64) >>> 9 << 4) + 14] = len

        var i
        var olda
        var oldb
        var oldc
        var oldd
        var a = 1732584193
        var b = -271733879
        var c = -1732584194
        var d = 271733878

        for (i = 0; i < x.length; i += 16) {
            olda = a
            oldb = b
            oldc = c
            oldd = d

            a = md5ff(a, b, c, d, x[i], 7, -680876936)
            d = md5ff(d, a, b, c, x[i + 1], 12, -389564586)
            c = md5ff(c, d, a, b, x[i + 2], 17, 606105819)
            b = md5ff(b, c, d, a, x[i + 3], 22, -1044525330)
            a = md5ff(a, b, c, d, x[i + 4], 7, -176418897)
            d = md5ff(d, a, b, c, x[i + 5], 12, 1200080426)
            c = md5ff(c, d, a, b, x[i + 6], 17, -1473231341)
            b = md5ff(b, c, d, a, x[i + 7], 22, -45705983)
            a = md5ff(a, b, c, d, x[i + 8], 7, 1770035416)
            d = md5ff(d, a, b, c, x[i + 9], 12, -1958414417)
            c = md5ff(c, d, a, b, x[i + 10], 17, -42063)
            b = md5ff(b, c, d, a, x[i + 11], 22, -1990404162)
            a = md5ff(a, b, c, d, x[i + 12], 7, 1804603682)
            d = md5ff(d, a, b, c, x[i + 13], 12, -40341101)
            c = md5ff(c, d, a, b, x[i + 14], 17, -1502002290)
            b = md5ff(b, c, d, a, x[i + 15], 22, 1236535329)

            a = md5gg(a, b, c, d, x[i + 1], 5, -165796510)
            d = md5gg(d, a, b, c, x[i + 6], 9, -1069501632)
            c = md5gg(c, d, a, b, x[i + 11], 14, 643717713)
            b = md5gg(b, c, d, a, x[i], 20, -373897302)
            a = md5gg(a, b, c, d, x[i + 5], 5, -701558691)
            d = md5gg(d, a, b, c, x[i + 10], 9, 38016083)
            c = md5gg(c, d, a, b, x[i + 15], 14, -660478335)
            b = md5gg(b, c, d, a, x[i + 4], 20, -405537848)
            a = md5gg(a, b, c, d, x[i + 9], 5, 568446438)
            d = md5gg(d, a, b, c, x[i + 14], 9, -1019803690)
            c = md5gg(c, d, a, b, x[i + 3], 14, -187363961)
            b = md5gg(b, c, d, a, x[i + 8], 20, 1163531501)
            a = md5gg(a, b, c, d, x[i + 13], 5, -1444681467)
            d = md5gg(d, a, b, c, x[i + 2], 9, -51403784)
            c = md5gg(c, d, a, b, x[i + 7], 14, 1735328473)
            b = md5gg(b, c, d, a, x[i + 12], 20, -1926607734)

            a = md5hh(a, b, c, d, x[i + 5], 4, -378558)
            d = md5hh(d, a, b, c, x[i + 8], 11, -2022574463)
            c = md5hh(c, d, a, b, x[i + 11], 16, 1839030562)
            b = md5hh(b, c, d, a, x[i + 14], 23, -35309556)
            a = md5hh(a, b, c, d, x[i + 1], 4, -1530992060)
            d = md5hh(d, a, b, c, x[i + 4], 11, 1272893353)
            c = md5hh(c, d, a, b, x[i + 7], 16, -155497632)
            b = md5hh(b, c, d, a, x[i + 10], 23, -1094730640)
            a = md5hh(a, b, c, d, x[i + 13], 4, 681279174)
            d = md5hh(d, a, b, c, x[i], 11, -358537222)
            c = md5hh(c, d, a, b, x[i + 3], 16, -722521979)
            b = md5hh(b, c, d, a, x[i + 6], 23, 76029189)
            a = md5hh(a, b, c, d, x[i + 9], 4, -640364487)
            d = md5hh(d, a, b, c, x[i + 12], 11, -421815835)
            c = md5hh(c, d, a, b, x[i + 15], 16, 530742520)
            b = md5hh(b, c, d, a, x[i + 2], 23, -995338651)

            a = md5ii(a, b, c, d, x[i], 6, -198630844)
            d = md5ii(d, a, b, c, x[i + 7], 10, 1126891415)
            c = md5ii(c, d, a, b, x[i + 14], 15, -1416354905)
            b = md5ii(b, c, d, a, x[i + 5], 21, -57434055)
            a = md5ii(a, b, c, d, x[i + 12], 6, 1700485571)
            d = md5ii(d, a, b, c, x[i + 3], 10, -1894986606)
            c = md5ii(c, d, a, b, x[i + 10], 15, -1051523)
            b = md5ii(b, c, d, a, x[i + 1], 21, -2054922799)
            a = md5ii(a, b, c, d, x[i + 8], 6, 1873313359)
            d = md5ii(d, a, b, c, x[i + 15], 10, -30611744)
            c = md5ii(c, d, a, b, x[i + 6], 15, -1560198380)
            b = md5ii(b, c, d, a, x[i + 13], 21, 1309151649)
            a = md5ii(a, b, c, d, x[i + 4], 6, -145523070)
            d = md5ii(d, a, b, c, x[i + 11], 10, -1120210379)
            c = md5ii(c, d, a, b, x[i + 2], 15, 718787259)
            b = md5ii(b, c, d, a, x[i + 9], 21, -343485551)

            a = safeAdd(a, olda)
            b = safeAdd(b, oldb)
            c = safeAdd(c, oldc)
            d = safeAdd(d, oldd)
        }
        return [a, b, c, d]
    }

    /*
    * Convert an array of little-endian words to a string
    */
    function binl2rstr(input) {
        var i
        var output = ''
        var length32 = input.length * 32
        for (i = 0; i < length32; i += 8) {
            output += String.fromCharCode((input[i >> 5] >>> (i % 32)) & 0xff)
        }
        return output
    }

    /*
    * Convert a raw string to an array of little-endian words
    * Characters >255 have their high-byte silently ignored.
    */
    function rstr2binl(input) {
        var i
        var output = []
        output[(input.length >> 2) - 1] = undefined
        for (i = 0; i < output.length; i += 1) {
            output[i] = 0
        }
        var length8 = input.length * 8
        for (i = 0; i < length8; i += 8) {
            output[i >> 5] |= (input.charCodeAt(i / 8) & 0xff) << (i % 32)
        }
        return output
    }

    /*
    * Calculate the MD5 of a raw string
    */
    function rstrMD5(s) {
        return binl2rstr(binlMD5(rstr2binl(s), s.length * 8))
    }

    /*
    * Calculate the HMAC-MD5, of a key and some data (raw strings)
    */
    function rstrHMACMD5(key, data) {
        var i
        var bkey = rstr2binl(key)
        var ipad = []
        var opad = []
        var hash
        ipad[15] = opad[15] = undefined
        if (bkey.length > 16) {
            bkey = binlMD5(bkey, key.length * 8)
        }
        for (i = 0; i < 16; i += 1) {
            ipad[i] = bkey[i] ^ 0x36363636
            opad[i] = bkey[i] ^ 0x5c5c5c5c
        }
        hash = binlMD5(ipad.concat(rstr2binl(data)), 512 + data.length * 8)
        return binl2rstr(binlMD5(opad.concat(hash), 512 + 128))
    }

    /*
    * Convert a raw string to a hex string
    */
    function rstr2hex(input) {
        var hexTab = '0123456789abcdef'
        var output = ''
        var x
        var i
        for (i = 0; i < input.length; i += 1) {
            x = input.charCodeAt(i)
            output += hexTab.charAt((x >>> 4) & 0x0f) + hexTab.charAt(x & 0x0f)
        }
        return output
    }

    /*
    * Encode a string as utf-8
    */
    function str2rstrUTF8(input) {
        return unescape(encodeURIComponent(input))
    }

    /*
    * Take string arguments and return either raw or hex encoded strings
    */
    function rawMD5(s) {
        return rstrMD5(str2rstrUTF8(s))
    }

    function hexMD5(s) {
        return rstr2hex(rawMD5(s))
    }

    function rawHMACMD5(k, d) {
        return rstrHMACMD5(str2rstrUTF8(k), str2rstrUTF8(d))
    }

    function hexHMACMD5(k, d) {
        return rstr2hex(rawHMACMD5(k, d))
    }

    function md5(string, key, raw) {
        if (!key) {
            if (!raw) {
                return hexMD5(string)
            }
            return rawMD5(string)
        }
        if (!raw) {
            return hexHMACMD5(key, string)
        }
        return rawHMACMD5(key, string)
    }

    if (typeof define === 'function' && define.amd) {
        define(function () {
            return md5
        })
    } else if (typeof module === 'object' && module.exports) {
        module.exports = md5
    } else {
        $.md5 = md5
    }
})(this)

/*---- 对话框 start ----*/
$.confirm = function (args) {
    args = args || {};
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

    html += '<div class="panel">';
    if (args.title) {
        html += '<div class="panel-header"><b>' + args.title + '</b></div>';
    }
    html += '<div class="panel-body">' + content + '</div>';
    html += '<div class="panel-footer text-right">';
    html += '  <button type="button" class="btn btn-secondary alert-cancel-btn">' + cancelText + '</button>';
    html += '  <button type="button" class="btn btn-primary alert-confirm-btn">' + confirmText + '</button>';
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

$.prompt = function (args) {
    args = args || {};
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

    html += '<div class="panel">';
    if (args.title) {
        html += '<div class="panel-header"><b>' + args.title + '</b></div>';
    }
    html += '  <div class="panel-body">';
    html += '    <div>' + content + '</div>';
    html += '    <div class="mt-3"><input class="form-control"></div>';
    html += '  </div>';
    html += '  <div class="panel-footer text-right">';
    html += '    <button class="btn btn-secondary alert-cancel-btn">' + cancelText + '</button>';
    html += '    <button class="btn btn-primary alert-confirm-btn">' + confirmText + '</button>';
    html += '  </div>';
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

$.alert = function (args) {
    args = args || {};
    var content = args.content || "";
    var confirmText = args.confirmText || "确认";
    var confirm = args.confirm || function () {
    };
    var id = $.randomString();
    var html = '';
    html += '<div class="modal fade" data-backdrop="static" id="' + id + '">';
    html += '<div class="modal-dialog modal-sm" role="document">';
    html += '<div class="panel">';
    if (args.title) {
        html += '<div class="panel-header"><b>' + args.title + '</b></div>';
    }
    html += '<div class="panel-body">' + content + '</div>';
    html += '<div class="panel-footer text-right"><button class="btn btn-primary alert-confirm-btn">' + confirmText + '</button></div>';
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

$.toast = function (args) {
    args = args || {};
    var content = args.content || (args.title || "");
    var timeout = args.timeout || 3000;
    var hide = args.hide || null;
    var id = $.randomString();
    var html = '<div class="toast-alert fixed-top" id="' + id + '"><div class="alert alert-info rounded-0">' + content + '</div></div>';
    $("body").append(html);
    setTimeout(function () {
        $("#" + id).addClass("show");
    }, 1);
    setTimeout(function () {
        $("#" + id).removeClass("show");
        setTimeout(function () {
            $("#" + id).remove();
            if (typeof hide == 'function') {
                hide();
            }
        }, 500);
    }, timeout);
    return id;
};

$.loading = function (args) {
    args = args || {};
    var text = args.title || (args.content || '');
    if ($("#myLoading").length > 0) {
        $("#myLoading .loading-text").html(text);
    } else {
        var html = '';
        html += '<div class="modal" data-backdrop="static" id="myLoading" aria-hidden="true">';
        html += '<div class="modal-dialog modal-sm" role="document">';

        html += '<div class="panel">';
        html += '<div class="panel-body">';
        html += '<div class="loading-icon text-center mt-3 mb-3"><img style="width: 24px;height: 24px" src="data:image/gif;base64,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"></div>';
        html += '<div class="loading-text text-center mt-3 mb-3">' + text + '</div>';
        html += '</div>';
        html += '</div>';

        html += '</div>';
        html += '</div>';
        $("body").append(html);
    }
    $("#myLoading").modal("show");
};
$.loadingHide = function () {
    $("#myLoading").modal("hide");
};
/*---- 对话框 end ----*/


/*---- 文件上传 start ----*/
var _pl_file_uploader = {
    id: $.randomString(),
    uploader: null,
    input: null,
    start: null,
    progress_timer: null,
    progress: null,
    success: null,
    error: null,
    complete: null,
    dataType: 'json',
};
$(document).ready(function () {
    $('body').append('<a id="' + _pl_file_uploader.id + '" href="javascript:" style="display: none!important;">pl_file_element</a>');

    function uploader_init() {
        _pl_file_uploader.uploader = new plupload.Uploader({
            browse_button: _pl_file_uploader.id, //触发文件选择对话框的按钮，为那个元素id
            url: _upload_url, //服务器端的上传页面地址
        });
        _pl_file_uploader.uploader.bind('Init', function (uploader) {
            _pl_file_uploader.input = $('#' + _pl_file_uploader.id + ' ~ .moxie-shim input[type=file]');
        });
        _pl_file_uploader.uploader.bind('FilesAdded', function (uploader, files) {
            if (typeof _pl_file_uploader.start === 'function') {
                _pl_file_uploader.start();
            }
            if (typeof _pl_file_uploader.progress === 'function') {
                _pl_file_uploader.progress_timer = setInterval(function () {
                    _pl_file_uploader.progress(_pl_file_uploader.uploader.total);
                }, 200);
            }
            _pl_file_uploader.uploader.start();
        });
        _pl_file_uploader.uploader.bind('FileUploaded', function (uploader, file, responseObject) {
            if (responseObject.status === 200 && typeof _pl_file_uploader.success === 'function') {
                var res = null;
                if (_pl_file_uploader.dataType === 'json') {
                    res = JSON.parse(responseObject.response);
                } else {
                    res = responseObject.response;
                }
                _pl_file_uploader.success(res);
            }
        });
        _pl_file_uploader.uploader.bind('UploadComplete', function (uploader, files) {
            if (_pl_file_uploader.progress_timer)
                clearInterval(_pl_file_uploader.progress_timer);
            if (typeof _pl_file_uploader.complete === 'function') {
                _pl_file_uploader.complete();
            }
            _pl_file_uploader.uploader.destroy();
            uploader_init();
        });
        _pl_file_uploader.uploader.bind('Error', function (uploader, errObject) {
            if (typeof _pl_file_uploader.error === 'function') {
                _pl_file_uploader.error(errObject);
            }
        });
        _pl_file_uploader.uploader.init();
    }

    uploader_init();

});

$.upload_file = function (args) {
    _pl_file_uploader.input.prop('multiple', args.multiple || false);
    _pl_file_uploader.input.attr('accept', args.accept || '*/*');
    _pl_file_uploader.dataType = args.dataType || 'json';
    _pl_file_uploader.dataType = _pl_file_uploader.dataType.toLowerCase();
    _pl_file_uploader.start = args.start || null;
    _pl_file_uploader.progress = args.progress || null;
    _pl_file_uploader.success = args.success || null;
    _pl_file_uploader.error = args.error || null;
    _pl_file_uploader.complete = args.complete || null;
    document.getElementById(_pl_file_uploader.id).click();
};
/*---- 文件上传 end ----*/

/*---- 文件选择 start ----*/
var _file_select = {
    success: null,
};

$(document).on('click', '#file_select_modal .file-item', function () {
    var item = $(this);
    if (typeof _file_select.success === 'function') {
        _file_select.success({
            name: item.attr('data-name'),
            url: item.attr('data-url'),
        });
    }
    $('#file_select_modal').modal('hide');
});

$(document).on('click', '#file_select_modal .file-more', function () {
    var list_block = $('#file_select_modal .file-list');
    var more_btn = $('#file_select_modal .file-more');
    var loading_block = $('#file_select_modal .file-loading');
    var page = parseInt(more_btn.attr('data-page'));
    loading_block.show();
    more_btn.hide();
    $.ajax({
        url: _upload_file_list_url,
        data: {
            dataType: 'html',
            type: 'image',
            page: page,
        },
        success: function (res) {
            more_btn.attr('data-page', page + 1);
            loading_block.hide();
            more_btn.show();
            list_block.append(res);
        }
    });
});

$.select_file = function (args) {
    $('#file_select_modal').modal('show');
    var list_block = $('#file_select_modal .file-list');
    var more_btn = $('#file_select_modal .file-more');
    var loading_block = $('#file_select_modal .file-loading');
    list_block.html('');
    loading_block.show();
    more_btn.hide();
    $.ajax({
        url: _upload_file_list_url,
        data: {
            dataType: 'html',
            type: 'image',
            page: 1,
        },
        success: function (res) {
            more_btn.attr('data-page', 2);
            loading_block.hide();
            more_btn.show();
            list_block.append(res);
        }
    });
    if (typeof args.success === 'function') {
        _file_select.success = args.success;
    }
};
/*---- 文件选择 end ----*/

/*---- 表单自动提交 start ----*/
$(document).ready(function () {
    $(document).on('submit', '.auto-form', function () {
        submit(this);
        return false;
    });
    $(document).on('click', '.auto-form .auto-form-btn', function () {
        var form = $(this).parents('.auto-form');
        submit(form);
        return false;
    });

    function submit(form) {
        var btn = $(form.find('.auto-form-btn'));
        btn.btnLoading(btn.text());
        $.ajax({
            url: form.attr('action') || '',
            type: form.attr('method') || 'get',
            dataType: 'json',
            data: form.serialize(),
            success: function (res) {
                if (res.code == 0) {
                    $.alert({
                        content: res.msg,
                        confirm: function () {
                            if (res.url) {
                                location.href = res.url;
                            } else if (form.attr('return')) {
                                location.href = form.attr('return');
                            } else {
                                location.reload();
                            }
                            setTimeout(function () {
                                btn.btnReset();
                            }, 30000);
                        }
                    });
                }
                if (res.code == 1) {
                    btn.btnReset();
                    $.alert({
                        content: res.msg,
                    });
                }
            },
            error: function (e) {
                btn.btnReset();
                $.alert({
                    title: '<span class="text-danger">系统错误</span>',
                    content: e.responseText,
                });
            }
        });
    }
});
/*---- 表单自动提交 end ----*/

/*---- 快速上传组件 start ----*/
$(document).on('click', '.upload-group .upload-file', function () {
    var btn = $(this);
    var group = btn.parents('.upload-group');
    var input = group.find('.file-input');
    var preview = group.find('.upload-preview');
    var preview_img = group.find('.upload-preview-img');
    $.upload_file({
        accept: group.attr('accept') || 'image/*',
        start: function () {
            btn.btnLoading(btn.text());
        },
        success: function (res) {
            btn.btnReset();
            if (group.hasClass('multiple')) {
                if (preview.find('.file-item-input').val() == '') {
                    if (res.code === 1) {
                        $.alert({
                            content: res.msg
                        });
                        return;
                    }
                    preview.find('.file-item-input').val(res.data.url).trigger('change');
                    preview.find('.upload-preview-img').attr('src', res.data.url);
                } else {
                    var preview_item = document.createElementNS('temp_element', 'div');
                    preview_item.innerHTML = preview.prop('outerHTML');
                    preview_item = $(preview_item).find('.upload-preview');
                    var file_item_input = preview_item.find('.file-item-input');
                    var file_item_preview_img = preview_item.find('.upload-preview-img');
                    file_item_input.val(res.data.url).trigger('change');
                    file_item_preview_img.attr('src', res.data.url);
                    group.find('.upload-preview-list').append(preview_item);
                }
            } else {
                if (res.code === 1) {
                    $.alert({
                        content: res.msg
                    });
                    return;
                }
                input.val(res.data.url).trigger('change');
                preview_img.attr('src', res.data.url);
            }
        },
    });
});
$(document).on('click', '.upload-group .select-file', function () {
    var btn = $(this);
    var group = btn.parents('.upload-group');
    var input = group.find('.file-input');
    var preview = group.find('.upload-preview');
    var preview_img = group.find('.upload-preview-img');
    $.select_file({
        success: function (res) {
            if (group.hasClass('multiple')) {
                if (preview.find('.file-item-input').val() == '') {
                    preview.find('.file-item-input').val(res.url).trigger('change');
                    preview.find('.upload-preview-img').attr('src', res.url);
                } else {
                    var preview_item = document.createElementNS('temp_element', 'div');
                    preview_item.innerHTML = preview.prop('outerHTML');
                    preview_item = $(preview_item).find('.upload-preview');
                    var file_item_input = preview_item.find('.file-item-input');
                    var file_item_preview_img = preview_item.find('.upload-preview-img');
                    file_item_input.val(res.url).trigger('change');
                    file_item_preview_img.attr('src', res.url);
                    group.find('.upload-preview-list').append(preview_item);
                }
            } else {
                input.val(res.url).trigger('change');
                preview_img.attr('src', res.url);
            }
        },
    });
});
$(document).on('click', '.upload-group .delete-file', function () {
    var btn = $(this);
    var group = btn.parents('.upload-group');
    var input = group.find('.file-input');
    var preview_img = group.find('.upload-preview-img');
    input.val('').trigger('change');
    preview_img.attr('src', '');
});
$(document).on('change', '.upload-group .file-input', function () {
    var input = $(this);
    var group = input.parents('.upload-group');
    var preview_img = group.find('.upload-preview-img');
    preview_img.attr('src', input.val());
});
$(document).on('click', '.upload-group .file-item-delete', function () {
    var btn = $(this);
    var preview = btn.parents('.upload-preview');
    if (preview.siblings('.upload-preview').length == 0) {
        preview.find('.file-item-input').val('');
        preview.find('.upload-preview-img').attr('src', '');
    } else {
        preview.remove();
    }
});
/*---- 快速上传组件 end ----*/

/*---- 地区选择器 start ----*/
var districtPicker = null;
$(document).ready(function () {
    var onSuccess = null;
    var onError = null;
    districtPicker = new Vue({
        el: '#district_pick_modal',
        data: {
            province_list: [],
            province_id: null,
            province_name: null,
            city_list: [],
            city_id: null,
            city_name: null,
            district_list: [],
            district_id: null,
            district_name: null,
        },
        methods: {
            provinceChange: function (e) {
                for (var i in this.province_list) {
                    if (this.province_list[i].id == this.province_id) {
                        this.province_name = this.province_list[i].name;
                        this.city_list = this.province_list[i].list;
                        this.district_list = [];
                        break;
                    }
                }
            },
            cityChange: function (e) {
                for (var i in this.city_list) {
                    if (this.city_list[i].id == this.city_id) {
                        this.city_name = this.city_list[i].name;
                        this.district_list = this.city_list[i].list;
                        break;
                    }
                }
            },
            districtChange: function (e) {
                for (var i in this.district_list) {
                    if (this.district_list[i].id == this.district_id) {
                        this.district_name = this.district_list[i].name;
                        break;
                    }
                }
            },
        },
    });

    $(document).on('click', '#district_pick_modal .district-confirm-btn', function () {
        $('#district_pick_modal').modal('hide');
        if (!districtPicker.province_id) {
            if (typeof onError == 'function')
                onError('没有选择省份');
        }
        else if (!districtPicker.city_id) {
            if (typeof onError == 'function')
                onError('没有选择城市');
        }
        else if (!districtPicker.district_id) {
            if (typeof onError == 'function')
                onError('没有选择县/区');
        } else if (typeof onSuccess == 'function') {
            onSuccess({
                province_id: districtPicker.province_id,
                province_name: districtPicker.province_name,
                city_id: districtPicker.city_id,
                city_name: districtPicker.city_name,
                district_id: districtPicker.district_id,
                district_name: districtPicker.district_name,
            });
        }
    });

    $.districtPicker = function (args) {
        var district_list = sessionStorage.getItem('district_list');
        onSuccess = args.success || null;
        onError = args.error || null;
        if (!district_list) {
            $.loading({title: '加载地区数据'});
            $.ajax({
                url: _district_data_url,
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        district_list = res['data'];
                        sessionStorage.setItem('district_list', JSON.stringify(district_list));
                        showModal(district_list);
                    }
                    if (res.code == 1) {
                        $.alert({
                            content: res.msg,
                        });
                    }
                },
                complete: function () {
                    $.loadingHide();
                }
            });
        } else {
            district_list = JSON.parse(district_list);
            showModal(district_list);
        }

        function showModal(district_list) {
            districtPicker.province_list = district_list;
            $('#district_pick_modal').modal('show');
        }
    };
});
/*---- 地区选择器 end ----*/

/*---- 简单的键值缓存器 ----*/
$.mcache = {
    _storage: {},
    _get_name: function (name) {
        if ((typeof name !== 'string' && typeof name !== 'number') || name === '' || name === null)
            return false;
        return 'name_' + md5(name);
    },
    _time: function () {
        return parseInt(Math.round(new Date() / 1000));
    },
    get: function (name) {
        var _name = this._get_name(name);
        if (_name === false)
            return null;
        if (typeof  this._storage[_name] !== 'object')
            return null;
        var _obj = this._storage[_name];
        if (this._time() > _obj.expire_time) {
            return null;
        }
        return _obj.val;
    },
    set: function (name, value, timeout) {
        timeout = parseInt(timeout || 0);
        if (isNaN(timeout))
            timeout = 0;
        var _name = this._get_name(name);
        if (_name === false)
            return false;
        var _obj = {
            val: value || null,
            expire_time: this._time() + timeout,
        };
        this._storage[_name] = _obj;
        return true;
    },
    delete: function (name) {
        var _name = this._get_name(name);
        if (_name === false)
            return false;
        this._storage[_name] = null;
    },
};