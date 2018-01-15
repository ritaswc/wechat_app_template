/**
 * Created by Wonderchief on 2017/1/12.
 */
//如何拦截json并处理数据，或者做逻辑判断或者数据处理？
//首先对你要定义一个中间件函数
var test = function (req, res, next) {
    var _send = res.json;

    res.json = function () {
        //然后获得参数表的第一个对象，就是json会返回的对象
        var jsonobj = arguments[0];

        //然后在此可以做一些逻辑处理
        //比如，打印对象出来到console
        console.log("参数表的第一个对象:" + JSON.stringify(jsonobj));
        //比如，操作对象，增加一些属性等
        jsonobj.ok = 0;
        //又比如，进行一些判断和统计
        if (jsonobj.status != null) {
            if (jsonobj.status == 0) {
                /*
                 conn.query("insert into XXXX");
                 */
                console.log(jsonobj.status);
            }
        }
        arguments[0] = jsonobj;
        return _send.apply(res, arguments);
    };
    next();
};
module.exports = test;