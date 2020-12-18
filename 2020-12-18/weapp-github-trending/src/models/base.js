import { request } from 'wx-promise-request'
import md5 from '../common/libs/md5'
import { Promise } from 'es6-promise'

class Base 
{
    constructor () {
        // 修改这里的地址为自己的服务器地址
        this.host = 'https://xxxx.com/api'
    }

    url (path) {
        return this.host + path
    }

    /**
     * 带缓存的Http Get
     * @param {String} path 
     * @param {Object} data
     * @param {Boolean} allowCache 是否允许获取缓存数据
     */
    get (path, data = {}, allowCache = true) {
        let key = md5(path + JSON.stringify(data))
        let val = null
        if (allowCache) {
            val = wx.getStorageSync(key)
        }
        if (val) {
            return new Promise((resolve, reject) => {
                return resolve(val)
            })
        } else {
            return request({
                url: this.url(path),
                data,
                header: {
                    'content-type': 'application/json' // 默认值
                }
            }).then((res) => {
                wx.setStorage({
                    key,
                    data: res
                  })
                return res
            })
        }
    }
}

export default Base