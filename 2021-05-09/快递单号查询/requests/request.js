let api = require('./api.js');
let util = require('../utils/util.js');
let app = getApp();

const wxRequest = (params, url, successCallback, errorCallback, completeCallback) => {
  wx.request({
    url: url,
    method: params.method || 'GET',
    data: params.data || {},
    header: { 'Content-Type': 'application/json' },
    success(res) {
      if(app.debug) {
        console.log( 'response data: ', res );
      }
      if(res.statusCode == 200)
        util.isFunction(successCallback) && successCallback(res.data);
      else
         util.isFunction(errorCallback) && errorCallback(res);
      },
      fail(res) {
         util.isFunction(errorCallback) && errorCallback(res);
      },
      complete(res) {
        util.isFunction(completeCallback) && completeCallback(res);
      }
  })
}

/**
 * @param s => successCallback
 * @param e => errorCallback
 * @param c => completeCallback
 */

const getEpxressNoType = (params, s, e, c) => wxRequest(params, api.API_NO_TYPE, s, e, c);
const getEpxressData = (params, s, e, c) => wxRequest(params, api.API_MSG, s, e, c);
const getEpxressType = (params, s, e, c) => wxRequest(params, api.API_TYPE, s, e, c);
const getEpxressList = (params, s, e, c) => wxRequest(params, api.API_SORT, s, e, c);
const getMDMessage = (params, s, e, c) => wxRequest(params, api.API_MAIDAO, s, e, c);
const getHotMatchExpress = (params, s, e, c) => wxRequest(params, api.API_HOTMATCH, s, e, c);

/**
 *  根据快递100查询
 *  参数都为logisticsNo
 */
const getExpress100 = (params, s, e, c) => wxRequest(params, api.API_EXPRESS_100, s, e, c);
const getCompany100 = (params, s, e, c) => wxRequest(params, api.API_COMPANY_100, s, e, c);

module.exports = {
    getEpxressData, 
    getEpxressNoType, 
    getEpxressType, 
    getEpxressList, 
    getMDMessage, 
    getHotMatchExpress,
    getExpress100,
    getCompany100
}