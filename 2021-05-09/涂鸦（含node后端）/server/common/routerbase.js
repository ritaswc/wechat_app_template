/**
 * 封装的路由公共基类，用于添加公用方法，不能直接实例化
 */

class RouterBase {
    constructor(req, res, next) {
        Object.assign(this, { req, res, next });
    }

    /**
     * 静态工厂方法：创建用以响应路由的回调函数
     */
    static makeRouteHandler() {
        return (req, res, next) => new this(req, res, next).handle();
    }

    /**
     * 子类实现该方法处理请求
     */
    handle() {
        throw new Error(`Please implement instance method \`${this.constructor.name}::handle\`.`);
    }
}

// 一个接口，调用路由
module.exports = RouterBase;