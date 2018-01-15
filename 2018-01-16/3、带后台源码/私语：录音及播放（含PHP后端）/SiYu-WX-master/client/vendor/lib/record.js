
var noop = function noop() {};
var recorder = {

};

/***
 * @class
 * 表示请求过程中发生的异常
 */
var RecordError = (function () {
    function RecordError(message) {
        Error.call(this, message);
        this.message = message;
    }

    RecordError.prototype = new Error();
    RecordError.prototype.constructor = RecordError;

    return RecordError;
})();

function startRecord(options) {
    if (typeof options !== 'object') {
        var message = '请求传参应为 object 类型，但实际传了 ' + (typeof options) + ' 类型';
        throw new RecordError(message);
    }

    var process = options.process;
    var success = options.success || noop;
    var fail = options.fail || noop;
    var complete = options.complete || noop;

    if (typeof process !== 'function'){
        var message = '刷新Ui函数不存在';
        throw new RecordError(message);
    }

    // 成功回调
    var callSuccess = function () {
        success.apply(null, arguments);
        complete.apply(null, arguments);
    };

    // 失败回调
    var callFail = function (error) {
        fail.call(null, error);
        complete.call(null, error);
    };
    
    initRecorder();
    doRecord();

    // 实际进行请求的方法
    function doRecord() {
        console.log("开始录音")
        recorder.timer = setInterval(function(){
            recorder.duration += 1;
            process();
            if (recorder.duration >= recorder.maxDuration && recorder.timer){
                clearInterval(recorder.timer);
            }
        }, 1000);
        wx.startRecord({
          success: function (res) {
                if (res.tempFilePath) {
                    recorder.src = res.tempFilePath;
                    callSuccess.apply(null, arguments);
                    return;
                }else{
                    message = '录音文件保存失败';
                    var error = new RecordError(message);
                    options.fail(error);
                }
                callFail(error);
            },

            fail: callFail,
            complete: complete,
        });

    };

    // 初始化录音器
    function initRecorder(){
        recorder = {
            maxDuration: 60,
            duration: 0,
            src: null,
            timer: null,
        };    
    }

};


function stopRecord(options) {
    if (typeof options !== 'object') {
        var message = '请求传参应为 object 类型，但实际传了 ' + (typeof options) + ' 类型';
        throw new RecordError(message);
    }

    var success = options.success || noop;
    var fail = options.fail || noop;
    var complete = options.complete || noop;

    // 成功回调
    var callSuccess = function () {
        success.apply(null, arguments);
        complete.apply(null, arguments);
    };

    // 失败回调
    var callFail = function (error) {
        fail.call(null, error);
        complete.call(null, error);
    };

    doStopRecord();

    // 实际进行请求的方法
    function doStopRecord() {
        wx.stopRecord({
          success: function (res) {
                if (recorder.timer){
                    clearInterval(recorder.timer);
                }
                callSuccess.apply(null, arguments);
            },
            fail: callFail,
            complete: complete,
        });
    };
};

function getRecordDuration(){
    return recorder.duration || 0;
}

function getRecordSrc(){
    return recorder.src || null;
}

module.exports = {
    RecordError: RecordError,
    startRecord: startRecord,
    stopRecord: stopRecord,
    getRecordDuration: getRecordDuration,
    getRecordSrc: getRecordSrc,
};