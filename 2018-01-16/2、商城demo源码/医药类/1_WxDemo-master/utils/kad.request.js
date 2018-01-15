const Promise = require('../lib/es6-promise.min.js').Promise

class Request{
   /**
 * 抓取API数据
 * @param  {String} url    链接
 * @param  {Objece} params 参数
 * @return {Promise}       包含抓取任务的Promise
 */
getApi(url, params) {
  return new Promise((resolve, reject) => {
    wx.request({
      url: `${url}`,
      data: Object.assign({}, params),
      header: { 'Content-Type': 'application/json' },
      success: resolve,
      fail: reject
    })
  })
}

}

let request = new Request();

module.exports = {
  get: request.getApi
}