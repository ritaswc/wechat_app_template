const esx = {
	show() {
	  console.log("xx");
	},
	showTips(data) {
		let isShow = data.showTips || false;
	   	setTimeout(() => {
	   		data.showTips = false;
	   	}, data.duartion || 500);
	   	esx.show();
	}
};

module.exports.exTips = esx.showTips;