/**
 * Created by David on 2017/1/12.
 * Legle co,.ltd.
 * This is a auto-generated code file.
 * 版权所有：广州聆歌信息科技有限公司
 */

var log4js = require('log4js');
var config = require('../config/config-logger.js');
//配置log4js
log4js.configure(config.log4js_config);
var logger = log4js.getLogger('BBMIS');
//输出级别
logger.setLevel('debug');
module.exports = logger;