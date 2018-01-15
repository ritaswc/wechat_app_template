/**
 * Created by Aber on 17/3/13.
 */
var app = require('../app');
var supertest = require('supertest');
// 看下面这句，这是关键一句。得到的 request 对象可以直接按照
// superagent 的 API 进行调用
var request = supertest(app);
var should = require('should');
var _ = require('underscore');
describe('test/app.test.js', function () {
    // var reqpack={
    //     appid: "noteplay"
    //     , core: {}
    //     , data: null
    //     , token: '1123'
    // };
    // .query 方法用来传 querystring，.send 方法用来传 body。
    // 它们都可以传 Object 对象进去。
    // login
    //----- 注册
    it('手机验证后注册成功', function (done) {
        // auth('appid', 'webapp')
        request.post('/user/create')
            .send({
                data:{
                    title: 'mjp',
                    phone: '18826252271',
                    integral: '5',
                    grank: '1',
                    grank_integral: '1',
                    pass: 'mjp11111',
                    is_phone: '1'
                }
            })
            .end(function (err, res) {
                console.log(res.text.should.obj);
                done(err);
            });
    });
    it('邮箱注册成功还需验证', function (done) {
        request.post('/user/create')
            .auth('appid','noteplay')
            .send({
                data:{
                    title: 'mjp',
                    email: '18826252271@163.com',
                    integral: '5',
                    grank: '1',
                    grank_integral: '1',
                    pass: 'mjp11111'
                }
            })
            .end(function (err, res) {
                console.log(res.text.should.obj);
                done(err);
            });
    });
    it('电话登陆成功', function (done) {
        request.post('/user/login')
            .send({data:{phone:'18826252271',pass:'mjp11111'}})
            .end(function (err, res) {
                // 由于 http 返回的是 String，所以我要传入 '55'。
                console.log(res.text.should.obj);
                // should.not.exist(err);
                // done(err) 这种用法写起来很鸡肋，是因为偷懒不想测 err 的值
                // 如果勤快点，这里应该写成
                /*
                 should.not.exist(err);
                 res.text.should.equal('55');
                 */
                done(err);
            });
    });
    it('电话登陆错误', function (done) {
        request.post('/user/login')
            .send({data:{phone:'18826252271',pass:'mjp1111'}})
            .end(function (err, res) {
                console.log(res.text.should.obj);
                done(err);
            });
    });
    it('邮箱登陆', function (done) {
        request.post('/user/login')
            .send({data:{email:'18826252271@163.com',pass:'mjp11111'}})
            .end(function (err, res) {
                console.log(res.text.should.obj);
                done(err);
            });
    });
    // 注册
    it('电话已经存在', function (done) {
        request.post('/user/phone')
            .send({data:{phone:'18826252271'}})
            .end(function (err, res) {
                console.log(res.text.should.obj);
                done(err);
            });
        // request.post('/user/phone')
        //     .send({data:{phone:'18826252273'}})
        //     .end(function (err, res) {
        //         console.log(res.text.should.obj);
        //         done(err);
        //     });
    });
    it('电话可以使用', function (done) {
        request.post('/user/phone')
            .send({data:{phone:'18826252273'}})
            .end(function (err, res) {
                console.log(res.text.should.obj);
                done(err);
            });
    });
    //---发送验证码
    // it('发送验证码', function (done) {
    //     request.post('/user/getCode')
    //         .send({data:{phone:'18826252271'}})
    //         .end(function (err, res) {
    //             console.log(res.text.should.obj);
    //             done(err);
    //         });
    // });
    //----验证邮箱
    // it('发送验证邮箱', function (done) {
    //     request.post('/user/getEmail')
    //         .send({
    //             data:{
    //                 email: '18826252271@163.com'
    //                 ,userid:'70bdff40088211e7b37a6f366096ed3a'
    //         }})
    //         .end(function (err, res) {
    //             console.log(res.text.should.obj);
    //             done(err);
    //         });
    // });
    it('验证邮箱', function (done) {
        request.get('/user/check_email')
            .query(
                'email=18826252271@163.com&id=fe9c9cd0089711e78359abd2b5da1aad'
            )
            .end(function (err, res) {
                console.log(res.text.should.obj);
                done(err);
            });
    });

    //获取详情
    it('读取用户详情', function (done) {
        request.post('/user/detail')
            .auth('appid','noteplay')
            .send({
                data:{
                    userid:'fe9c9cd0089711e78359abd2b5da1aad'
                }
            })
            .end(function (err, res) {
                console.log(res.text.should.obj);
                done(err);
            });
    });
    //更新用户
    it('更新用户基本信息', function (done) {
        request.post('/user/update')
            .auth('appid','noteplay')
            .send({
                data:{
                    userid:'70bdff40088211e7b37a6f366096ed3a',
                    picture: 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489492971306&di=d158d0f63d4a96569b7277792c754f57&imgtype=0&src=http%3A%2F%2Fd.hiphotos.baidu.com%2Fimage%2Fpic%2Fitem%2F5882b2b7d0a20cf482c772bf73094b36acaf997f.jpg',
                    title: 'mjp',
                    sex: '0',
                    age: '18',
                    abstract:'简介',
                    signature:'个性签名'
                }
            })
            .end(function (err, res) {
                console.log(res.text.should.obj);
                done(err);
            });
    });
});
