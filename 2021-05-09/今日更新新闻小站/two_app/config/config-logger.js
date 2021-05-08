/**
 * Created by Administrator on 2016/9/8.
 */
var path = require('path');

//log4js配置
var config = {
    log4js_config: {
        appenders: [
            {type: 'console'},//控制台输出
            {
                type: 'dateFile',//文件输出
                filename: __dirname + '/../logs/bbmis.log',
                pattern: '-yyyy-MM-dd',
                maxLogSize: 20480,
                alwaysIncludePattern: false
            }
        ],
        replaceConsole: true
    }
};
//console.log(__dirname+'logs/lejiang.log')
module.exports = config;
