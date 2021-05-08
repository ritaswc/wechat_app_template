var mongoose = require('./db');
//医院
var Hospital = mongoose.model('hospitals', {
	category: String,	//类型
	hospitalId : String, // 医院编号
	hospitalNum : String,
	contactName : String, // 联系人姓名
	contactPhone : String, // 联系电话
	province: String,	//省份(直辖市)
	city: String,	//地级市
	county: String,	//县
	name: String,	//单位名称
	level: String,	//等级
	area : String, // 所在区域
	profit: String,	//盈利性质
	create: {
		type: Date,
		default: new Date()
	},	//创建时间
});

module.exports = Hospital;