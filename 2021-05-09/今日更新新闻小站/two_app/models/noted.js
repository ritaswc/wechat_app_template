/**
 * Created by Aber on 17/3/10.
 */
"use strict";

module.exports = function (sequelize, DataTypes) {

    var _model = sequelize.define("noted", {
        noteid: {type: DataTypes.UUID, primaryKey: true, comment: '笔记本ID'}
        , userid: {
            type: DataTypes.STRING(100), comment: '拥有者ID', references: {
                model: sequelize.models.user
                , key: 'userid'
            }
        }
        , picture: {type: DataTypes.STRING(150), comment: '封面'}
        , nickname: {type: DataTypes.STRING(16), allowNull: false, comment: '名称'}
        , type: {type: DataTypes.INTEGER,defaultValue: 0, comment: '类型'}
        , css: {type:DataTypes.STRING(150), comment: '样式'}
        , pass: {type: DataTypes.INTEGER, comment: '密码'}
        , limit: {type: DataTypes.INTEGER, defaultValue: 0, comment: '权限'}
        , is_pass:{type:DataTypes.BOOLEAN,defaultValue:false,comment: '是否需要密码'}
        , content:{type:DataTypes.TEXT("long"), comment: '内容'}

        , ext: {type: DataTypes.TEXT("long"), comment: '扩展字段'}

    }, {
        indexes: [{
            name: 'noteid'
            , fields: ['noteid']
        }, {
            name: 'userid'
            , fields: ['userid']
        }]
        , classMethods: {
            associate: function (models) {
                _model.hasMany(models.logs, {foreignKey: 'logid'});
                _model.hasMany(models.discuss, {foreignKey: 'discussid'});
                _model.belongsTo(models.user, {foreignKey: 'userid'});
            }
        }
    });
    return _model;
};