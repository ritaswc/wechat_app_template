import fetch from 'node-fetch';
import co from 'co';
import WXBizDataCrypt from '../util/WXBizDataCrypt';

import config from './index';

const grant_type = "authorization_code";


export function getSecretInfo(req, res, next) {
	co(function* () {
        const { code, encryptedData, iv } = req.query;
        const params = "appid=" + config.appId + "&secret=" + config.appSecret + "&js_code=" + code + "&grant_type=" + grant_type;
        const url = `https://api.weixin.qq.com/sns/jscode2session?${params}`;
        const { session_key } = yield fetch(url).then((result) => result.json());
        const data = decodeSecretInfo(session_key, encryptedData, iv);
		req.wxData = data;
		next();
    }).catch((err) => {
        console.log(err);
    })
}



function decodeSecretInfo(session_key, encryptedData, iv) {
    const pc = new WXBizDataCrypt(config.appId, session_key)
    const data = pc.decryptData(encryptedData , iv)
    return data;
}
