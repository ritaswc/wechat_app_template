var mongoose = require("./db");
var Record = mongoose.model('records', {
	card_id: String, //学习卡id
	card_name: String, //学习卡名
	card_code: String, //学习卡号
	owner: String, //消费人员id
	ownerName: String, //消费人员姓名
	ownerPro: String, //消费人员省份
	ownerCity: String, //消费人员城市
	ownerUnit: String, //消费人员单位
	create: Date, //时间
});

module.exports = Record;