/**
 * Created by Wonderchief on 2017/1/16.
 */
/**
 * admin auth web json token
 * @returns {Function}
 */
module.exports = function (req, res, next) {

    console.log(req.url);

    if (req.url.indexOf("/login") >= 0) {
        next();
        return null;
    }
    else {
        res.redirect("./login.html");
    }


    if (req.pack.auth != null) {
        res.redirect("./login.html");
    }
    else {
        req.pack.setStatus(2);
        req.pack.setMsg({type: 2, data: '没有访问权限'});
        res.send(req.pack);
    }

    // var roles = null;

    //定义一个类似中间件的对象
    var handler = function () {
        //console.log(req.jwt);
        //console.log("role::::::" + req.jwt.role)


    };

};
