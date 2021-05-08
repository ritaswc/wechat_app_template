/**
 * global settings
 */

module.exports = {
	development : false,
	is_local : false,//true -> 本地开发环境, false -> 测试和正式服务器
	debug : false,
	http : 1227,
	https : 80,
	db : {
		host: '',
        user: '',
        password: '',
        database: '',
        port: 3306
	}
};