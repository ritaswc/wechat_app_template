var ajax = {
    get:function(url,fn){},
    post:function(url,data,fn){
        wx.request({
            url: url,
            data: data,
            method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
            header: {
                "Content-Type":"application/x-www-form-urlencoded"
            }, // 设置请求的 header
            success: function(res){
                // success
                console.log(res);
                fn(res);
            }
        });
    }
}

module.exports = ajax;