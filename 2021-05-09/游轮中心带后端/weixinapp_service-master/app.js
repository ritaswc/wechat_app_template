var version = require('./version.js').version;
var express = require('express');
var http = require('http');
var https = require('https');
var path = require('path');
var formidable = require("formidable");
var favicon = require('static-favicon');
//var logger = require('morgan');
var cookieParser = require('cookie-parser');
var bodyParser = require('body-parser');
var crypto = require('crypto');

var partials = require('express-partials');
var flash = require('connect-flash');

var routeCtrl = require("./routes/ctrl");
controllers = require('./routes/index');
var routes = require('./routes');
var nodeUtil = require('util');
var settings = require('./settings.js');
var sslSettings = require('./ssl/settings.js');

var later = require('later');

var app = express();
nodeUtil.log('Process environment: ' + process.env.NODE_ENV);
if ( app.get('env') === 'development' ){
    nodeUtil.log('express using development environment');
    settings.development = true;
} else {
    nodeUtil.log('express using production environment');
    settings.development = false;
}

// view engine setup
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');
app.use(partials());
app.use(flash());

app.use(express.bodyParser({uploadDir:'./public/files'}));
app.use(favicon());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded());

//----------- cookies and session ------------------
app.use(cookieParser());
app.use(express.session({ secret: "keyboard cat" }));
/*
var session = require('express-session');
if ( settings.development && settings.is_local ) {
    //app.use(express.session({secret: "secret cds 1.0"}));
    nodeUtil.log('session store in-memory');
    permission.config.session.store = new session.MemoryStore();
    permission.config.cookie.name = permission.config.cookie.name + version;
    permission.config.cookie.secret = permission.config.cookie.secret + version;
} else if ( settings.development ) { // test server
    nodeUtil.log('test server: session store using redis');
    var RedisStore = require('connect-redis')(session);
    var redisClient = require("redis").createClient();
    permission.config.session.store = new RedisStore({host: 'localhost', port: 6379, client: redisClient});
    _cleanSessions(redisClient);//force user login again
    permission.config.cookie.name = permission.config.cookie.name + version;
    permission.config.cookie.secret = permission.config.cookie.secret + version;
} else { // production server
    nodeUtil.log('production server: session store using redis');
    var RedisStore = require('connect-redis')(session);
    var redisClient = require("redis").createClient();
    permission.config.session.store = new RedisStore({host: 'localhost', port: 6379, client: redisClient});
    _cleanSessions(redisClient);//force user login again
    permission.config.cookie.name = 'HYLcds' + version;
    permission.config.cookie.secret = '_HYLcds@' + version;
}
app.use(session({
    store: permission.config.session.store,
    name: permission.config.cookie.name,
    secret: permission.config.cookie.secret,
    resave: true,
    saveUninitialized: true,
    cookie: { secure: true }//, maxAge: 60000 }
}));*/
//-------------- End cookies and session ----------------

app.use(express.static(path.join(__dirname, 'public')));
//app.use(express.router(routes));
routeCtrl(app, controllers);

/// catch 404 and forwarding to error handler
/*
app.use(function(req, res, next) {
    var err = new Error('Not Found');
    err.status = 404;
    return next(err);
});
*/

///---------- error handlers ------------
// handler implemented by own: ./routes/error.js

// express error handler plugin
// development error handler
// will print stacktrace
if (settings.development) {
    app.use(function(err, req, res, next) {
        if( nodeUtil.isError(err) ) {
            nodeUtil.error(err.message + "[" + err.status + ':' + err.stack+"]");
            res.render('errorpage', {
                message: err.message,
                error: err
            });
        } else if (err ){
            nodeUtil.error(err);
            res.render('errorpage', {
                message: err,
                error: {}
            });
        }   
    });
} else {

    // production error handler
    // no stacktraces leaked to user
    app.use(function(err, req, res, next) {
        if( nodeUtil.isError(err) ) {
            nodeUtil.error(err.message + "[" + err.status + ':' + err.stack+"]");
            res.render('errorpage', {
                message: err.message,
                error: {}
            });
        } else if (err ){
            nodeUtil.error(err);
            res.render('errorpage', {
                message: err,
                error: {}
            });
        }    
    });
}
// ------------- end error handlers --------------

//https
var httpsPort = settings.https;//1226;
var server = https.createServer(sslSettings, app).listen(httpsPort);
var server = http.createServer(app).listen(1227);
nodeUtil.log('Express server started');
nodeUtil.log('App Version: ' + version);
//socket.io
//require('./routes/socket/socket.js').listen(server);

//forwards http to https

var httpapp = express();
httpapp.use(function redirectHttps(req, res, next) {
    if ( req.headers && req.headers.host ) {
    	var idx = req.headers.host.indexOf(':');
    	if ( idx > 0 ) { //local development
    		res.redirect('https://' + req.headers.host.substring(0, idx) + ':' + httpsPort + req.url);
    	} else {
    		res.redirect('https://' + req.headers.host + req.url);
    	}
    }
});
httpapp.listen(settings.http);

process.on('uncaughtException', function(err){
	nodeUtil.error('uncaughtException: ' + err.stack);
});


module.exports = app;

// clean all sessions stored in redis
function _cleanSessions(redisClient){
    redisClient.keys("sess:*", function(err, key) {
        redisClient.del(key, function(err) {
        });
    });
}
// clean all data stored in redis
function _cleanRedis(redisClient){
    redisClient.flushdb();
}
