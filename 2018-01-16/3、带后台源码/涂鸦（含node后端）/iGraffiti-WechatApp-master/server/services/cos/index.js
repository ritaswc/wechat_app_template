const { cos, auth, conf } = require('qcloud_cos');
const config = require('../../config');

conf.setAppInfo(config.cosAppId, config.cosSecretId, config.cosSecretKey);

module.exports = Object.assign(cos, { auth, conf });