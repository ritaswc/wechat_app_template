//############ 该js没有使用, 数据库使用db_mysql.js ################
var settings = require('../settings');
var mysql = require('mysql');
module.exports = mysql.createPool({
    host: settings.db.host,
    user: settings.db.user,
    password: settings.db.password,
    database:settings.db.database,
    port: settings.db.port
});
