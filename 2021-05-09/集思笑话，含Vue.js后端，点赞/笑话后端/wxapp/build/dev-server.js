require('./check-versions')()

var config = require('../../config')
if (!process.env.NODE_ENV) {
  process.env.NODE_ENV = JSON.parse(config.dev.env.NODE_ENV)
}

var opn = require('opn')
var path = require('path')
var express = require('express')
var webpack = require('webpack')
var logger   = require('morgan');
var app = express()

require('../common/common')(app) //set app
require('../models/user')(app) //user register , login 
require('../models/restful')(app) // connect mongodb jsjoke
require('../common/uploadimage')(app) //upload image
require('../passport-weapp-mongoose/weapplogin')(app) //weixin app login

app.get(["/",'/m'],function (req,res){
  res.sendFile(path.resolve(__dirname, '../src/m.html'));
})

var webpackConfig = process.env.NODE_ENV === 'testing'
  ? require('./webpack.prod.conf')
  : require('./webpack.dev.conf')

// default port where dev server listens for incoming traffic
var port = 9090 || process.env.PORT || config.dev.port
// automatically open browser, if not set will be false
var autoOpenBrowser = !!config.dev.autoOpenBrowser
// Define HTTP proxies to your custom API backend
// https://github.com/chimurai/http-proxy-middleware

var compiler = webpack(webpackConfig)

var devMiddleware = require('webpack-dev-middleware')(compiler, {
  publicPath: webpackConfig.output.publicPath,
  quiet: true
})

var hotMiddleware = require('webpack-hot-middleware')(compiler, {
  log: () => {}
})
// force page reload when html-webpack-plugin template changes
compiler.plugin('compilation', function (compilation) {
  compilation.plugin('html-webpack-plugin-after-emit', function (data, cb) {
    hotMiddleware.publish({ action: 'reload' })
    cb()
  })
})

app.use(logger('dev'));

// serve pure static assets
var staticPath = path.posix.join(config.dev.assetsPublicPath, config.dev.assetsSubDirectory)
app.use(staticPath, express.static('./static'))
app.use(express.static(path.resolve(__dirname,'../dist')));
app.use(express.static(path.resolve(__dirname,'../../third/ckeditor')));
app.use(express.static(path.resolve(__dirname,'../../third')));
app.use(express.static(path.resolve(__dirname,'../../uploads')));

app.get(['/admin/register'],function (req,res){
    res.sendFile(path.resolve(__dirname, '../src/register.html'));
})

app.get(['/admin/login'],function (req,res){
    res.sendFile(path.resolve(__dirname, '../src/login.html'));
})

app.get(["/admin/*"],function (req,res){
  if(req.isAuthenticated()){
    res.sendFile(path.resolve(__dirname, '../src/index.html'));
  }
  else {
    res.redirect('/admin/login');
  }
})

app.get(['/m/register','/m/login'],function (req,res){
    res.sendFile(path.resolve(__dirname, '../src/m.html'));
})


app.get(["/m/*"],function (req,res){
  if(req.isAuthenticated()){
    res.sendFile(path.resolve(__dirname, '../src/m.html'));
  }
  else {
    res.redirect('/m/login');
  }
})


// handle fallback for HTML5 history API
//app.use(require('connect-history-api-fallback')())

// serve webpack bundle output
app.use(devMiddleware)

// enable hot-reload and state-preserving
// compilation error display
app.use(hotMiddleware)


var uri = 'http://localhost:' + port

devMiddleware.waitUntilValid(function () {
  console.log('> Listening at ' + uri + '\n')
})

module.exports = app.listen(port, function (err) {
  if (err) {
    console.log(err)
    return
  }

  // when env is testing, don't need open it
  if (autoOpenBrowser && process.env.NODE_ENV !== 'testing') {
    opn(uri)
  }
})
