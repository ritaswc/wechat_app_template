/**
 * Created by Wonderchief on 2017/1/10.
 */
var _ = require('underscore');
/*
协议解析中间件
 */
var Protocol = function(req) {
    this.status = 0;
    this.msg = {type:0,data:null};
    this.auth = null;

    //把req对象中所有属性复制并覆盖
    this.req = _.extend({
        appid:'0'
        ,token:'0'
        ,core:{}
        ,data:{}
    },req || {});
    this.result = {};
};
Protocol.prototype.setStatus = function (value) {
    this.status = value;
};
Protocol.prototype.setMsg = function (msg) {
    this.msg = _.extend(msg);
};
Protocol.prototype.setResult = function (result) {
    this.result = result;
};
Protocol.prototype.toJSON = function () {

    return {status: this.status, msg: this.msg, result: this.result};
};

module.exports = Protocol;
module.exports.Protocol = Protocol;
module.exports.default = Protocol;