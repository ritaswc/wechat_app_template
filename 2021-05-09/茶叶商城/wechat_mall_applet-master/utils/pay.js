function pay(hash, successCallback) {
  wx.requestPayment({
    'appId':     hash.appId,
    'timeStamp': hash.timeStamp,
    'nonceStr':  hash.nonceStr,
    'package':   hash.package,
    'signType':  hash.signType,
    'paySign':   hash.signature,
    'success': successCallback,
    'fail': function(res){
    }
  })
}
module.exports = {
  pay (hash, successCallback) {
    return pay(hash, successCallback)
  }
}
