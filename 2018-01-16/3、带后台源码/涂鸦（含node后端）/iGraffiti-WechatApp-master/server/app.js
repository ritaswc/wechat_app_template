// 需要globals.js
require('./globals');
// 需要http模块
const http = require('http');
// 需要express模块
const express = require('express');
// 需要body-parser模块，用于解析http请求
const bodyParser = require('body-parser');
// 需要express的日志模块morgan
const morgan = require('morgan');
// 需要读取配置config.js，config是个对象
const config = require('./config');
// 创建app这个Express应用
const app = express();

// query parser使用simple
app.set('query parser', 'simple');
// 是否允许区分大小写
app.set('case sensitive routing', true);
// 具体说明默认调用名称
app.set('jsonp callback name', 'callback');
// 严格路由
app.set('strict routing', true);
// 设置代理
app.set('trust proxy', true);
// 关闭powered-by
app.disable('x-powered-by');

// 记录请求日志
app.use(morgan('tiny'));

// parse `application/x-www-form-urlencoded`
app.use(bodyParser.urlencoded({ extended: true }));

// parse `application/json`
app.use(bodyParser.json());

// 调用通用路由
app.use(require('./middlewares/route_dispatcher'));

// 打印异常日志
process.on('uncaughtException', error => {
    console.log(error);
});

// 启动server
http.createServer(app).listen(config.port, () => {
    console.log('Express server listening on port: %s', config.port);
});
