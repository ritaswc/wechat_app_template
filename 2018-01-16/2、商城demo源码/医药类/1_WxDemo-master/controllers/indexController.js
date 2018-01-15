
const request = require('../utils/kad.request.js')
const linq = require('../lib/linq.min.js').linq

const URI = 'http://app.360kad.com';

/**
 * 每一个页面对应一个contoller
 */
class IndexController{
    /**
     * 抓取首页布局
     * @return {Promise}
     */
    getHomeLayout(){
        return request.get(`${URI}/ad/get?id=iOS.HomeV2.Layout&_rndev=104042`).then(res => res.data)
    }
    /**
     * 获取热门专题
     * @return {Promise}
     */
    getHotTopic(){
        return request.get(`${URI}/ad/get?id=iOS.HomeV2.HotSpecialWithIntegralStore&_rndev=109758`).then(res => res.data)
    }

    /**
     * 抓取底部导航数据
     * @return {Promise} 
     */
    getFooter(){
        let _this = this;
        return request.get('https://wxapp.360kad.com/home').then(res => _this.getFooterList(res.data.FooterList))  
    }

    getFooterList(data){
        var list = linq.From(data)
                   .Where(function (x) { return x.Sort > 1 })
                   .OrderBy(function (x) { return x.Text })
                   .ToArray();
        return list;
    }


        /**
     * 抓取底部导航数据
     * @return {Promise} 
     */
    getFooter2(){
        let _this = this;
        return request.get('https://wxapp.360kad.com/home').then(res => _this.getFooterList2(res.data.FooterList))  
    }

    getFooterList2(data){
        var list = linq.From(data)
                   .Where(function (x) { return x.Sort > 2 })
                   .OrderBy(function (x) { return x.Text })
                   .ToArray();
        return list;
    }

    /**
     * 抓取底部导航数据
     * @return {Promise}  
     */
    getBannerIcon(){
        return request.get(`${URI}/ad/get?id=iOS.HomeV2.RoundIcon&_rndev=109951`).then(res => res.data)  
    }

    /**
     * 抓取底部导航数据
     * @return {Promise} 
     */
    getTopScroll(){
        return request.get(`${URI}/ad/get?id=iOS.Home.BigBanner&_rndev=108097`).then(res => res.data) 
    }
    /**
     * 获取猜你喜欢
     * @return {Promise}
     */
    getIndexGuess(pageIndex,pageSize){
        return request.get(`${URI}//DataPlatform/GetIndexGuessLikeProducts?siteid=40&pageIndex=${pageIndex}&pageSize=${pageSize}`).then(res => res.data) 
    }

}
/**
 * 实例化对象
 */
let indexController=  new IndexController();
/**
 * 暴露对象，无需每次都加函数名
 */
module.exports = { 
    controller:indexController,
 }