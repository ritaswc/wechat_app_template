var mongoose = require("./db");
var Specialist = mongoose.model('specialists', {
    name : String, //专家名字
    img: String, //图片链接
    createDate: Date,
    modifyDate: Date,
    introduce: String, //简介
});

module.exports = Specialist;
