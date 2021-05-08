/**
 * 小程序配置文件
 */

var host = "http://gov.jointem.com/zyb/api"  //API请求接口
var host_sh = "http://m.jointem.com"  //API请求接口
var host_iamge = "http://gov.jointem.com"     //图片拼接前

var config = {

    // 下面的地址配合云端 Server 工作
    host,

    loginUrl: `${host_iamge}/zyb/public/user/system/login`, /** 登录接口 login */
    logout: `${host_iamge}/zyb/public/user/system/logout`,  /** 退出登录 logout */
    AppinitData: `${host}/init3_1/public/getAppInitData`, /** 首页接口数据 - 轮播 */
    GET_HOT_NEWS: `${host}/hotNews/public/getHotNews?accessToken=`,/** 获取新闻动态 */
    
    newsTagUrl: `${host}/newstag/public/getnewstag`, /** 获取政务资讯顶部标签 */

    // 测试的请求地址
    requestUrl: `${host}/testRequest`,

    //生活
    RecommendDoorListUrl: `${host_sh}/front/public/advert/getAdvertRecommendDoorList`, /** 获取便民首页优选推荐店铺列表 */
    ttPrizeUrl: `${host_sh}/front/public/activity/getActivityListByTypeId`,/** 天天有奖 */
    onePrizeUrl: `${host_sh}/front/public/oyb/getServantPrdList`, /** 一元夺宝 */
    miaoshaUrl: `${host_sh}/front/public/activity/getActivityListByTypeId`, /** 一元夺宝 */
    couponCategoryUrl: `${host_sh}/front/public/search/getCouponCategory`, /** 获取优惠券分类 */
    CouponSearchListUrl: `${host_sh}/front/public/search/getCouponSearchList`, /*好优惠搜索*/
    receiveCouponUrl: `${host_sh}/front/public/user/url/receiveCoupon`, /** 筛选出用户领取过的未使用的优惠券 */
    addCouponUrl: `${host_sh}/front/public/user/url/addCoupon`, /** 领取优惠券 */
    CouponDetailUrl: `${host_sh}/front/public/getCouponDetail`, /** 获取优惠券详情 */
    UserCouponIdUrl: `${host_sh}/front/public/user/getUserCouponId`, /** 获取用户优惠券表id */
    UserCouponInfoUrl: `${host_sh}/front/public/user/url/getUserCouponInfo`, /** 获取用户优惠券信息 */
    ServiceTypeListUrl: `${host_sh}/front/public/door/getServiceTypeList`,/** 分类服务 */
    ServiceSearchListUrl: `${host_sh}/front/public/search/getServiceSearchList`, /** 好服务搜索 */
    ServiceDetailInfoUrL: `${host_sh}/front/public/door/viewServiceDetailInfo`, /** 获取服务详情数据 */
    AdvertCaroucelsUrl: `${host_sh}/front/public/advert/getAdvertCaroucels`, /**吃喝玩乐 - 广告轮播*/
    ChildCateUrl: `${host_sh}/front/public/common/url/getChildCate`, /** 吃喝玩乐 - 分类 */
    LifeSearchListUrl: `${host_sh}/front/public/search/getLifeSearchList`, /** 吃喝玩乐 - 条件检索 */
    HighestDiscountUrl: `${host_sh}/front/public/getHighestDiscount`, /** 吃喝玩乐 - 条件检索 - 对应折扣查询 */
    PrdSearchListUrl: `${host_sh}/front/public/search/getPrdSearchList`, /** 好商品搜索 */
    PrdFirstCategoryUrl: `${host_sh}/front/public/search/getPrdFirstCategory`, /** 获取好商品所有一级分类 */
};

module.exports = config
