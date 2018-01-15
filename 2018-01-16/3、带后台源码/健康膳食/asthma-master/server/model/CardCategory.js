var mongoose = require("./db");

var CardCategory = mongoose.model('cardcategories', {
	name: String, //学习卡名称
	course: [String], //固定的课程id
	codes: String, //唯一表示(判断重复)
	createDate: Date,
	modifyDate: Date,

	itemCourse: [{
        cid: String, 
        name: String
    }],
});

module.exports = CardCategory;