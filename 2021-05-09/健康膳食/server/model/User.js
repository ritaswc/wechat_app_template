var mongoose = require("./db");
var User = mongoose.model('users', {
    username: String,//用户名
    name : String,//姓名
    password: String,//密码
    sex: String, //性别 男／女
    email: String, //邮箱
    phone: String, //手机号码
    certificate_category: String, //证件类型
    certificate_code: String, //证件号
    province: String, //地区－－省
    city: String, //地区－－市
    department_first: String, // 一级所属科室
    department_second: String, // 二级所属科室
    title: String, //职称
    unit: String, //工作单位（医院）
    addr: String, //通信地址
    code: String, //邮编
    doctor_certificate_code: String, //医师资格证编号
    real: Number,//是否认证 0、未认证 1、认证
    duty: String,
    unionid: String,
    remember_me: String,
    mycourse: [String],//我的课程
});

module.exports = User;
