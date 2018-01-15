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
  create:function (logs) {
    //判断是否为空
    if (logs != null) {
      return models.logs.create(logs);
    } else {
      return Promise.reject({message:"logs对象不能为空"});
    }
  }

  //根据主键查找一条记录
  ,findOne: function (logs,attributes) {
    if (typeof(logs) === 'string') {
      return models.logs.findOne({where: {logid: logs},attributes:attributes});
    }
    else {
      if (logs != null) {
        if (logs.logid != null) {
          return models.logs.findOne({where: {logid: logs.logid},attributes:attributes});
        }
      }
    }
    return Promise.reject({message:"logs对象不能为空"});
  }

  //根据对象查找一条记录
  ,findOne_obj:function(_obj,attributes){
    if (_obj){
      return models.logs.findOne({where:_obj,attributes:attributes});
    }
    return Promise.reject({message:"logs对象不能为空"});
  }

  

  //根据主键更新记录
  ,update: function (logs) {
    if (logs != null) {
      if (logs.logid != null) {
        return models.logs.update(logs, {where: {logid: logs.logid}});
      }
    }
return Promise.reject({message:"logs对象不能为空"});
  }

  //根据对象更新记录
  ,update_obj: function (logs,obj) {
    if (logs != null) {
      return models.logs.update(logs, {where: obj});
    }
return Promise.reject({message:"logs对象不能为空"});
  }

  //根据主键删除记录
  ,delete: function (logs) {
    if (typeof(logs) === 'string') {
      return models.logs.destroy({where: {logid: logs}});
    }
    else {
      if (logs != null) {
        if (logs.logid != null) {
          return models.logs.destroy({where: {logid: logs.logid}});
        }
      }
    }
return Promise.reject({message:"logs对象不能为空"});
  }

  //根据对象删除记录
    ,delete_obj: function (logs) {

      if (logs != null) {
        return models.logs.destroy({where: logs});
      }

return Promise.reject({message:"logs对象不能为空"});
  }

  //列出前1000条记录
  ,list: function (limit) {
    if(typeof(limit)==='number')
    {
      if(limit>1000)
      {
        limit=1000;
      }
      return models.logs.findAndCountAll({limit:limit});
    }else {
      if(limit==null)
          return models.logs.findAndCountAll({limit:1000});
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
      return models['logs'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.logs.findAndCountAll({where: where, limit: limit,order: 'createdAt DESC'});
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
    return models.logs.count({where: where});
  }

  
  //按照logid索引列出从n到n+size,size小于50，默认为20
  ,listPage_logid: function (offset,limit,logid,st,et,attributes) {
    var where = {logid:logid};
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
      return models['logs'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.logs.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_logid 参数类型有误"});
  }

  //按logid索引和时间范围统计created数
  ,count_logid: function(logid,st,et)
  {
    var where = {logid:logid};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.logs.count({where: where});
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
      return models['logs'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.logs.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
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
    return models.logs.count({where: where});
  }
  
  //按照logid索引列出从n到n+size,size小于50，默认为20
  ,listPage_logid: function (offset,limit,logid,st,et,attributes) {
    var where = {logid:logid};
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
      return models['logs'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.logs.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_logid 参数类型有误"});
  }

  //按logid索引和时间范围统计created数
  ,count_logid: function(logid,st,et)
  {
    var where = {logid:logid};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.logs.count({where: where});
  }
  
  //按照noteid索引列出从n到n+size,size小于50，默认为20
  ,listPage_noteid: function (offset,limit,noteid,st,et,attributes) {
    var where = {noteid:noteid};
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
      return models['logs'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.logs.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_noteid 参数类型有误"});
  }

  //按noteid索引和时间范围统计created数
  ,count_noteid: function(noteid,st,et)
  {
    var where = {noteid:noteid};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.logs.count({where: where});
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
      return models['logs'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.logs.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
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
    return models.logs.count({where: where});
  }
  
};
