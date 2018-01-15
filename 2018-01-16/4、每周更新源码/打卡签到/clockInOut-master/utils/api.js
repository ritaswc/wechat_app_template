
var host = 'https://api.clock.newteo.com/';

var session = host + 'session';
var company = host + 'company/new?token=';
var companyList = host + 'user/companies?token=';
var companyDetail = host + 'user/company/';
var joinCompany = host + 'user/company?token=';
var applylist = host + 'company/applylist?token=';
var deleteCompany = host + 'company/now?token=';
var verifyApply = host + 'company/applylist/';

var staffsList = host + 'company/staffs?token='
var worktime = host + 'company/information?token=' //用于获取该公司上班时间

var information = host + 'company/information?token='

var staffAttdance = host + 'company/staffs/day?token='

var qrcode = host + 'qrcode/get?token='

var tofree = host + 'user/tofree?token='

var punch = host + 'user/punch?'

var userInfo = host + 'user/info?token='

var clockList = host + 'company/staffs/day?token='

var selftList = host + 'user/record?token='
var staffsigns = host + 'company/staffs/'

module.exports = {
    session: session,
    company: company,
    companyList: companyList,
    companyDetail: companyDetail,
    joinCompany: joinCompany,
    applylist: applylist,
    deleteCompany: deleteCompany,
    verifyApply: verifyApply,
    staffsList: staffsList,
    worktime: worktime,
    information: information,
    staffAttdance: staffAttdance,
    qrcode: qrcode,
    tofree: tofree,
    punch: punch,
    userInfo: userInfo,
    clockList: clockList,
    selftList: selftList,
    staffsigns: staffsigns,

}

//58b42745679e8155e0a771de
//58b42b04679e8155e0a771e0

//新增公司
//POST  http://localhost:?/company/new?token=${token}

//获取公司列表
//GET   http://localhost:?/user/companies?token=${token}


//查看公司详情
//GET  http://localhost:?/user/company/:id?token=${token}


//申请加入
//POST  http://localhost:?/user/company?token=${token}


//获取申请人员列表
//GET    http://localhost:?/company/applylist?token=${token}

//删除公司信息
//DELETE    http://localhost:?/company/now?token=${token}



//验证申请人员
//POST    http://localhost:?/company/applylist/:applyId?token=${token}


//获取单天成员打卡信息
//  POST    http://localhost:?/company/staffs/day?token=${token}


//获取成员列表
//GET    http://localhost:?/company/staffs?token=${token}

//获取二维码
//GET    http://localhost:?/qrcode/get?token=${token}

//退出公司
//  DELETE    http://localhost:?/user/tofree?token=${token}


//打卡API
//POST    http://localhost:?/user/punch/:companyId?token=${token}

//获取单天成员打卡信息
//  GET    http://localhost:?/company/staffs/day?token=${token}&today=${today}

// 查看自己个人打卡记录
// GET    http://localhost:?/user/records?token=${token}

//查看个人信息
//    GET    http://localhost:?/user/info?token=${token}
