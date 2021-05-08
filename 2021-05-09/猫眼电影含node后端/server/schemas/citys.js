/**
 * Created by Administrator on 2017/3/3.
 */
var mongoose=require('mongoose');
module.exports= new mongoose.Schema({
    username: String,
    password: String
});