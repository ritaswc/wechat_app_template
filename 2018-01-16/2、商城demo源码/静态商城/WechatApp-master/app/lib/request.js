import promiseFromWXCallback from '../lib/promiseFromWXCallback';
const request = promiseFromWXCallback(wx.request);
const host = 'https://service.mediamall.cccwei.com';

export default (config) => {
  try
  {
    const token = wx.getStorageSync('token');
    if(config.path && config.path.indexOf('http://') ==0 ){
     
        config.url = config.path
    } else  {
      config.url = config.path ? host + config.path : config.url;
    }
   
    config.header = config.header || {};
    config.header.authorization = `Bearer {${token}}`;
    return request(config);
  } catch(e) {
    console.log(e);
  }
};
