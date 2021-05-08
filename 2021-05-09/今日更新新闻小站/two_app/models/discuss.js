/**
 * Created by Aber on 17/3/10.
 */
"use strict";

module.exports = function (sequelize, DataTypes) {

    var _model = sequelize.define("discuss", {

        discussid: {type: DataTypes.UUID, primaryKey: true, comment: '评论ID'}
        , listid: {type: DataTypes.UUID,allowNull: true, comment: '笔记本',references: {
            model: sequelize.models.notes
            , key: 'listid'
        }}
        , noteid: {type: DataTypes.UUID, allowNull: true, comment: '笔记',references: {
            model: sequelize.models.noted
            , key: 'noteid'
        }}
        , userid: {type: DataTypes.STRING(100), allowNull: true, comment: '用户',references: {
            model: sequelize.models.user
            , key: 'userid'
        }}
        , visitor: {type: DataTypes.STRING(100),allowNull: false,defaultValue: '{visitor:""}', comment: '访问者'}
        , type: {type: DataTypes.INTEGER,defaultValue: 0, comment: '评论类型'}
        , like: {type: DataTypes.INTEGER,defaultValue: 0, comment: '点赞次数'}
        , lose: {type: DataTypes.INTEGER,defaultValue: 0, comment: '不赞成'}
        , lose_content: {type: DataTypes.TEXT,defaultValue: "", comment: '不赞成理由'}
        , sort: {type: DataTypes.INTEGER, defaultValue: 0, comment: '优先级'}
        , content:{type:DataTypes.TEXT, comment: '内容'}
        , ext: {type: DataTypes.TEXT("long"), comment: '扩展字段'}
    }, {
        indexes: [{
            name: 'sort'
            , fields: ['sort']
        }, {
            name: 'discussid'
            , fields: ['discussid']
        },
            {
            name: 'listid'
            , fields: ['listid']
        }, {
            name: 'noteid'
            , fields: ['noteid']
        }, {
            name: 'like'
            , fields: ['like']
        },]
        , classMethods: {
            associate: function (models) {
                _model.belongsTo(models.notes, {foreignKey: 'listid'});
                _model.belongsTo(models.noted, {foreignKey: 'noteid'});
                _model.belongsTo(models.user, {foreignKey: 'userid'});
            }
        }
    });
    return _model;
};