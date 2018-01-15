function getToast(data) {
  this.title = data.title;
  this.icon = data.icon;
  this.duration = data.duration;
  this.success = data.success;
  this.fail = data.fail;
  this.complete = data.complete;
  // this.build();
}
getToast.prototype.build = function() {
	console.log(this);
	return this;
};
module.exports.toast = getToast;
