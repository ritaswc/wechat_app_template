/**
 * Created by David on 2017/1/12.
 * 版权所有：广州聆歌信息科技有限公司
 * Legle co,.ltd.
 */
module.exports = {
    //appId: "wx4a2b7abd550eeadb" //APPID
    //, secret: "593d475d5cacee07f3a21f1eb76ecb32"  //公众号密钥

    appId: "wx77f952e833068728" // 测试APPID
    , secret: "3f8b28e6ebaf89962b857ae075d05d52"//测试密钥

    , mch_id: "1383387202"                        //商户号
    , apiKey: "e1afc06cde8f95a8459185093a05166c"  //商户key

    , access_token: ''
    , expire: ''

    , new_user_redirect_url: 'http://10.0.0.151:3345/webapp/html/validate.html'
    , old_user_redirect_url: 'http://10.0.0.151:3345/webapp/html/validate.html'

    , new_customer_redirect_url: 'http://10.0.0.151:3345/bbboss/validate.html'
    , old_customer_redirect_url: 'http://10.0.0.151:3345/bbboss/validate.html'

    , notify_url: 'http://infinitus-beauty.com/order/notify' //创建统一支付订单回调地址
    , customer_notify_url: 'http://infinitus-beauty.com/purchased_service/notify'
};