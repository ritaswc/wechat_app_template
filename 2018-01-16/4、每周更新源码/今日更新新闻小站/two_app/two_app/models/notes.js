/**
 * Created by Aber on 17/3/10.
 */
"use strict";

module.exports = function (sequelize, DataTypes) {
    var _model = sequelize.define("notes", {
        listid: {type: DataTypes.UUID, primaryKey: true, comment: '笔记目录ID'}
        , userid: {
            type:DataTypes.STRING(100), comment: '用户ID', references: {
                model: sequelize.models.user
                , key:'userid'
            }
        }
        , picture: {type: DataTypes.STRING(150), comment: '封面'}
        , title: {type: DataTypes.STRING(16), allowNull: false, comment: '名称'}
        , type: {type: DataTypes.INTEGER,defaultValue: 0, comment: '类型'}
        , css: {type:DataTypes.STRING(150), comment: '样式'}
        , list: {type: DataTypes.TEXT("long"), comment: '多级目录'}
        , pass: {type: DataTypes.STRING(100), comment: '密码'}
        , limit: {type: DataTypes.INTEGER, defaultValue: 0, comment: '权限'}
        , is_pass:{type: DataTypes.INTEGER,defaultValue:0,comment: '是否需要密码'}
        , see:{type:DataTypes.INTEGER,comment: '浏览次数'}
        , abstract:{type:DataTypes.TEXT('tiny'),comment: '简介'}
        , ext: {type: DataTypes.TEXT("long"), comment: '扩展字段'}
    }, {
        indexes: [{
            name: 'listid'
            , fields: ['listid']
        }, {
            name: 'type'
            , fields: ['type']
        }]
        , classMethods: {
            associate: function (models) {
                _model.hasMany(models.discuss, {foreignKey: 'listid'});
                _model.belongsTo(models.user, {foreignKey: 'userid'});
            }
        }
    });
    return _model;
};