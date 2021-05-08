/**
 * 小程序配置文件
 */

// 此处主机域名修改成腾讯云解决方案分配的域名
var host = 'localhost:8888';

var config = {

    // 下面的地址配合云端 Demo 工作
    service: {
        host,

        // 登录地址，用于建立会话
        loginUrl: `http://${host}/login`,

        // 请求地址，用于测试会话
        requestUrl: `http://${host}/user`,

        // 信道服务地址
        tunnelUrl: `http://${host}/tunnel`,
    }
};

module.exports = config;