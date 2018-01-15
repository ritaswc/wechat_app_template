
var URI = 'https://api.douban.com/v2/movie';

/**
 * 抓取豆瓣电影特定类型的API
 * https://developers.douban.com/wiki/?title=movie_v2
 * @param  {String} type   类型，例如：'coming_soon'
 * @param  {Objece} params 参数
 * @return {Promise}       包含抓取任务的Promise
 */
function fetchApi(type, params) {
  return new Promise(function (resolve, reject) {
    wx.request({
      url: URI + '/' + type,
      data: Object.assign({}, params),
      header: { 'Content-Type': 'application/json' },
      success: resolve,
      fail: reject
    });
  });
}

/**
 * 获取列表类型的数据
 * @param  {String} type   类型，例如：'coming_soon'
 * @param  {Number} page   页码
 * @param  {Number} count  页条数
 * @param  {String} search 搜索关键词
 * @return {Promise}       包含抓取任务的Promise
 */
function find(type) {
  var page = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;
  var count = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 20;
  var search = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : '';

  var params = { start: (page - 1) * count, count: count };
  return fetchApi(type, search ? Object.assign(params, { q: search }) : params).then(function (res) {
    return res.data;
  });
}

/**
 * 获取单条类型的数据
 * @param  {Number} id     电影ID
 * @return {Promise}       包含抓取任务的Promise
 */
function findOne(id) {
  return fetchApi('subject/' + id).then(function (res) {
    return res.data;
  });
}

module.exports = { find: find, findOne: findOne };
//# sourceMappingURL=douban.js.map
