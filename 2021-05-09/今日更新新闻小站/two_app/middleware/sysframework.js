/**
 * Created by Wonderchief on 2017/1/10.
 */
var Protocol = require('./lib/protocol');
var jwt = require('jsonwebtoken');
var async = require('async');
var models = require('../models');
var jwtConfig = require('../config/config-jwt');

module.exports = function (req, res, next) {
    var pack = new Protocol(req.body);

    async.waterfall([function (cb) {
        //处理auth
        if (pack.req.hasOwnProperty('token') && pack.req.token.length > 0) {
            try {
                pack.auth = jwt.decode(pack.req.token, jwtConfig.JWTKEY);
                if (pack.req.appid != pack.auth.appid) {
                    pack.auth = null;
                }
                /*if (pack.req.core.userid != pack.auth.userid) {
                 pack.auth = null;
                 break;
                 }*/
                //如果在这里进行user的获取，那么在sequelize的回调函数里进行cb()，记得要提前return;
                /*models.user.fineOne({where: {userid: auth.userid}}).then(function (data) {
                 pack.user = data.dataValues;
                 cb();
                 });*/
                // return;
            }
            catch (e) {
                pack.auth = null;
            }
        }
        cb();
    }
        /* ,
         function () {
         //处理appid
         if (pack.req.appid == 'webapp') {
         //处理core
         //配置触发器需要获取对象的属性
         var trigger = [
         {name:userid, table: "user"}
         ,{name:customerid, table: "customer"}
         ];
         pack.core={};
         //循环实例化到core中
         async.each(trigger,function (obj,callkack) {
         if(pack.req.core[obj.name]==null || pack.req.core[obj.name] != pack.auth[obj.name])
         {
         callkack()
         }
         var o={};
         o.where={};
         o.where[obj.name]=pack.req.core[obj.name];

         var o2={};
         o2[obj.name]=pack.req.core[obj.name];
         models[obj.table].fineOne({where:o2}).then(function (data) {
         pack.core[obj.table]= data.dataValues;
         callkack();
         });
         })
         }

         if (pack.req.appid == 'webadmin') {

         }
         }*/

        , function () {
            req.pack = pack;
            next();
        }
    ], function (err) {
        pack.setStatus(-1);
        pack.setMsg(err);
        req.pack = pack;
        res.json(pack);
    });


    //TODO 根据需要进行系统级的处理,比如(请不要随意拦截)
    // if(true){
    //     pack.setStatus(1)
    //     res.json(pack);
    // }

};

