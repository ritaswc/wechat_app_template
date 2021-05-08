var restful = require('node-restful')
var mong = require('mongoose')



function init( ){
	var mongoose = restful.mongoose
  //databases
  var db = mongoose.connect("mongodb://localhost/jsjokes")	
  
  var accounts = restful.model(
    'accounts',mongoose.Schema({
    username: String,
    nickname: String,
    avatar: String,  //image url
    createdate: {type:Date, default: Date.now },
    level: {type:Number,default: 0 },
    admin: Number,
  }));

  /*
  accounts.find(function(error,cursor){
		console.log(cursor)
    //db.disconnect()					
  })
  */

  var Jokes = restful.model(
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
  }));


  accounts.findOne({username:'asmcos'},function(e,cursor){
   console.log(cursor)
   Jokes.find({author:[cursor._id]},function(error,cursor){
		 console.log(cursor)
   })
  })
  // db.disconnect()					
						
  // _id: 58b8c266947f354558a7b5da
  /*
  Jokes.findOne({_id:'58b8c266947f354558a7b5da'})
       .populate('author')
       .exec(function(error,cursor) {
		      console.log(cursor.author[0])
          var author = cursor.author[0]
          accounts.update({_id:author._id},{level:author.level+1},function(e,a){
           db.disconnect()					
          })
        })
  */
}

init ( )
