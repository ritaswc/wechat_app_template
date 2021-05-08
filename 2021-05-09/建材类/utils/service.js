

function request(url,data,success,fail,complete){
     var app=getApp();
     var host=app.config.host;
     wx.request({
       url: host+url,
       data: data,
       method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
       header: {
         "Content-Type":"application/x-www-form-urlencoded"
       }, // 设置请求的 header
       success: function(res){
         if(typeof success == "function"){
             success(res);
         }
       },
       fail: function() {
         if(typeof fail == "function"){
             fail();
         }
       },
       complete: function() {
         if(typeof complete == "function"){
             complete();
         }
       }
     })
}

module.exports = {
  request:request
}

