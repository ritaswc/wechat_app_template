var restful = require('node-restful')
var mong = require('mongoose')

// app.jokes, /jokes
// app.images,/images

module.exports = function (app){
	var mongoose = restful.mongoose
  //databases
  mongoose.connect("mongodb://localhost/jsjokes")	

  //collection 1,
  var Jokes = app.blogs = restful.model(
		'jokes', mongoose.Schema({
    title: String,
    content: String,
    createdate : { type:Date, default: Date.now },
    pv: { type:Number, default: 0 },
    joke: {type:Number, default: 0 },
    unjoke: {type:Number,default: 0},
    published: {type:Number, default:1 },// 1 ,show. 0. hide
    comments: [{type : mongoose.Schema.ObjectId, ref : 'comments'}], // 1 ,show. 0. hide
    author  : [{type : mongoose.Schema.ObjectId, ref : 'accounts'}],
  }))
  .methods(['get','post','put','delete']);

  var accounts = restful.model(
    'accounts',mongoose.Schema({
    nickname: String,
    avatar: String,  //image url
    createdate : { type:Date, default: Date.now },
    level: {type: Number,default: 0},
  }));

  var comments = restful.model(
    'comments',mongoose.Schema({
    content : String,
    createdate : { type:Date, default: Date.now },
    author  : {type : mongoose.Schema.ObjectId, ref : 'accounts'},
    joke    : {type : mongoose.Schema.ObjectId, ref : 'jokes'},
  }))
  .methods(['post']);

  Jokes.after('get', function(req, res, next) {
    if (req.params.id){
				var	pv = res.locals.bundle.pv + 1
        res.locals.bundle.pv = pv
        Jokes.update({_id:req.params.id},{pv:pv},function(err, count, resp) {
        });
    }
    next();
  })

  Jokes.before('get', function(req, res, next) {
			req.body.author = req.user._id
      next();
  })

  Jokes.before('post', function(req, res, next) {
    if(req.isAuthenticated()){
      req.body['createdate'] = new Date()
      req.body['pv'] = 1
      req.body['joke'] = 0
      req.body['unjoke'] = 0
      req.body['published'] = 0
      req.body['author'] = req.user._id
      next();
    } else {
			res.sendStatus(403);
    } 
  })

  Jokes.before('put', function(req, res, next) {
    if(req.isAuthenticated()){
      
      next();
    } else {
			res.sendStatus(403);
    } 
  })

  Jokes.before('delete', function(req, res, next) {
    if(req.isAuthenticated()){
      next();
    } else {
			res.sendStatus(403);
    } 
  })

	Jokes.register(app,'/jokes')

  comments.before('post', function(req, res, next) {
    if(req.isAuthenticated()){
      req.body['createdate'] = new Date()
      req.body['author'] = req.user._id
      req.body['joke'] = req.query.id
      next();
    } else {
      res.sendStatus(403);
    }
  })

  comments.after('post', function(req, res, next) {
    if(req.isAuthenticated()){ 
      
      Jokes.findOneAndUpdate({_id:req.query.id},
                           {$push: { comments: res.locals.bundle._id} },function(err, count, resp){
            });
      next();
    } else {
      res.sendStatus(403);
    }
  })

  app.get('/comments' ,function (req,res){
    var jokeid = req.query.jokeid
    comments.find({joke:jokeid})
         // .sort('-_id')
         .populate({ path: 'author', select: {'avatar':1,'nickname':1,'level':1,'username':1} })
         .exec(function (err, comments) {
           if (err) return handleError(err);
           res.json(comments)
         })
   })

	comments.register(app,'/comments')

  // collection 2,
  var UploadImg = app.images = restful.model(
		'uploadimg', mongoose.Schema({
    url: String,
    createdate : { type:Date, default: Date.now },
  }))
  .methods(['get','post']);

	UploadImg.register(app,'/images')

}


/*
GET    /jokes
GET    /jokes/:id
POST   /jokes
PUT    /jokes/:id
DELETE /jokes/:id
*/
