
const crypto = require("../libs/crypto-js"); //使用scrypot-js进行HmacSHA256加密、BASE64加密。 npm install crypto-js --save
const uuid = require('../libs/uuid'); //生成随机字符串   npm install node-uuid --save
const Promise = require('../libs/bluebird.min');
const ALICLOUD_KEY = '23541096';
const ALICLOUD_SECRET = 'eb1c4174d554af277b63c13d54f0cbfa';
const NET_ERROR = '网络异常';
/**
 * 天气数据服务
 * 
 * @export
 * @class WeatherService
 */
class WeatherService {
    /**
     * 根据path获取
     * 总的获取入口
     * @static
     * @param {string} path
     * @returns
     * 
     * @memberOf WeatherService
     */
    static getWeather(path) {
        let Accept = 'application/json';
        let ContentType = 'application/json';
        let Timestamp = Date.now();
        let Nonce = uuid.create().toString();
        let Headers =
            'X-Ca-Key' + ":" + ALICLOUD_KEY + "\n" +
            'X-Ca-Nonce' + ":" + Nonce + "\n" +
            'X-Ca-Request-Mode' + ":" + 'debug' + "\n" +
            'X-Ca-Stage' + ":" + 'RELEASE' + "\n" +
            'X-Ca-Timestamp' + ":" + Timestamp + "\n" +
            'X-Ca-Version' + ":" + '1' + "\n";

        let Url = 'https://ali-weather.showapi.com' + path;
        let stringToSign =
            'GET' + "\n" +
            Accept + "\n" +
            '' + "\n" +
            ContentType + "\n" +
            '' + "\n" +
            Headers +
            path; //此处为访问域名后面的字符串


        let sign = crypto.HmacSHA256(stringToSign, ALICLOUD_SECRET); //先进行HMAC SHA256加密。官方文档并无nodejs相关的说明，参照其他语言进行摸索
        sign = crypto.enc.Base64.stringify(sign); //将HMAC SHA256加密的原始二进制数据后进行Base64编码
        console.log(sign);

        return new Promise((resolve, reject) => {
            wx.request({
                url: Url, //仅为示例，并非真实的接口地址
                header: {
                    'Accept': Accept,
                    'Content-Type': ContentType,
                    'X-Ca-Request-Mode': 'debug',
                    'X-Ca-Version': 1,
                    'X-Ca-Signature-Headers': 'X-Ca-Request-Mode,X-Ca-Version,X-Ca-Stage,X-Ca-Key,X-Ca-Timestamp,X-Ca-Nonce',
                    'X-Ca-Stage': 'RELEASE',
                    'X-Ca-Key': ALICLOUD_KEY,
                    'X-Ca-Timestamp': Timestamp,
                    'X-Ca-Nonce': Nonce,
                    'X-Ca-Signature': sign
                },
                success: (res) => {
                    resolve(res.data)
                },
                fail: (res) => {
                    reject(res);
                }
            })
        })
    }
    /**
     * 根据GPS获取的经纬度获取
     * 
     * @static
     * @param {string} lat
     * @param {string} lng
     * @returns
     * 
     * @memberOf WeatherService
     */
    static getByGps(lat, lng) {
        console.log('getByGps ', lat, lng);
        var path = '/gps-to-weather?from=1&lat=' + lat + '&lng=' + lng + '&need3HourForcast=1&needAlarm=1&needIndex=1&needMoreDay=1';
        return this.getWeather(path);
    }
}

module.exports = WeatherService;