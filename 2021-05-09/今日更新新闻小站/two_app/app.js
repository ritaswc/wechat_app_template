var express = require('express');
var path = require('path');
var cookieParser = require('cookie-parser');
var bodyParser = require('body-parser');

var user = require('./routes/user');
var logs = require('./routes/logs');
var discuss = require('./routes/discuss');
var noted = require('./routes/noted');
var notes = require('./routes/notes');


var app = express();

// view engine setup
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');
app.set('logs', path.join(__dirname, 'logs'));

//跨域处理
app.all('*', require('./middleware/cors'));

// uncomment after placing your favicon in /public
//app.use(favicon(path.join(__dirname, 'public', 'favicon.ico')));
app.use(bodyParser.json({limit: '10000kb'}));
app.use(bodyParser.urlencoded({limit: '10000kb', extended: true}));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));

//引入各种应用的静态文件
app.use('/webpack', express.static(path.join(__dirname, 'webpack')));
app.use('/ueditor', express.static(path.join(__dirname, 'ueditor')));
//app.use('/webadmin', require("./middleware/adminauth"));

app.use(require('./middleware/sysframework'));

//然后在use route
app.use('/user', user);
app.use('/logs', logs);
app.use('/discuss', discuss);
app.use('/noted', noted);
app.use('/notes', notes);
var fibonacci = function (n) {
    // typeof NaN === 'number' 是成立的，所以要判断 NaN
    if (typeof n !== 'number' || isNaN(n)) {
        throw new Error('n should be a Number');
    }
    if (n < 0) {
        throw new Error('n should >= 0')
    }
    if (n > 10) {
        throw new Error('n should <= 10');
    }
    if (n === 0) {
        return 0;
    }
    if (n === 1) {
        return 1;
    }

    return fibonacci(n-1) + fibonacci(n-2);
};
var ueditor = require("ueditor")
var ueditors = require('./routes/ueditors');
app.use(bodyParser.urlencoded({
    extended: true
}))

app.use("/ueditor/ue", ueditor(path.join(__dirname, 'public'), function (req, res, next) {
    // ueditor 客户发起上传图片请求
    if (req.query.action === 'uploadimage') {
        var foo = req.ueditor;

        var imgname = req.ueditor.filename;

        var img_url = '/images/ueditor/';
        res.ue_up(img_url); //你只要输入要保存的地址 。保存操作交给ueditor来做
        res.setHeader('Content-Type', 'text/html');//IE8下载需要设置返回头尾text/html 不然json返回文件会被直接下载打开
    }
    //  客户端发起图片列表请求
    else if (req.query.action === 'listimage') {
        var dir_url = '/images/ueditor/';
        res.ue_list(dir_url); // 客户端会列出 dir_url 目录下的所有图片
    }
    // 客户端发起其它请求
    else {
        // console.log('config.json')
        res.setHeader('Content-Type', 'application/json');
        res.redirect('/ueditor/nodejs/config.json');
    }
}));
app.use("/ueditor/lists", ueditors.returnDatas_List);
app.use("/ueditor/biji", ueditors.returnDatas);
app.use("/ueditor/saves", ueditors.saveDatas);
// END 与之前一样
app.get('/fib', function (req, res) {
    // http 传来的东西默认都是没有类型的，都是 String，所以我们要手动转换类型
    var n = Number(req.query.n);
    try {
        // 为何使用 String 做类型转换，是因为如果你直接给个数字给 res.send 的话，
        // 它会当成是你给了它一个 http 状态码，所以我们明确给 String
        res.send(String(fibonacci(n)));
    } catch (e) {
        // 如果 fibonacci 抛错的话，错误信息会记录在 err 对象的 .message 属性中。
        // 拓展阅读：https://www.joyent.com/developers/node/design/errors
        res
            .status(500)
            .send(e.message);
    }
});
// catch 404 and forward to error handler
app.use(function (req, res, next) {
    var err = new Error('Not Found');
    err.status = 404;
    next(err);
});

// error handlers

// development error handler
// will print stacktrace
if (app.get('env') === 'development') {
    app.use(function (err, req, res, next) {
        res.status(err.status || 500);
        res.render('error', {
            message: err.message,
            error: err
        });
    });
}

// production error handler
// no stacktraces leaked to user
app.use(function (err, req, res, next) {
    res.status(err.status || 500);
    res.render('error', {
        message: err.message,
        error: {}
    });
});

module.exports = app;

/*
 -------  |                      |-----|                                                          |\   |
 |      |____      ___         |  _ /      ___       ___      |             ___     ___         | \  |   __    |     |
 |      |    |   /____\        |    \     /____\    /___    --|---         /   \    /           |  \ |  /  \ --|--   |__   ~   ___   ___
 |      |    |   \_____        |-----|    \____    \___/      |__          \___/   |            |   \|  \__/   |__   |  |  |  |  |  |___|
 Wonderchief    2017                                                                                                                 ___/
 */

