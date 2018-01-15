/**
 * Created by David on 2017/2/10.
 * 版权所有：广州聆歌信息科技有限公司
 * Legle co,.ltd.
 */
var customer = require('../services/customer');
var purchased_service = require('../services/purchased_service');
var service = require('../services/service');
var moment = require('moment');
var models = require('../models');

module.exports = function () {
    var roles = null;

    var handler = function (req, res, next) {

        if (req.pack.auth.appid == 'webapp' || req.pack.auth.appid == 'webcustomer' || req.pack.auth.appid == 'websales') {
            var customerid = (req.pack.auth.appid == 'websales') ? req.pack.req.data.customerid : req.pack.auth.customerid;

            customer.findOne(customerid)
                .then(function (data) {
                    if (data.dataValues.account_status != 1) {
                        req.pack.setStatus(2);
                        req.pack.setMsg({type: 4, msg: '该商家账户未开通！'});
                        res.send(req.pack);
                    } else {
                        return models.purchased_service.findAll({where: {customerid: customerid}});
                    }
                })
                .then(function (data) {

                    if (data == null) {
                        req.pack.setStatus(2);
                        req.pack.setMsg({type: 4, msg: '该商家未购买该服务！'});
                        res.send(req.pack);
                    } else {
                        var error_code = null;
                        var serviceid;

                        var now = moment().format("YYYY-MM-DD HH:mm:ss");

                        var i = 0;
                        var z = roles.length;

                        for (var j in data) {

                            serviceid = data[j].dataValues.serviceid.split('_');

                            if (serviceid[0] == roles[i]) {
                                var finish = moment(data[j].dataValues.finish_datetime).format("YYYY-MM-DD HH:mm:ss");

                                if (now > finish) {
                                    error_code = 1;
                                    serviceid = data[j].dataValues.serviceid;
                                    break;

                                } else if (i < z - 1) {
                                    i++;

                                } else {
                                    break;
                                }
                            } else {
                                error_code = 2;
                                serviceid = roles[i];
                                break;
                            }
                        }

                        if (error_code == 1) {

                            service.findOne(serviceid).then(function (data) {

                                if (data == null) {
                                    req.pack.setStatus(2);
                                    req.pack.setMsg({type: 2, msg: serviceid + ',该服务不存在!'});
                                    res.send(req.pack);
                                } else {
                                    req.pack.setStatus(2);
                                    req.pack.setMsg({type: 4, msg: data.dataValues.service_name + ',您的服务已过期!'});
                                    res.send(req.pack);
                                }
                            });
                        } else if (error_code == 2) {

                            service.findOne(serviceid).then(function (data) {

                                if (data == null) {
                                    req.pack.setStatus(2);
                                    req.pack.setMsg({type: 2, msg: serviceid + ',该服务不存在!'});
                                    res.send(req.pack);
                                } else {
                                    req.pack.setStatus(2);
                                    req.pack.setMsg({type: 4, msg: data.dataValues.service_name + ',您未购买该服务!'});
                                    res.send(req.pack);
                                }
                            });
                        } else if (error_code == null) {
                            next();
                        }
                    }

                })
        } else {
            next();
        }
    }

    //获取参数的个数，如果不等3嘅参数，就返回handler对象
    if (arguments.length != 3) {
        roles = arguments[0];
        return handler;
    }
    else {
        //执行这个中间件
        handler(arguments[0], arguments[1], arguments[2]);
    }
}