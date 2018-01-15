const _ = require('lodash');
const path = require('path');
const RouterBase = require('../../../common/routerbase');
const config = require('../../../config');

class ListImages extends RouterBase {
    handle() {
        const bucket = config.cosFileBucket;
        const listPath = config.cosUploadFolder;
        const listNum = 100;
        const pattern = 'eListFileOnly';
        const order = 1;
        const context = '';

        cos.list(bucket, listPath, listNum, pattern, order, context, (res) => {
            if (res.code !== 0) {
                this.res.json({ code: -1, msg: 'failed', data: {} });
                return;
            }

            this.res.json({
                code: 0,
                msg: 'ok',
                data: _.map(res.data.infos, 'access_url').filter(function item() {
                    let extname = String(path.extname(item)).toLowerCase();

                    // 只返回`jpg/png`后缀图片
                    return ['.jpg', '.png'].includes(extname);
                }),
            });
        });
    }
}

module.exports = ListImages.makeRouteHandler();