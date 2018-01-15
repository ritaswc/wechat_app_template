let config = {
    WX_ENV : "development", //当前环境 development, product, test
    CODE : {
        REQUESTSUCCESS : 1001,
        REQUESTERROR : 1004
    },
    product : {
        HOST : {
            HTTP_DOMAIN : 'http://m.xymens.com/',
            BASE_URL : 'http://m.xymens.com/index.php',
            API_URL : 'http://api.xymens.com/index.php'
        }
    },
    development : {
        HOST : {
            HTTP_DOMAIN : 'http://tm.xymens.com/',
            BASE_URL : 'http://tm.xymens.com/index.php',
            API_URL : 'http://tapi.xymens.com/index.php'
        }
    },
    test : {
        HOST : {
            HTTP_DOMAIN : 'http://tm.xymens.com/',
            BASE_URL : 'http://tm.xymens.com/index.php',
            API_URL : 'http://tapi.xymens.com/index.php'

        }
    }
};

module.exports = config;