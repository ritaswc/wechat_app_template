//app.js

var whisper = require('./vendor/index');
var config = require('./config');

App({
  //小程序初始化
  onLaunch: function () {
    whisper.setLoginUrl(config.service.loginUrl);
  },
})