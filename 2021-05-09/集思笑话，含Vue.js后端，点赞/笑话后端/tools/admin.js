var restful = require('node-restful')
var mong = require('mongoose')



function admin(user){
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

  accounts.find(function(error,cursor){
		console.log(cursor)
  })

  accounts.findOneAndUpdate({"username":user},
                           {'admin':1},function(err, count, resp){
													 db.disconnect()											
            });

}

admin ('asmcos')
