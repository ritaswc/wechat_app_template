var restful = require('node-restful')
var mong = require('mongoose')

// app.jokes, /jokes
// app.images,/images
var LEVEL_JOKE = 1;
var LEVEL_COMMENT = 10;


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
  .methods(['post','delete']);

  var accounts = restful.model(
    'accounts',mongoose.Schema({
    nickname: String,
    avatar: String,  //image url
    createdate : { type:Date, default: Date.now },
    level: {type: Number,default: 0},
    weappid: {type: String},
  }));

  var comments = restful.model(
    'comments',mongoose.Schema({
    content : String,
    createdate : { type:Date, default: Date.now },
    author  : {type : mongoose.Schema.ObjectId, ref : 'accounts'},
    joke    : {type : mongoose.Schema.ObjectId, ref : 'jokes'},
  }))
  .methods(['post']);

  app.get('/api/jokes' ,function (req,res){
    var l = 0
    var s = 0
    if (req.query.limit){
      l = req.query.limit
    }

    if (req.query.skip){
      s = req.query.skip
    }

    Jokes.find()
         .limit(l)
         .skip(s)
         .sort('-_id')
         .populate({ path: 'author', select: {'avatar':1,'nickname':1,'level':1,'username':1} })
         .populate({ path: 'comments',
                     // options: {sort: {'_id': -1 }}, 
                     populate: {path: 'author', select: {'nickname':1,'username':1}}})
         .exec(function (err, jokes) {
           if (err) return handleError(err);
           res.json(jokes)
         })
   })
 
   // get jokes end

   // get use own jokes
   app.get('/api/my/jokes',function(req,res){
     if(!req.isAuthenticated()){
        return res.json({"err":"need login"})
     } else {
       Jokes.find({author:[req.user._id]})
              .sort('-_id')
              .populate({ path: 'author', select: {'avatar':1,'nickname':1,'level':1,'username':1} })
              .populate({ path: 'comments',
                     // options: {sort: {'_id': -1 }}, 
                     populate: {path: 'author', select: {'nickname':1,'username':1}}})
              .exec(function (err, jokes) {
                if (err) return handleError(err);
                res.json(jokes)
              })
     }
   })
 
   // joke or unjoke
   app.get('/api/jokes/:id',function(req,res){

      Jokes.findOne({'_id': req.params['id']})
           .populate({ path: 'author', select: {'avatar':1,'nickname':1,'level':1,'username':1} })
           .exec(function(err,j){
             if (req.query['joke']){
               j.joke = j.joke + 1

               update_author_level(req.params['id'], LEVEL_JOKE) // joke

               j.save(function(err,data){
                 res.json(data)
               })
             } else if (req.query['unjoke']) {

               j.unjoke = j.unjoke + 1
               j.save(function(err,data){
                 res.json(data)
               })
             } else {
               res.json(j)
           }

         }) //exec jokes find one
   }) //app.get

   /*****************************************************/
   // id is jokeid ,val is + level value 
   function update_author_level (id,val) {
    Jokes.findOne({_id:id})
       .populate('author')
       .exec(function(error,cursor) {
          var author = cursor.author[0]
          accounts.update({_id:author._id},{level:author.level+val},function(e,a){
          })
        })
   }


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
      
      Jokes.findOne({'_id': req.params['id']})
           .populate({ path: 'author', select: {'avatar':1,'nickname':1,'level':1,'username':1} })
           .exec(function(err,j){
							if (j.author[0]._id == req.user._id.toString()){
                next();
              } else {
								res.sendStatus(403)
							}
           })
    } else {
			res.sendStatus(403);
    } 
  })

	Jokes.register(app,'/api/jokes')

  comments.before('post', function(req, res, next) {
    if(req.isAuthenticated()){
      req.body['createdate'] = new Date()
      req.body['author'] = req.user._id
      req.body['joke'] = req.query.id
      update_author_level(req.query.id,LEVEL_COMMENT) // joke
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

  app.get('/api/comments' ,function (req,res){
    var jokeid = req.query.jokeid
    comments.find({joke:jokeid})
         // .sort('-_id')
         .populate({ path: 'author', select: {'avatar':1,'nickname':1,'level':1,'username':1} })
         .exec(function (err, comments) {
           if (err) return handleError(err);
           
           res.json(comments)
         })
   })

	comments.register(app,'/api/comments')

  // collection 2,
  var UploadImg = app.images = restful.model(
		'uploadimg', mongoose.Schema({
    url: String,
    createdate : { type:Date, default: Date.now },
  }))
  .methods(['get','post']);

	UploadImg.register(app,'/api/images')

}


/*
GET    /jokes
GET    /jokes/:id
POST   /jokes
PUT    /jokes/:id
DELETE /jokes/:id
*/
