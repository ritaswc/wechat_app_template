/**
 * Created by Aber on 17/3/13.
 */
var _ = require('underscore');
var uuid = require('uuid');
var mobicode = {};
mobicode.codetable = {};
var http = require('http');
function sendCode(code, mobi) {
    var querystring = require('querystring');
    var postData = {
        uid: 'EtATbUbnuKvI',
        pas: 'qudhe8td',
        mob: mobi,
        cid: 'yPSKs7Z66KbX',
        type: 'json',
        p1: code
    };
    var content = querystring.stringify(postData);
    var options = {
        host: 'api.weimi.cc',
        path: '/2/sms/send.html',
        method: 'POST',
        agent: false,
        rejectUnauthorized: false,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Content-Length': content.length
        }
    };
    var req = http.request(options, function (res) {
        res.setEncoding('utf8');
        res.on('data', function (chunk) {
            console.log(JSON.parse(chunk));
        });
        res.on('end', function () {
            console.log('over');
        });
    });
    req.write(content);
    req.end();
    /*
     注意：以上参数传入时不包括“<>”符号
     */
}

function newCode(params) {
    var code = {};

    var code_str = ""
    var timestamp = Date.parse(new Date()) / 1000;
    for (i = 0; i < 6; i++) {
        code_str += parseInt(Math.random() * 10);
    }
    code.id = uuid.v4().replace(/-/g, '');
    code.mobi = params.mobi;
    code.type = params.type;
    code.code = code_str;
    //code.code='1234';
    //验证码生命周期 180秒
    code.deadline = timestamp + 180;
    return code;
}

function clearOldCode() {
    var timestamp = Date.parse(new Date()) / 1000;
    _.each(mobicode.codetable, function (element, index, list) {
        if (element.deadline < timestamp) {
            delete list[index]
        }
    });

}
function checkMobiExist(mobi) {
    var timestamp = Date.parse(new Date()) / 1000;
    return _.find(mobicode.codetable, function (element) {
        return (element.mobi == mobi && element.deadline > timestamp);
    });
}
//创建短信验证码
mobicode.newCode = function (params) {

    clearOldCode();
    var code = checkMobiExist(params.mobi);
    if (code) {
        return 2;
    }
    else {
        code = newCode(params);
        mobicode.codetable[code.id] = code;
        //调用短信接口发送
        sendCode(code.code, code.mobi);
        return 1;
    }
}

mobicode.showAllCode = function () {
    console.log("showAllCode");
    console.log(JSON.stringify(mobicode.codetable))
    //for(var a in mobicode.codetable){
    //	console.log(a,mobicode.codetable[a]);
    //}
}

//检查验证码
mobicode.checkCode = function (params) {
    var code = _.find(mobicode.codetable, function (element) {
        return (element.mobi == params.mobi && element.code == params.code);
    });
    console.log("check:" + code);
    if (!code) {
        return false;
    }
    //消耗这个消息

    delete mobicode.codetable[code.id]
    if (code && code.deadline >= Date.parse(new Date()) / 1000) {
        return true;
    }
    else {
        return false;
    }
}

module.exports = mobicode