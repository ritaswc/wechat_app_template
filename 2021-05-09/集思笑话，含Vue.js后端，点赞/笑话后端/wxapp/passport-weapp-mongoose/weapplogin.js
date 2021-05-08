
var Account = require('../models/account')

var passport = require('passport')
  , WeappStrategy = require('./passport-strategy-weapp')
  ;

module.exports = function (app) {

  passport.use('weapp',new WeappStrategy({
    requireState: false
  }, function(profile, done){
    Account.findOne({weappid:profile['openId']},function(err,user){
      if (user) {
            return done(null, user); // user found, return that user
      } else {
            // if there is no user found with that facebook id, create them
        var user = new Account ()
        user.weappid  = profile['openId']
			  user.username = profile['openId']
			  user.nickname = profile['nickName']
        user.avatar   = profile['avatarUrl']

        user.save(function(err) {
           if (err) console.log(err);
              return done(err, user);
        });
      } // if...else

    }) // findOne
  })); //passport.use


  app.get('/api/weapplogin',passport.authenticate('weapp'),function (req,res){
  	res.end("ok")
  }) // app.get /api/weapplogin

}
