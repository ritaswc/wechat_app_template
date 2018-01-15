
var noop = function noop() {};
var player = {
    src: null,
};

/***
 * @class
 * 表示请求过程中发生的异常
 */
var PlayError = (function () {
    function PlayError(message) {
        Error.call(this, message);
        this.message = message;
    }

    PlayError.prototype = new Error();
    PlayError.prototype.constructor = PlayError;

    return PlayError;
})();

function play(options) {
    if (typeof options !== 'object') {
        var message = '请求传参应为 object 类型，但实际传了 ' + (typeof options) + ' 类型';
        throw new PlayError(message);
    }

    if (!options.src){
        var message = '无资源';
        var error = new PlayError(message);
        options.fail(error);
        return;
    }

    var process = options.process || noop;
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

    if (!player.src || player.src != options.src) {
        if (player.src){
            doStop(false);
        }
        doPlay();
    }else{
        doStop(true);
    }

    // 实际进行请求的方法
    function doPlay() {
        player.src = options.src;
        console.log("开始播放" + player.src);
        wx.playVoice({
            filePath: player.src,
            success: function(res){
                // success
                console.log("播放结束" + player.src);
                player.src = null,
                callSuccess.apply(null, arguments);
            },
            fail: callFail,
            complete: complete,
        })
    };

    // 实际进行请求的方法
    function doStop(isCallbackOn) {
        wx.stopVoice({
            success: function () {
                console.log("停止播放" + player.src);
                player.src = null;
                isCallbackOn ? callSuccess.apply(null, arguments) : noop();
            },
            fail: isCallbackOn ? callFail : noop,
            complete: isCallbackOn ? complete : noop,
        });
    };
};

module.exports = {
    PlayError: PlayError,
    playRecord: play,
};