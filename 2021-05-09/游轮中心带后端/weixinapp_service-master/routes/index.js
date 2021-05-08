var settings = require('../settings');
var mysql = require('../models/db');
var request = require("request");

exports.servicedo = function(req, res) {
    res.setHeader("Access-Control-Allow-Origin", "*");
    var sql = req.params.sql;
    if (sql == "getCalByKey") {
        var d1 = req.param("date");
        var sql = "select * from cruise_cal where datestart = '"+d1+"'";
        console.log(sql);
        mysql.query(sql, function(err, result) {
            if (err) return console.error(err.stack);
            console.log(result);
            res.json(result);
        });
    }
}

exports.demo = function(req, res) {
        var sql = "select * from notice order by id desc";
        console.log(sql);
        mysql.query(sql, function(err, result) {
            if (err) return console.error(err.stack);
            console.log(result);
            res.send(result);
        });
}