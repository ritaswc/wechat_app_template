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
  create:function (discuss) {
    //判断是否为空
    if (discuss != null) {
      return models.discuss.create(discuss);
    } else {
      return Promise.reject({message:"discuss对象不能为空"});
    }
  }

  //根据主键查找一条记录
  ,findOne: function (discuss,attributes) {
    if (typeof(discuss) === 'string') {
      return models.discuss.findOne({where: {discussid: discuss},attributes:attributes});
    }
    else {
      if (discuss != null) {
        if (discuss.discussid != null) {
          return models.discuss.findOne({where: {discussid: discuss.discussid},attributes:attributes});
        }
      }
    }
    return Promise.reject({message:"discuss对象不能为空"});
  }

  //根据对象查找一条记录
  ,findOne_obj:function(_obj,attributes){
    if (_obj){
      return models.discuss.findOne({where:_obj,attributes:attributes});
    }
    return Promise.reject({message:"discuss对象不能为空"});
  }

  

  //根据主键更新记录
  ,update: function (discuss) {
    if (discuss != null) {
      if (discuss.discussid != null) {
        return models.discuss.update(discuss, {where: {discussid: discuss.discussid}});
      }
    }
return Promise.reject({message:"discuss对象不能为空"});
  }

  //根据对象更新记录
  ,update_obj: function (discuss,obj) {
    if (discuss != null) {
      return models.discuss.update(discuss, {where: obj});
    }
return Promise.reject({message:"discuss对象不能为空"});
  }

  //根据主键删除记录
  ,delete: function (discuss) {
    if (typeof(discuss) === 'string') {
      return models.discuss.destroy({where: {discussid: discuss}});
    }
    else {
      if (discuss != null) {
        if (discuss.discussid != null) {
          return models.discuss.destroy({where: {discussid: discuss.discussid}});
        }
      }
    }
return Promise.reject({message:"discuss对象不能为空"});
  }

  //根据对象删除记录
    ,delete_obj: function (discuss) {

      if (discuss != null) {
        return models.discuss.destroy({where: discuss});
      }

return Promise.reject({message:"discuss对象不能为空"});
  }

  //列出前1000条记录
  ,list: function (limit) {
    if(typeof(limit)==='number')
    {
      if(limit>1000)
      {
        limit=1000;
      }
      return models.discuss.findAndCountAll({limit:limit});
    }else {
      if(limit==null)
          return models.discuss.findAndCountAll({limit:1000});
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
      return models['discuss'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.discuss.findAndCountAll({where: where, limit: limit,order: 'createdAt DESC'});
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
    return models.discuss.count({where: where});
  }

  
  //按照sort索引列出从n到n+size,size小于50，默认为20
  ,listPage_sort: function (offset,limit,sort,st,et,attributes) {
    var where = {sort:sort};
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
      return models['discuss'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.discuss.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_sort 参数类型有误"});
  }

  //按sort索引和时间范围统计created数
  ,count_sort: function(sort,st,et)
  {
    var where = {sort:sort};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.discuss.count({where: where});
  }
  
  //按照discussid索引列出从n到n+size,size小于50，默认为20
  ,listPage_discussid: function (offset,limit,discussid,st,et,attributes) {
    var where = {discussid:discussid};
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
      return models['discuss'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.discuss.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_discussid 参数类型有误"});
  }

  //按discussid索引和时间范围统计created数
  ,count_discussid: function(discussid,st,et)
  {
    var where = {discussid:discussid};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.discuss.count({where: where});
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
      return models['discuss'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.discuss.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
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
    return models.discuss.count({where: where});
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
      return models['discuss'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.discuss.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
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
    return models.discuss.count({where: where});
  }
  
  //按照like索引列出从n到n+size,size小于50，默认为20
  ,listPage_like: function (offset,limit,like,st,et,attributes) {
    var where = {like:like};
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
      return models['discuss'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.discuss.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_like 参数类型有误"});
  }

  //按like索引和时间范围统计created数
  ,count_like: function(like,st,et)
  {
    var where = {like:like};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.discuss.count({where: where});
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
      return models['discuss'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.discuss.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
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
    return models.discuss.count({where: where});
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
      return models['discuss'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.discuss.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
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
    return models.discuss.count({where: where});
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
      return models['discuss'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.discuss.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
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
    return models.discuss.count({where: where});
  }
  
};
