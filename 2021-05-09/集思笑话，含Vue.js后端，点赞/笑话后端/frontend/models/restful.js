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
    content:String,
    createdate :{type:Date, default:Date.now },
    pv: {type:Number,default:0},
    joke: {type:Number,default:0},
    unjoke: {type:Number, default: 0},
    published: {type :Number, default: 1},// 1 ,show. 0. hide
    comments: [{type : mongoose.Schema.ObjectId, ref : 'comments'}], // 1 ,show. 0. hide
    author  : [{type : mongoose.Schema.ObjectId, ref : 'accounts'}],
  }))
  .methods(['get']);
   
  var accounts = restful.model(
    'accounts',mongoose.Schema({
    nickname: String,
    avatar: String,  //image url
    createdate: {type:Date, default: Date.now },
    level: {type:Number,default: 0 },
  }));

  var comments = restful.model(
    'comments',mongoose.Schema({
    content : String,
    createdate: {type:Date, default: Date.now },
    author  : {type : mongoose.Schema.ObjectId, ref : 'accounts'},
    joke    : {type : mongoose.Schema.ObjectId, ref : 'jokes'},
  }))
 
  app.get('/jokes' ,function (req,res){
		Jokes.find()
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

   // joke or unjoke
   app.get('/jokes/:id',function(req,res){

      Jokes.findOne({'_id': req.params['id']},function(err,j){
			  if (req.query['joke']){
				  j.joke = j.joke + 1

          update_author_level(req.params['id']) // joke

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
      
      })
   })
 
   /*****************************************************/
   function update_author_level (id) {
    Jokes.findOne({_id:id})
       .populate('author')
       .exec(function(error,cursor) {
          var author = cursor.author[0]
          accounts.update({_id:author._id},{level:author.level+1},function(e,a){
          })
        })
   }




}


/*
GET    /jokes
GET    /jokes/:id
POST   /jokes
PUT    /jokes/:id
DELETE /jokes/:id
*/
