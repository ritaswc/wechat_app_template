/**
 * Created by Wonderchief on 2017/1/6.
 * Legle co,.ltd.
 * This is a auto-generated code file.
 * 版权所有：广州聆歌信息科技有限公司
 */
var models = require('../models');
var Promise = require('bluebird');
var Moment = require('moment');

module.exports = {
  //创建新的记录
  create:function (note) {
    //判断是否为空
    if (note != null) {
      return models.note.create(note);
    } else {
      return Promise.reject({message:"note对象不能为空"});
    }
  }

  //根据主键查找一条记录
  ,findOne: function (note,attributes) {
    if (typeof(note) === 'string') {
      return models.note.findOne({where: {listid: note},attributes:attributes});
    }
    else {
      if (note != null) {
        if (note.listid != null) {
          return models.note.findOne({where: {listid: note.listid},attributes:attributes});
        }
      }
    }
    return Promise.reject({message:"note对象不能为空"});
  }

  //根据对象查找一条记录
  ,findOne_obj:function(_obj,attributes){
    if (_obj){
      return models.note.findOne({where:_obj,attributes:attributes});
    }
    return Promise.reject({message:"note对象不能为空"});
  }

  

  //根据主键更新记录
  ,update: function (note) {
    if (note != null) {
      if (note.listid != null) {
        return models.note.update(note, {where: {listid: note.listid}});
      }
    }
return Promise.reject({message:"note对象不能为空"});
  }

  //根据对象更新记录
  ,update_obj: function (note,obj) {
    if (note != null) {
      return models.note.update(note, {where: obj});
    }
return Promise.reject({message:"note对象不能为空"});
  }

  //根据主键删除记录
  ,delete: function (note) {
    if (typeof(note) === 'string') {
      return models.note.destroy({where: {listid: note}});
    }
    else {
      if (note != null) {
        if (note.listid != null) {
          return models.note.destroy({where: {listid: note.listid}});
        }
      }
    }
return Promise.reject({message:"note对象不能为空"});
  }

  //根据对象删除记录
    ,delete_obj: function (note) {

      if (note != null) {
        return models.note.destroy({where: note});
      }

return Promise.reject({message:"note对象不能为空"});
  }

  //列出前1000条记录
  ,list: function (limit) {
    if(typeof(limit)==='number')
    {
      if(limit>1000)
      {
        limit=1000;
      }
      return models.note.findAndCountAll({limit:limit});
    }else {
      if(limit==null)
          return models.note.findAndCountAll({limit:1000});
    }

    return Promise.reject({message:"list 参数类型有误"});
  }

  //根据日期范围内列出指定字段和，从n到n+size，size小于50，默认为20，没有日期范围则列出最新
  ,listPage: function (offset,limit,st,et,attributes) {
    var where = {};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    if (typeof(offset) === 'number') {
      if (limit > 50) {
        limit = 50;
      }
    }
    else {
      limit = 20;
    }
    if (limit == null)limit = 20;
    if (typeof(offset) === 'number') {
      return models['note'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.note.findAndCountAll({where: where, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage 参数类型有误"});
  }


  //按时间范围统计created数
  ,count: function(st,et)
  {
    var where={};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.note.count({where: where});
  }

  
  //按照listid索引列出从n到n+size,size小于50，默认为20
  ,listPage_listid: function (offset,limit,listid,st,et,attributes) {
    var where = {listid:listid};
    if (st != null || et != null) {
      where.createdAt = {
          $between: [st, et]
      }
    }
    if (typeof(offset) === 'number') {
      if (limit > 50) {
        limit = 50;
      }
    }
    else {
      limit = 20;
    }
    if (limit == null)limit = 20;
    if (typeof(offset) === 'number') {
      return models['note'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.note.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_listid 参数类型有误"});
  }

  //按listid索引和时间范围统计created数
  ,count_listid: function(listid,st,et)
  {
    var where = {listid:listid};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.note.count({where: where});
  }
  
  //按照userid索引列出从n到n+size,size小于50，默认为20
  ,listPage_userid: function (offset,limit,userid,st,et,attributes) {
    var where = {userid:userid};
    if (st != null || et != null) {
      where.createdAt = {
          $between: [st, et]
      }
    }
    if (typeof(offset) === 'number') {
      if (limit > 50) {
        limit = 50;
      }
    }
    else {
      limit = 20;
    }
    if (limit == null)limit = 20;
    if (typeof(offset) === 'number') {
      return models['note'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.note.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_userid 参数类型有误"});
  }

  //按userid索引和时间范围统计created数
  ,count_userid: function(userid,st,et)
  {
    var where = {userid:userid};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.note.count({where: where});
  }
  
  //按照userid索引列出从n到n+size,size小于50，默认为20
  ,listPage_userid: function (offset,limit,userid,st,et,attributes) {
    var where = {userid:userid};
    if (st != null || et != null) {
      where.createdAt = {
          $between: [st, et]
      }
    }
    if (typeof(offset) === 'number') {
      if (limit > 50) {
        limit = 50;
      }
    }
    else {
      limit = 20;
    }
    if (limit == null)limit = 20;
    if (typeof(offset) === 'number') {
      return models['note'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.note.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_userid 参数类型有误"});
  }

  //按userid索引和时间范围统计created数
  ,count_userid: function(userid,st,et)
  {
    var where = {userid:userid};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.note.count({where: where});
  }
  
};
