var mongoose = require("./db");

var Card = mongoose.model('cards', {
	name: String, //学习卡名称
	card_category: String, //?卡分类?
	card: [{
		card_no: String, // 卡序列
		code: String, //卡号
		status: Number, //是否使用
		create: Date, //创建卡时间
		use_time: Date, //卡使用时间
		owner: String, //使用人
	}],
	createDate: Date,
});

module.exports = Card;