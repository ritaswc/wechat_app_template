/**
 * Created by Wonderchief on 2016/8/30.
 */
/**
 * 授权权限验证中间件
 * @returns {Function}
 */
module.exports = function () {

    var roles = null;

    //定义一个类似中间件的对象
    var handler = function (req, res, next) {
        console.log(req.pack,req.pack.req)
        if (req.pack.auth != null) {
            if (roles != null && roles.length > 0) {
                var flag = false;

                for (var i in roles) {
                    console.log(roles[i],req.pack.auth.appid)
                    if (roles[i] == req.pack.auth.appid) {
                        flag = true;
                        break;
                    }
                }
            }
            if (flag) {

                var now = new Date().getTime();
                var deadTime = parseInt(req.pack.auth.logintime)+parseInt(req.pack.auth.exp);
                if(now>deadTime){
                    req.pack.setStatus(2);
                    req.pack.setMsg({type: 4, data: '验证已过期，请重新登录！'});
                    res.send(req.pack);
                }else{
                    next();
                }
            }
            else {
                req.pack.setStatus(2);
                req.pack.setMsg({type: 2, data: '没有访问权限'});
                res.send(req.pack);
            }
        }
        else {
            req.pack.setStatus(2);
            req.pack.setMsg({type: 2, data: '没有访问权限'});
            res.send(req.pack);
        }
    };

    //获取参数的个数，如果不等3嘅参数，就返回handler对象
    if (arguments.length != 3) {
        roles = arguments[0];
        return handler;
    }
    else {
        //执行这个中间件
        handler(arguments[0], arguments[1], arguments[2]);
    }

};
