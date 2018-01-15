/**
 * Created by Aber on 17/3/10.
 */
"use strict";

module.exports = function (sequelize, DataTypes) {

    var _model = sequelize.define("logs", {
        logid: {type: DataTypes.UUID, primaryKey: true, comment: '更改ID'}
        , noteid: {type: DataTypes.UUID,  comment: '笔记',references: {
            model: sequelize.models.noted
            , key: 'noteid'
        }}
        , userid: {type: DataTypes.STRING(100),  comment: '用户',references: {
            model: sequelize.models.user
            , key: 'userid'
        }}
        , content:{type:DataTypes.TEXT, comment: '更改前的内容'}
        , content_update:{type:DataTypes.STRING(100), comment: '备注'}
        , content_type:{type:DataTypes.STRING(10), comment: '类型'}
        , ext: {type: DataTypes.TEXT("long"), comment: '扩展字段'}
    }, {
        indexes: [{
            name: 'logid'
            , fields: ['logid']
        }, {
            name: 'userid'
            , fields: ['userid']
        }]
        , classMethods: {
            associate: function (models) {
                _model.belongsTo(models.noted, {foreignKey: 'noteid'});
                _model.belongsTo(models.user, {foreignKey: 'userid'});
            }
        }
    });
    return _model;
};