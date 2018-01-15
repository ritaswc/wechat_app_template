
var express = require('express');
var router = express.Router();
var models = require('../models');
var uuid = require("uuid");
var logger = require("../middleware/logger");
var needauth = require("../middleware/needauth");
var noted = require('../services/noted');

router.post('/create', needauth, function (req, res) {
    var _obj = {
        noteid: uuid.v1().replace(/-/g,""),
        listid: req.pack.req.data.listid,
        nickname: req.pack.req.data.nickname||"新建笔记",
        userid: req.pack.req.data.userid,
        type: req.pack.req.data.type || 0,
        limit: req.pack.req.data.limit || 0,
        content: req.pack.req.data.content || null,
        ext: req.pack.req.data.ext || null
    };
    if(req.pack.req.data.picture){
        _obj.picture=req.pack.req.data.picture
    }
    if(req.pack.req.data.css ){
        _obj.css=req.pack.req.data.css
    }
    if(req.pack.req.data.is_pass){
        _obj.is_pass=req.pack.req.data.is_pass
        _obj.pass=req.pack.req.data.pass
    }
    noted.create(_obj)
        .then(function () {
            req.pack.setMsg({type: 1, data: '创建成功'});
            res.send(req.pack);
        })
        .catch(function (err) {
            req.pack.setStatus(-1);
            req.pack.setMsg({type: 2, data: err.message});
            res.send(req.pack);
        });
});

router.post('/delete', needauth, function (req, res) {
    if (req.pack.auth.power == 0) {
        noted.delete(req.pack.req.data.listid)
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

router.post('/update', needauth, function (req, res) {
    var _obj = {
        listid: req.pack.req.data.listid || null,
        picture: req.pack.req.data.picture || null,
        nickname: req.pack.req.data.nickname || null,
        type: req.pack.req.data.type || null,
        css: req.pack.req.data.css || null,
        pass: req.pack.req.data.pass || null,
        limit: req.pack.req.data.limit || null,
        is_pass: req.pack.req.data.is_pass || null,
        content: req.pack.req.data.content || null,
        userid: req.pack.req.data.userid || null,
        ext: req.pack.req.data.ext || null,
    };
    noted.update(_obj)
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

    models.noted.findAll({
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

router.post('/listPage', needauth, function (req, res) {

    var offset = parseInt(req.pack.req.data.offset);
    var limit = parseInt(req.pack.req.data.limit) || 20;
    var st = req.pack.req.data.startDatetime || null;
    var et = req.pack.req.data.endDatetime || null;

    noted.listPage(offset, limit, st, et)
        .then(function (data) {

            req.pack.setResult(data)
            res.send(req.pack);
        })
        .catch(function (err) {

            req.pack.setStatus(-1);
            req.pack.setMsg({type: 2, data: err.message});
            res.send(req.pack);
        });
});

router.post('/detail', function (req, res) {

    noted.findOne(req.pack.req.data.listid)
        .then(function (result) {
            req.pack.setResult(result);
            res.send(req.pack);
        })
        .catch(function (err) {
            req.pack.setStatus(-1);
            req.pack.setMsg({type: 2, data: err.message});
            res.send(req.pack);
        });
});

module.exports = router;