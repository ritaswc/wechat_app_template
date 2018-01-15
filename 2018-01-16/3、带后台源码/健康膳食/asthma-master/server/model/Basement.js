var mongoose = require("./db");
var Basement = mongoose.model('basements', {
    status: Number, //是否发布1-已发布；0-未发布
    createDate: Date,
    modifyDate: Date,
    province: String, //name
    city: String,
    _province: String, //id
    _city: String,
    name: String,
    content: String,
});

module.exports = Basement;
