const Promise = require('../libs/bluebird');

// const apiRoot = 'http://192.168.0.163:1988';
// const apiRoot = 'http://localhost:1988';
const apiRoot = 'https://www.stydyweb.com';

function courseDetailApi(id) {
    return `${apiRoot}/course/coursedetail/${id}`;
}

function authApi(code, encryptedData, iv) {
    return `${apiRoot}/weixin?code=${code}&encryptedData=${encodeURIComponent(encryptedData)}&iv=${encodeURIComponent(iv)}`
}

function authRequest() {
    return Promise.all([wx.pro.login(), wx.pro.getUserInfo()]).then((results) => {
            const {code} = results[0];
            const {encryptedData, iv} = results[1];
            return wx.pro.request({url: authApi(code, encryptedData, iv)});
        });
}


module.exports = {
    appId: "wx5cbeb3b042c9c4ba",
    apiRoot,
    banner: `${apiRoot}/banner`,
    qulityVideo: `${apiRoot}/video/qulityvideo`,
    recommendedCourse: `${apiRoot}/course/recommendedcourse`,
    courseDetail: courseDetailApi,
    getAllSpecializedObj: `${apiRoot}/specializedObj/all`,
    searchCourse: `${apiRoot}/search/course`,
    getCourses: `${apiRoot}/course/list`,
    authApi,
    authRequest 
}
