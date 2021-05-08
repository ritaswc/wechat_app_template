/*********************************************/
/**stage 1 weapp get user info**/

var WXBizDataCrypt = require('./WXBizDataCrypt') // from weixin demo
var appId = 'wxabd3a7d2a79ce7f4'      // app id
var SECRET = require('./weappsecret') // app secret
var https = require('https')
var sessionKey = ''

//openId, nickName, avatarUrl


function auth_weapp (code,iv,encryptedData,callback){
  var url = 'https://api.weixin.qq.com/sns/jscode2session?appid='+appId+'&secret='+SECRET+'&js_code='+code+'&grant_type=authorization_code'

  https.get(url,function(res){
   var body = ''
   res.on('data',function (d){
      body += d
   })
   res.on('end',function (){

    // get sessionkey
    sessionKey = JSON.parse(body).session_key

    console.log(sessionKey)

    var pc = new WXBizDataCrypt(appId, sessionKey)
    var data = pc.decryptData(encryptedData , iv)
    //console.log('解密后 data: ', data)

    callback(data)  //get user profile
   })

  }) //https.get

}



/**stage 2 use passport to session,cookie**/
/******************************************/
var util = require('util');
var passport = require('passport-strategy');

function WeappStrategy(options, verify) {
  options = options || {};

  if (!verify) {
    throw new TypeError('WeappStrategy required a verify callback');
  }

  if (typeof verify !== 'function') {
    throw new TypeError('_verify must be function');
  }


  passport.Strategy.call(this, options, verify);

  this.name = options.name || 'weapp';
  this._verify = verify;
  this._lang = options.lang || 'en';

}

/**
 * Inherit from 'passort.Strategy'
 */
util.inherits(WeappStrategy, passport.Strategy);

WeappStrategy.prototype.authenticate = function(req, options) {

  if (!req._passport) {
    return this.error(new Error('passport.initialize() middleware not in use'));
  }

  var self = this;

  options = options || {};

  function verified(err, user, info) {
        if (err) {
          return self.error(err);
        }
        if (!user) {
          return self.fail(info);
        }
        self.success(user, info);
  }
  try {
    auth_weapp(req.query.code,
             req.query.iv,
             decodeURIComponent(req.query.encryptedData) ,function (data){
             
             console.log('解密后 data: ', data)

            //openId, nickName, avatarUrl
						 var profile = {
                 openId: data['openId'],
                 nickName: data['nickName'],
                 avatarUrl: data['avatarUrl']
             }
             self._verify(profile, verified);
    }) //auth_weapp
  } catch (e) {
		console.log(e)
  }
  
}

module.exports = WeappStrategy;
