const Sequelize = require('sequelize');

const sequelize = new Sequelize('bus_ecomm', 'root', '123456', {
	host: 'localhost',
	dialect: 'mysql',
	port: 3306,
	define: {
		// 字段以下划线（_）来分割（默认是驼峰命名风格）  
		'underscored': true
	},
	pool: {
		min: 0,
		max: 5,
		idle: 1000
	}
});

module.exports = sequelize;

