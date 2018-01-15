const URI = 'http://app.360kad.com'
/**
 * 抓取API数据
 * @param  {String} url    链接
 * @param  {Objece} params 参数
 * @return {Promise}       包含抓取任务的Promise
 */
function fetchApi (url, params) {
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
/**
 * 抓取首页布局
 * @return {Promise}       包含抓取任务的Promise
 */
function getHomeLayout(){
    return fetchApi(`${URI}/ad/get?id=iOS.HomeV2.Layout&_rndev=104042`).then(res => res.data)
}
/**
 * 获取热门专题
 * @return {Promise}       包含抓取任务的Promise
 */
function getHotTopic(){
    return fetchApi(`${URI}/ad/get?id=iOS.HomeV2.HotSpecialWithIntegralStore&_rndev=109758`).then(res => res.data)
}

/**
 * 抓取底部导航数据
 * @return {Promise}       包含抓取任务的Promise
 */
function getFooter(){
    return fetchApi('http://wxapp.360kad.com/home').then(res => res.data.FooterList)  
}


/**
 * 抓取底部导航数据
 * @return {Promise}       包含抓取任务的Promise
 */
function getBannerIcon(resolve,reject){
    return fetchApi(`${URI}/ad/get?id=iOS.HomeV2.RoundIcon&_rndev=109951`).then(res => res.data)  
}

/**
 * 抓取底部导航数据
 * @return {Promise}       包含抓取任务的Promise
 */
function getTopScroll(resolve,reject){
    return fetchApi(`${URI}/ad/get?id=iOS.Home.BigBanner&_rndev=108097`).then(res => res.data) 
}

module.exports = { 
    getFooter:getFooter, 
    getBannerIcon:getBannerIcon,
    getTopScroll:getTopScroll,
    getHotTopic:getHotTopic
 }