/**
 *  日期处理工具类
 */

class DateUtil {

	constructor() {

	}

	getToday() {
		let date = new Date();
		return this.formatDate(date);
	}

	getEndDate() {
		let date = new Date();
		date.setDate(date.getDate()+4);
		return this.formatDate(date);
	}

	formatDate(date) {
		let month = date.getMonth()+1;
		if(month < 10) {
			month = '0' + month;
		}
		return date.getFullYear() + '-' + month + '-' + date.getDate();
	}
} 

module.exports = new DateUtil();