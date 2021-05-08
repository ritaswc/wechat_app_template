var passport = require('passport');
var Account = require('./account');
var router = require('express').Router();
var extend = require('util')._extend;

router.post('/register', function(req, res, next) {
  d = new Date()
  Account.register(new Account({username: req.body.username,createdate:d,level:0}), req.body.password, function(err) {
    if (err) {
      console.log('error while user register!', err);
      return next(err);
    }

    console.log('user registered!');
     //auto login
    passport.authenticate('local')(req, res, function () {
             res.redirect('/m/my');
    })
  });
});


router.post('/login', passport.authenticate('local'), function(req, res) {
  res.redirect('/m/my');
});

router.get('/logout', function(req, res) {
  req.logout();
  res.redirect('/');
});

router.get('/api/my', function(req, res) {

  if (!req.user)
    return res.json({username:'Joker'})

  var user = extend({},req.user['_doc'])

  user['_id'] = 'null'
	res.json(user)
});

router.post('/api/my', function(req,res) {
		if (!req.user)
      res.end("请登录")

    var upinfo = {}
    upinfo.nickname = req.body['nickname']
    upinfo.avatar   = req.body['avatar']
    Account.update({_id:req.user.id},upinfo,function(err, count, resp) {
                  });

    res.end("ok")
})

module.exports = router;

