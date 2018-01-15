function formatTime(date) {
  var year = date.getFullYear()
  var month = date.getMonth() + 1
  var day = date.getDate()

  var hour = date.getHours()
  var minute = date.getMinutes()
  var second = date.getSeconds()

  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}
function formatNumber(n) {
  n = n.toString()
  return n[1] ? n : '0' + n
}
//手机号码是否正确
function checkPhone(phone){
  if(!(/^1[34578]\d{9}$/.test(phone))){
    return false;
  } else{
    return true;
  }
}
module.exports = {
  formatTime: formatTime,
  checkPhone: checkPhone,
}