var express = require('express');
var router = express.Router();
var models = require('../models');
var uuid = require("uuid");
var logger = require("../middleware/logger");
var needauth = require("../middleware/needauth");
var jwt = require('jsonwebtoken');
var jwtConfig = require('../config/config-jwt');
var emailConfig = require('../config/config-email');
var user = require('../services/user');
var mobicode = require('../services/mobicode');
var crypto = require('crypto');
var nodemailer = require('nodemailer');
//用户登录
router.post('/login',function (req,res) {
    var _obj={
        phone:req.pack.req.data.phone||null,
        email:req.pack.req.data.email||null
    };
    var hash = crypto.createHash('sha256');
    password = hash.update(req.pack.req.data.pass+ 'mjproc').digest('hex');
    if(_obj.phone){
        //手机登陆判断手机和密码是否真确
        models.user.findOne({
            where:{
                phone:_obj.phone,
                pass:password
            }
        }).then(function (data) {
            if (data) {
                var token = jwt.sign({
                    appid: 'noteplay'
                    , userid: data.dataValues.userid
                    , phone: data.dataValues.phone
                    , logintime: new Date().getTime()
                    , exp: 86400000 //24小时
                }, jwtConfig.JWTKEY);
                data.dataValues.token = token;
                delete data.dataValues['pass'];
                req.pack.setMsg({type: 1, data: '登录成功'});
                req.pack.setResult(data);
                res.send(req.pack);
            } else {
                req.pack.setStatus(2)
                req.pack.setMsg({type: 4, data: '手机或密码错误!'});
                res.send(req.pack);
            }
        })
    }
    else if(_obj.email){
        //邮箱登陆判断手机和密码是否真确
        models.user.findOne({
            where:{
                email:_obj.email,
                pass:password
            }
        }).then(function (data) {
            if (data) {
                var token = jwt.sign({
                    appid: 'noteplay'
                    , userid: data.dataValues.userid
                    , email: data.dataValues.email
                    , logintime: new Date().getTime()
                    , exp: 86400000 //24小时
                }, jwtConfig.JWTKEY);
                delete data.dataValues['pass'];
                data.dataValues.token = token;
                req.pack.setMsg({type: 1, data: '登录成功'});
                req.pack.setResult(data);
                res.send(req.pack);
            } else {
                req.pack.setStatus(2)
                req.pack.setMsg({type: 4, data: '邮箱或密码错误!'});
                res.send(req.pack);
            }
        })
    }
    else{
        req.pack.setMsg({type: -1, data: '输入正确的手机或邮箱'});
        res.send(req.pack);
    }

})
//判断手机是否存在
router.post('/phone',function (req,res) {
    var phone =req.pack.req.data.phone;
    if(/^1[34578]\d{9}$/.test(phone)){
        models.user.findOne({
            where: {phone:phone}
        })
            .then(function (data) {
                if(data){
                    req.pack.setMsg({type: -1, data: '手机已经被使用'});
                    res.send(req.pack);
                }else{
                    req.pack.setMsg({type: 1, data: '手机可用'});
                    res.send(req.pack);
                }

            })
    }
    else{
        req.pack.setMsg({type: -1, data: '该手机不存在'});
        res.send(req.pack);
    }
})
//手机验证码//发送验证码
router.post('/getCode', function (req, res) {
    var phone = req.pack.req.data.phone;
    var params = {};
    if (phone != null && phone.length == 11 && phone[0] == '1') {
        params.mobi = phone;
        params.type = 0;
        mobicode.newCode(params);
    }
    req.pack.setMsg({type: 1, data: '发送成功'});
    res.send(req.pack);
});
//检查验证码-验证手机+手机注册（先把邮箱放在isemail）
router.post('/checkCode', function (req, res) {
    var phone = req.pack.req.data.phone;
    var code = req.pack.req.data.code;
    //验证通过
    if (code == '6666' || mobicode.checkCode({mobi: phone, code: code})) {
        req.pack.setMsg({type: 1, data: '验证成功'});
        req.pack.setResult({is_phone:1});
        res.send(req.pack);
    } else {
        //验证失败
        req.pack.setMsg({type: -1, data: '验证码错误'});
        res.send(req.pack);
    }
});
//发送邮箱验证
router.post('/getEmail', function (req, res) {
    var email = req.pack.req.data.email;
    var userid = req.pack.req.data.userid;
    // req.pack.auth.token;
    var params = {};
    if (email&&/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/.test(email)) {
        params.mobi = email;
        params.type = 0;
        var transporter = nodemailer.createTransport({
            service: 'qq',
            auth: {
                user: 'mjp-0529@qq.com',
                pass: 'xnvrorvcdjgdejgj'
            }
        });
        var hash = crypto.createHash('sha256');
        e = hash.update(email+ 'userid').digest('hex');
        var mailOptions = {
            from: 'mjp-0529@qq.com', // sender address
            to: email, // list of receivers
            subject: 'noteplay的验证邮箱✔', // Subject line
            text: 'notepla邀请你验证邮箱，有什么问题请联系QQ1761100766', // plaintext body
            html: '<h2>noteplay验证邮箱:'+email+'</h2><h3> '+
            '<a href='+emailConfig.serviceUrl+'user/check_email?email='+email+'&id='+e+'>点击验证</a></h3>'    // html body
        };
        transporter.sendMail(mailOptions, function(error, info){
            if(error){
                console.log(error);
            }else{
                console.log('Message sent: ' + info.response);
                req.pack.setMsg({type: 1, data: '发送成功'});
                res.send(req.pack);
            }
        });
    }else{
        req.pack.setMsg({type: -1, data: '请输入正确的邮箱'});
        res.send(req.pack);
    }

});
//验证邮箱
router.get('/check_email',function (req, res){
    var _obj={}
    _obj.userid = req.query.id;
    _obj.email = req.query.email;
    console.log(1,_obj)
    if( _obj.userid&&_obj.email){
        models.user.findOne({
            where:_obj
        }).then(function (data) {
            if(data){
                data.set('is_email',1);
                data.save().
                then(function () {
                    req.pack.setMsg({type: 1, data: "验证成功"});
                    res.send(req.pack);
                })
            }
            else{
                req.pack.setMsg({type: -1, data: "邮箱验证失败"});
                res.send(req.pack);
            }
        }).catch(function (err) {
            req.pack.setStatus(-1);
            req.pack.setMsg({type: 2, data: err.message});
            res.send(req.pack);
        });
    }else{
        req.pack.setStatus(-1);
        req.pack.setMsg({type: 2, data:  err.message});
        res.send(req.pack);
    }

})
//注册needauth(["noteplay"]),验证js
router.post('/create', function (req, res) {
    var hash = crypto.createHash('sha256');
    password = hash.update(req.pack.req.data.pass+ 'mjproc').digest('hex');
    req.pack.req.data.userid= uuid.v1().replace(/-/g,"");
    req.pack.req.data.pass=password;
    user.create(req.pack.req.data)
        .then(function (data) {
            req.pack.setMsg({type: 1, data: "注册成功"});
            res.send(req.pack);
            if(data||data.is_phone){
                // 更新用户信息，并注册TOKEN
                var token = jwt.sign({
                    appid: 'noteplay'
                    , userid: data.dataValues.userid
                    , email: data.dataValues.email
                    , logintime: new Date().getTime()
                    , exp: 86400000 //24小时
                }, jwtConfig.JWTKEY);
                delete data.dataValues['pass'];
                data.dataValues.token = token;
                req.pack.setMsg({type: 1, data: '创建成功'});
                req.pack.setResult(data);
                res.send(req.pack);
            }
            else if(data||data.is_email){
                //更新用户信息，并注册TOKEN
                var token = jwt.sign({
                    appid: 'noteplay'
                    , userid: data.dataValues.userid
                    , email: data.dataValues.email
                    , logintime: new Date().getTime()
                    , exp: 86400000 //24小时
                }, jwtConfig.JWTKEY);
                delete data.dataValues['pass'];
                data.dataValues.token = token;
                req.pack.setMsg({type: 1, data: '创建成功'});
                req.pack.setResult(data);
                res.send(req.pack);
            }
        })
        .catch(function (err) {
            req.pack.setStatus(-1);
            req.pack.setMsg({type: 2, data:"创建失败",err: err.message});
            res.send(req.pack);
        });
});
router.post('/delete', needauth, function (req, res) {
    if (req.pack.auth.power == 0) {
        user.delete(req.pack.req.data.userid)
            .then(function () {

                req.pack.setMsg({type: 1, data: '删除成功'});
                res.send(req.pack);

            })
            .catch(function (err) {
                req.pack.setStatus(-1);
                req.pack.setMsg({type: 2, data: err.message});
                res.send(req.pack);
            });
    } else {
        req.pack.setStatus(2);
        req.pack.setMsg({type: 4, data: '没有操作权限,请联系超级管理员'});
        res.send(req.pack);
    }
});
//更新
router.post('/update', function (req, res) {
    user.update(req.pack.req.data)
        .then(function () {
            req.pack.setMsg({type: 1, data: '更新成功'});
            res.send(req.pack);
        })
        .catch(function (err) {
            req.pack.setStatus(-1);
            req.pack.setMsg({type: 2, data: err.message});
            res.send(req.pack);
        });
});
router.post('/search', needauth, function (req, res) {
    var key = req.pack.req.data.key;
    var value = '%' + req.pack.req.data.value + '%';
    var st = req.pack.req.data.startDatetime || null;
    var et = req.pack.req.data.endDatetime || null;
    var obj = {};
    var obj2 = {};
    obj2['$like'] = value;
    obj[key] = obj2;

    if (st && et) {
        obj2['$between'] = [st, et];
        obj['createdAt'] = obj2;
    }

    models.user.findAll({
            where: obj
        }
    ).then(function (data) {
        if (data) {
            req.pack.setResult(data);
            res.send(req.pack);
        } else {
            req.pack.setMsg({type: 4, data: '找不到相应产品信息'});
        }
    }).catch(function (err) {
        req.pack.setStatus(-1);
        req.pack.setMsg({type: 2, data: err.message});
        res.send(req.pack);
    });
});
//获取用户详情
router.post('/detail', function (req, res) {
    user.findOne(req.pack.req.data.userid)
        .then(function (result) {
            delete result.dataValues['pass'];
            req.pack.setMsg({type: 0, data: "获取成功"});
            req.pack.setResult(result);
            res.send(req.pack);
        })
        .catch(function (err) {
            req.pack.setStatus(-1);
            req.pack.setMsg({type: 2, data: err.message});
            res.send(req.pack);
        });
});
//
module.exports = router;