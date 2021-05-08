var mongoose = require("./db");
var Category = mongoose.model('categories', {
    name : String, //分类名字
    status: Number, //是否发布1-已发布；0-未发布
    type: Number, //类型：0-行业资讯；1-视频课件
    createDate: Date,
    modifyDate: Date,
});

module.exports = Category;
