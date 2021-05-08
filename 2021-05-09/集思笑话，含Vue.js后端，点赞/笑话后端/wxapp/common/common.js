var path     = require('path')
var express  = require('express')
var cookieParser = require('cookie-parser');
var session = require('cookie-session');
var bodyParser = require('body-parser');
var methodOverride = require('method-override')
var url = require("url")
var onHeaders = require('on-headers')

module.exports = function (app) {

// the code for weapp get sessionid 
// the code run before session({keys......})
app.use(
    function  (req, res, next) {

     onHeaders(res, function setHeaders ( ) {
       //return sessionid 
       if(url.parse(res.req.url).pathname === '/api/weapplogin') {
         res.json(res._headers)
       }   
     })
     next();
   }
)


app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(express.query());
app.use(bodyParser.urlencoded({'extended':'true'}));
app.use(bodyParser.json({type:'application/vnd.api+json'}));
app.use(methodOverride());
app.use(session({keys: ['jsadmin1', 'jsadmin2', '...'],cookie:{maxAge:3600000000}}));
// 1000 hour

app.use(function(req, res, next) {
  res.header("Access-Control-Allow-Origin", "*");
  res.header("Access-Control-Allow-Credentials",true);
  res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept"); 
  next();
});



}
