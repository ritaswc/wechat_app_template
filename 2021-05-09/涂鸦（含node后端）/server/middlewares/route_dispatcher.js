/**
 * 通用路由分发器
 */

const express = require('express');
// path模块（获取路径）
const path = require('path');
// lodash模块（还不知道干什么的）
const _ = require('lodash');
// 读取config
const config = require('../config');
// 读取route？？
const routes = require('../routes');

// 定义路由选项，大小写敏感，严格路由
const routeOptions = { 'caseSensitive': true, 'strict': true };
// 创建一个新的路由对象，并使用路由选项
const routeDispatcher = express.Router(routeOptions);

// 对每个route，进行函数（=>就是function的意思，es6语法）,得看那里调用的
_.each(routes, function(route, subpath){
    // 创建route，应用路由选项
    const router = express.Router(routeOptions);

    // 函数内定义routePath，作用域为函数内部
    let routePath;

    // ignore `config.ROUTE_BASE_PATH` if `subpath` begin with `~`
    // 如果subpath第一个有~就忽略掉
    if (subpath[0] === '~') {
        // 路由路径
        routePath = subpath.slice(1);
    } else {
        // 否则路由路径为根路径+子路径
        routePath = config.ROUTE_BASE_PATH + subpath;
    }

    // /全局根路径/routes/  route ？？
    require(path.join(global.SERVER_ROOT, 'routes', route))(router);

    // 这个路由对象
    routeDispatcher.use(routePath, router, function(err, req, res, next){
        // mute `URIError` error，忽略URIError错误
        if (err instanceof URIError) {
            return next();
        }
        throw err;
    });
});

module.exports = routeDispatcher;