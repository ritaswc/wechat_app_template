//const API_BASE = "https://qf-restapi.mdscjtest.com/xp_express";  //测试环境
const API_BASE = "https://qf-restapi.mdscj.com/xp_express";      //正式环境

/**
 * 获取热门快递公司列表
 * 无参数!
 */
const getExpressType = "/logistics/company/list";

/**
 * 获取快递具体信息，根据公司码
 * tid 时间戳，companyCode 公司码，logisticCode 快递单号!
 */
const getExpressMessage = "/logistics/search";

/**
 * 获取快递公司列表排序之后结果
 * 无参数!
 */
const getExpressAllType = "/logistics/company-by-sort";

/**
 *  获取快递具体信息，无需公司码，自动匹配
 *  logisticsNo  快递单号!
 */
const geMessageNoType = "/logistics/query";

/**
 *  根据快递单号从买到获取信息
 *  logisticsNo  快递单号!
 */
const geMessageMD = "/logistics/query-product-logisticsno";

/**
 *  根据快递单号获取可能的快递类型
 *  logisticsNo  快递单号!
 */
const  hotMatch = "/logistics/query-company-quickly";

/**
 * 使用快递100接口查询，根据快递单号,查询快递详情
 * logisticsNo  快递单号!
 */
const getExpressBy100 = "/kuaidi100/query";

/**
 * 使用快递100接口查询，根据快递单号，查询快递可能的快递公司
 * logisticsNo  快递单号!
 */
const getCompanyBy100 = "/kuaidi100/query-company-quickly";

const API_TYPE = API_BASE + getExpressType;
const API_MSG = API_BASE + getExpressMessage;
const API_SORT = API_BASE + getExpressAllType;
const API_NO_TYPE = API_BASE + geMessageNoType;
const API_MAIDAO = API_BASE + geMessageMD;
const API_HOTMATCH = API_BASE + hotMatch;
const API_EXPRESS_100 = API_BASE + getExpressBy100;
const API_COMPANY_100 = API_BASE + getCompanyBy100;

module.exports = {
    API_BASE, 
    API_TYPE, 
    API_MSG, 
    API_SORT, 
    API_NO_TYPE, 
    API_MAIDAO, 
    API_HOTMATCH, 
    API_EXPRESS_100, 
    API_COMPANY_100
}