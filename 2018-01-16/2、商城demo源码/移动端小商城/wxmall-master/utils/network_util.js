/**
 * url 请求地址
 * success 成功的回调
 * fail 失败的回调
 */
function _get( url, success, fail ) {
    console.log("get request--->"+url);
    wx.request( {
        url: url,
        header: {
            'Content-Type': 'application/json'
        },
        success: function( res ) {
            console.log("get success--->" + JSON.stringify(res));
            success( res );
        },
        fail: function( res ) {
            console.log("get fail--->" + JSON.stringify(res));
            fail( res );
        }
    });
}
/**
 * url 请求地址
 * success 成功的回调
 * fail 失败的回调
 */
function _post(url,data, success, fail ) {
    var jsonDataStr = JSON.stringify(data);
    console.log("post request--->"+url+":"+ jsonDataStr);
    wx.request( {
        url: url,
        header: {
            'Content-Type': 'content-type x-www-form-urlencoded',
            'Accept': 'application/json',
        },
        method:'POST',
        data:'data='+data,
        success: function( res ) {
            console.log("post success--->" + JSON.stringify(res));
            success( res );
        },
        fail: function( res ) {
            console.log("post fail--->" + JSON.stringify(res));
            fail( res );
        }
    });
}
/**
 * url 请求地址
 * success 成功的回调
 * fail 失败的回调
 */
function _post_json(url,data, success, fail ) {
    var jsonDataStr = JSON.stringify(data);
    console.log("post_json request--->"+url+":"+ jsonDataStr);
    wx.request( {
        url: url,
        // header: {
        //     'Content-Type': 'application/json',
        //     'Accept': 'application/json',
        // },
        method:'POST',
        data:data,
        success: function( res ) {
            console.log("post_json success--->" + JSON.stringify(res));
            success( res );
        },
        fail: function( res ) {
            console.log("post_json fail--->" + JSON.stringify(res));
            fail( res );
        }
    });
}
module.exports = {
    _get: _get,
    _post:_post,
    _post_json:_post_json
}