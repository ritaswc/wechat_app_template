/**
 * Created by lingge on 2016/8/25.
 */
"use strict";
// delete require.cache;
var fs = require("fs");
var path = require("path");
var Sequelize = require("sequelize");
var env = process.env.NODE_ENV || "development";
var config = require(path.join(__dirname, '..', 'config', 'config-sequelize'))[env];
var sequelize = new Sequelize(config.database, config.username, config.password, config);
var db = {};
// delete require.cache;
fs.readdirSync(__dirname)
    .filter(function (file) {
        return (file.indexOf(".") !== 0) && (file !== "index.js");
    })
    .forEach(function (file) {
        var model = sequelize.import(path.join(__dirname, file));
        db[model.name] = model;

    });
// db.user=sequelize.import(require('./user');
//  sequelize.import(require('./user');
//  sequelize.import(require('./order');
//  sequelize.import(require('./share');
//  Object.keys(sequelize.models).forEach(function(modelName) {
//  console.log(1,sequelize.models[modelName])
//  db[modelName].sync();
//  });
Object.keys(db).forEach(function (modelName) {
    // console.log(db[modelName])
    if ("associate" in db[modelName]) {
        // console.log(db[modelName])
        db[modelName].associate(db);
    }
});


db.user.sync()
    .then(function () {
        return db.noted.sync();
    }).then(function () {
    return db.notes.sync();
    }).then(function () {
    return db.logs.sync();
}).then(function () {
    return db.discuss.sync()
});

// sequelize.sync();
db.sequelize = sequelize;
db.Sequelize = Sequelize;
module.exports = db;