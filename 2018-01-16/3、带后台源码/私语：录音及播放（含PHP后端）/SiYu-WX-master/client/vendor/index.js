var login = require('./lib/login');
var Session = require('./lib/session');
var request = require('./lib/request');
var upload = require('./lib/upload');
var record = require('./lib/record');
var play = require('./lib/play');

var exports = module.exports = {
    login: login.login,
    setLoginUrl: login.setLoginUrl,
    LoginError: login.LoginError,

    request: request.request,
    RequestError: request.RequestError,

    setSession: Session.set,
    getSession: Session.get,
    clearSession: Session.clear,

    uploadFile: upload.uploadFile,
    UploadError: upload.UploadError,

    startRecord: record.startRecord,
    stopRecord: record.stopRecord,
    getRecordDuration: record.getRecordDuration,
    getRecordSrc: record.getRecordSrc,
    RecordError: record.RecordError,

    playRecord: play.playRecord,
    PlayError: play.PlayError,
};