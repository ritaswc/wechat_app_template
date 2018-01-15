/**
 * Created by Administrator on 2017/1/9.
 */
"use strict";

module.exports = function (sequelize, DataTypes) {

    var _model = sequelize.define("user", {
        userid: {type: DataTypes.STRING(100), primaryKey: true, comment: '用户ID'}
        , picture: {type: DataTypes.STRING(150), comment: '头像'}
        , title: {type: DataTypes.STRING(16), allowNull: false, comment: '用户昵称'}
        , sex: {type: DataTypes.INTEGER,defaultValue: 0, comment: '性别'}
        , age: {type: DataTypes.INTEGER,defaultValue: 18, comment: '年龄'}
        , phone: {type: DataTypes.STRING(20), unique: true, comment: '联系电话'}
        , integral: {type: DataTypes.INTEGER,defaultValue: 0, comment: '用户积分'}
        , grank: {type: DataTypes.STRING(4),defaultValue: 0, comment: '等级'}
        , grank_integral: {type: DataTypes.INTEGER,defaultValue: 0, comment: '等级积分'}
        , email: {type: DataTypes.STRING(50), unique: true, comment: '邮箱'}
        , limit: {type: DataTypes.STRING(5),defaultValue: 0, comment: '权限'}
        , limit_time: {type: DataTypes.STRING(50), allowNull: true, comment: '权限到期时间'}
        , code: {type: DataTypes.STRING(100),defaultValue: 0,  comment: '邀请码'}
        , pass: {type: DataTypes.STRING(100),allowNull: false, comment: '密码'}
        , is_phone:{type:DataTypes.INTEGER,defaultValue: 0,comment: '手机验证'}
        , is_email:{type:DataTypes.INTEGER,defaultValue: 0,comment: '邮箱验证'}
        , openid: {type: DataTypes.STRING(50), unique: true,allowNull: true, comment: '微信登陆'}
        , abstract:{type:DataTypes.TEXT('tiny'),comment: '简介'}
        , signature:{type:DataTypes.TEXT('tiny'),comment: '个性签名'}
        , register_way: {type: DataTypes.INTEGER,defaultValue: 0, comment: '注册方式'}
        , ext: {type: DataTypes.TEXT("long"), comment: '扩展字段'}
    }, {
        indexes: [ {
            name: 'grank_integral'
            , fields: ['grank_integral']
        }, {
            name: 'phone'
            , fields: ['phone']
        }, {
            name: 'email'
            , fields: ['email']
        }, {
            name: 'openid'
            , fields: ['openid']
        }]
        , classMethods: {
            associate: function (models) {
                _model.hasMany(models.logs, {foreignKey: 'userid'});
                _model.hasMany(models.noted, {foreignKey: 'userid'});
                _model.hasMany(models.notes, {foreignKey: 'userid'});
                _model.hasMany(models.discuss, {foreignKey: 'userid'});
            }
        }
    });
    return _model;
};