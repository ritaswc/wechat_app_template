/**
 * 小程序配置文件
 */

// 主机域名
var host = '15178378.t-otaku.com';

var config = {

    service: {
        host,

        // 登录地址，用于建立会话
        loginUrl: `https://${host}/?login/login`,

        // 登录地址，用于维持会话
        checkUrl: `https://${host}/?login/check`,

        // 上传接口，用于上传私语
        whisperUrl: `https://${host}/?whisper/whisper`,

        // 删除接口，用于删除私语
        deleteUrl: `https://${host}/?whisper/delete`,

        // 查看私语接口
        viewUrl: `https://${host}/?search/view`,
        
        // 搜索私语接口
        searchUrl: `https://${host}/?search/search`,
    }
};

module.exports = config;