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

var app = getApp()

function WXRequest(param){
  // Cookie:session=eyJwYXNzcG9ydCI6eyJ1c2VyIjoY29zIn19; session.sig=GBxXF9JQA8A_5sWswHHE

  var session = app.globalData.session,
      sessionsig = app.globalData.sessionsig;
    
  var Cookies = 'session='+session+';session.sig='+sessionsig
  if (param.header){
    param.header['Cookie'] = Cookies
  } else {
    param.header={'Cookie':Cookies,"content-type": "application/x-www-form-urlencoded"}
  }
  wx.request(param)

}

module.exports = {
  WXRequest:WXRequest,
  formatTime: formatTime
}
