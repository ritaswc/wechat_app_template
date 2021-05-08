var mongoose = require('mongoose'),
  Schema = mongoose.Schema,
  passportLocalMongoose = require('passport-local-mongoose');

var Account = new Schema({
  nickname: String,
  avatar: String,  //image url
  createdate: {type:Date,default:Date.now},
  level: {type:Number,default:0 },
  weappid: {type: String},
});

Account.plugin(passportLocalMongoose);

module.exports = mongoose.model('Account', Account);

