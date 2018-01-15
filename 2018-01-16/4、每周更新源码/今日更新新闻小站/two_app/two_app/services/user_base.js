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
  create:function (user) {
    //判断是否为空
    if (user != null) {
      return models.user.create(user);
    } else {
      return Promise.reject({message:"user对象不能为空"});
    }
  }

  //根据主键查找一条记录
  ,findOne: function (user,attributes) {
    if (typeof(user) === 'string') {
      return models.user.findOne({where: {userid: user},attributes:attributes});
    }
    else {
      if (user != null) {
        if (user.userid != null) {
          return models.user.findOne({where: {userid: user.userid},attributes:attributes});
        }
      }
    }
    return Promise.reject({message:"user对象不能为空"});
  }

  //根据对象查找一条记录
  ,findOne_obj:function(_obj,attributes){
    if (_obj){
      return models.user.findOne({where:_obj,attributes:attributes});
    }
    return Promise.reject({message:"user对象不能为空"});
  }

  
  ,findOne_phone: function (phone,attributes) {
    if (phone) {
      return models.user.findOne({where: {phone:phone},attributes:attributes});
    }
    else {
      return Promise.reject({message:"phone不能为空"});
    }
  }
  
  ,findOne_email: function (email,attributes) {
    if (email) {
      return models.user.findOne({where: {email:email},attributes:attributes});
    }
    else {
      return Promise.reject({message:"email不能为空"});
    }
  }
  
  ,findOne_openid: function (openid,attributes) {
    if (openid) {
      return models.user.findOne({where: {openid:openid},attributes:attributes});
    }
    else {
      return Promise.reject({message:"openid不能为空"});
    }
  }
  

  //根据主键更新记录
  ,update: function (user) {
    if (user != null) {
      if (user.userid != null) {
        return models.user.update(user, {where: {userid: user.userid}});
      }
    }
return Promise.reject({message:"user对象不能为空"});
  }

  //根据对象更新记录
  ,update_obj: function (user,obj) {
    if (user != null) {
      return models.user.update(user, {where: obj});
    }
return Promise.reject({message:"user对象不能为空"});
  }

  //根据主键删除记录
  ,delete: function (user) {
    if (typeof(user) === 'string') {
      return models.user.destroy({where: {userid: user}});
    }
    else {
      if (user != null) {
        if (user.userid != null) {
          return models.user.destroy({where: {userid: user.userid}});
        }
      }
    }
return Promise.reject({message:"user对象不能为空"});
  }

  //根据对象删除记录
    ,delete_obj: function (user) {

      if (user != null) {
        return models.user.destroy({where: user});
      }

return Promise.reject({message:"user对象不能为空"});
  }

  //列出前1000条记录
  ,list: function (limit) {
    if(typeof(limit)==='number')
    {
      if(limit>1000)
      {
        limit=1000;
      }
      return models.user.findAndCountAll({limit:limit});
    }else {
      if(limit==null)
          return models.user.findAndCountAll({limit:1000});
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
      return models['user'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.user.findAndCountAll({where: where, limit: limit,order: 'createdAt DESC'});
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
    return models.user.count({where: where});
  }

  
  //按照integral索引列出从n到n+size,size小于50，默认为20
  ,listPage_integral: function (offset,limit,integral,st,et,attributes) {
    var where = {integral:integral};
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
      return models['user'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.user.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_integral 参数类型有误"});
  }

  //按integral索引和时间范围统计created数
  ,count_integral: function(integral,st,et)
  {
    var where = {integral:integral};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.user.count({where: where});
  }
  
  //按照grank_integral索引列出从n到n+size,size小于50，默认为20
  ,listPage_grank_integral: function (offset,limit,grank_integral,st,et,attributes) {
    var where = {grank_integral:grank_integral};
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
      return models['user'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.user.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_grank_integral 参数类型有误"});
  }

  //按grank_integral索引和时间范围统计created数
  ,count_grank_integral: function(grank_integral,st,et)
  {
    var where = {grank_integral:grank_integral};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.user.count({where: where});
  }
  
  //按照phone索引列出从n到n+size,size小于50，默认为20
  ,listPage_phone: function (offset,limit,phone,st,et,attributes) {
    var where = {phone:phone};
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
      return models['user'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.user.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_phone 参数类型有误"});
  }

  //按phone索引和时间范围统计created数
  ,count_phone: function(phone,st,et)
  {
    var where = {phone:phone};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.user.count({where: where});
  }
  
  //按照email索引列出从n到n+size,size小于50，默认为20
  ,listPage_email: function (offset,limit,email,st,et,attributes) {
    var where = {email:email};
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
      return models['user'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.user.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_email 参数类型有误"});
  }

  //按email索引和时间范围统计created数
  ,count_email: function(email,st,et)
  {
    var where = {email:email};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.user.count({where: where});
  }
  
  //按照openid索引列出从n到n+size,size小于50，默认为20
  ,listPage_openid: function (offset,limit,openid,st,et,attributes) {
    var where = {openid:openid};
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
      return models['user'].findAndCountAll({where: where,attributes:attributes, limit: limit, offset: offset,order: 'createdAt DESC'});
    } else {
      if (offset == null)
        return models.user.findAndCountAll({where: where,attributes:attributes, limit: limit,order: 'createdAt DESC'});
    }

    return Promise.reject({message:"listPage_openid 参数类型有误"});
  }

  //按openid索引和时间范围统计created数
  ,count_openid: function(openid,st,et)
  {
    var where = {openid:openid};
    if (st != null || et != null) {
      where.createdAt = {
        $between: [st, et]
      }
    }
    return models.user.count({where: where});
  }
  
};
