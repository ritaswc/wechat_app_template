var mongoose = require("./db");
var Infomation = mongoose.model('infomations', {
    name : String, //分类名字
    status: Number, //是否发布1-已发布；0-未发布
    category: String,	//所属类别
    specialObj: String, //所属专科
    content: String, //富文本内容，暂时定义string类型
    img: String, //图片链接
    viewCount: Number, //浏览次数
    createDate: Date,
    modifyDate: Date,
    publishDate: Date, //发布日期
    introduce: String, //简介
    sourceFrom: String, //文章来源
    tag: String, //标签
});

module.exports = Infomation;
