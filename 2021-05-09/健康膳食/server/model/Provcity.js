var mongoose = require('./db');

var Provcity = mongoose.model('provcities', {
    pid: String,
    level: Number, // 0-省份  1-地级市
    province: String,	//省份(直辖市)
    city: String,	//地级市
    create: {
        type: Date,
        default: new Date()
    }	//创建时间
});

module.exports = Provcity;
