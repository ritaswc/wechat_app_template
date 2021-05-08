/**
 * Created by rudolf on 2017/4/23.
 */
const _ = require('lodash');
const path = require('path');
const fs = require('fs');
const RouterBase = require('../../../common/routerbase');
const config = require('../../../config');

class DownLoad extends RouterBase {
    handle() {
        fs.readFile("/data/release/qcloud-applet-album/output/output.jpg","binary",function (error, file, res) {
            if(error) {
                res.end();
            }
            else{
                res.write(file, "binary");
                res.end();
            }
        });
    }
}

module.exports = DownLoad.makeRouteHandler();