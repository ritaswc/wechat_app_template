//app.js
App({
  timeout: 9*1000*60, //ping时间间隔
  bearer: 'Bearer',
  url: { 
    host: 'https://www.huixuemao.com/',
    versionUrl: 'apis/v2/',
    pingUrl: 'apis/v2/exam/ping/',
    logInUrl: 'apis/v2/signin/',
    logOffUrl: 'apis/v2/signout/',
    registerUrl: 'apis/v2/signup/', //注册
    categoriesUrl: 'apis/v2/exam/categories/',
    nodesUrl: 'apis/v2/exam/nodes/',
    questionsUrl: 'apis/v2/exam/questions/',
    questionsRandomUrl: 'apis/v2/exam/questions/random/',
    progressesUrl: 'apis/v2/exam/progresses/',
    progressUrl: 'apis/v2/exam/progress/',
    answersUrl: 'apis/v2/exam/answers/',
    answerHistoryUrl: 'apis/v2/exam/answer-history/',
    answerHistoriesUrl: 'apis/v2/exam/answer-histories/',
    favoritesUrl: 'apis/v2/exam/favorite/questions/',
    favoriteUrl: 'apis/v2/exam/favorite/question/',
    topicsUrl: 'apis/v2/exam/topics/',
    contentUrl: 'apis/v2/exam/content/'
  },
  ping: function(){
    if(!wx.getStorageSync('Authorization')) return;
    var that = this;
    wx.request({
      url: that.url.host + that.url.pingUrl,
      method: 'GET',
      header: {
        Authorization: wx.getStorageSync('Authorization'),
      },
      success: function(res){
        if(res.statusCode == '200') {
          var data = res.data;
          if(data.hasOwnProperty('Authorization')){
            console.log(data.Authorization);
            wx.setStorageSync('Authorization',data.Authorization);
          }
        } else {
          if(statusCode == '400' || statusCode == '401') {
            //无权限
            wx.showModal({
              title:'注意',
              content:'登陆过期，请重新登录',
              showCancel:false,
              confirmColor: '#00a5cf',
              success:function(res) {
                if (res.confirm) {
                  wx.redirectTo({url: url});
                }
              }
            });
          }      
        } //statusCode-else结束
      }
    });
  },
  onLaunch:function(){
    var that = this;
    that.ping();
    setInterval(function(){that.ping()}, that.timeout);
  },
  myJson: function(obj) {
    for (var i in obj) {
      if(typeof obj[i] == 'object'){
        var str = '';
        for (var j in obj[i]) {
          str += (j + ':' + obj[i][j].toString() + ',');
        }
        obj[i] = str.substring(0, str.length - 1);
      }
    }
    if (obj.Aid === "") {
      obj.Aid = ",";
    }
    return JSON.parse(JSON.stringify(obj));
  },
  toLetter: function(index) {
    if (index >= 0 && index < 26) return String.fromCharCode(index+65);
    else return '';
  },
  unauthorized: function(statusCode, url) {
    url = url || '../login/login';
    if(statusCode == '400' || statusCode == '401') {
      //无权限
      wx.showModal({
        title:'注意',
        content:'登陆过期，请重新登录',
        showCancel:false,
        confirmColor: '#00a5cf',
        success:function(res) {
          if (res.confirm) {
            wx.removeStorageSync('Authorization')
            wx.redirectTo({url: url});
          }
        }
      });
    }
  }
})

