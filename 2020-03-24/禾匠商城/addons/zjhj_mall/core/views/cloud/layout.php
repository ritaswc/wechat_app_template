<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title><?= $this->title ?></title>
    <script>
    if (typeof Promise === 'undefined') {
        if (confirm('您的浏览器版本过低，将无法正常使用本页面，请升级您的浏览器或使用360浏览器。')) {
            window.open('https://browser.360.cn/ee/');
        }
    }
    </script>
    <link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/statics/css/element-ui.min.css">
    <link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/statics/css/flex.css">
    <style>
    * {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-size: 14px;
        background: #f6f6f6;
    }

    a {
        color: #409EFF;
    }
    </style>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/js/vue.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/js/element-ui.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/js/axios.min.js"></script>
    <script>
    const _csrfToken = '<?=Yii::$app->request->csrfToken?>';
    const _scriptUrl = '<?=Yii::$app->request->scriptUrl?>';

    function cleanArray(actual) {
        const newArray = [];
        for (let i = 0; i < actual.length; i++) {
            if (actual[i]) {
                newArray.push(actual[i]);
            }
        }
        return newArray;
    }

    // 将一个对象转成QueryString
    function toQueryString(obj) {
        if (!obj) return "";
        return cleanArray(
            Object.keys(obj).map(key => {
                if (obj[key] === undefined) return "";
                return encodeURIComponent(key) + "=" + encodeURIComponent(obj[key]);
            })
        ).join("&");
    }

    const request = axios.create({});
    request.defaults.baseURL = _scriptUrl;
    request.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
    request.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    request.interceptors.request.use(function (config) {
        if (config.method.toLowerCase() === 'post') {
            config.data['_csrf'] = _csrfToken;
        }
        if (config.headers['Content-Type'] !== 'text/plain' && config.headers['Content-Type'] !== 'application/json') {
            config.data = toQueryString(config.data)
        }
        return config;
    }, function (error) {
        return Promise.reject(error);
    });

    /**
     * 浏览器跳转链接
     * @param {JSON} params
     * @param {bool} newWindow
     */
    const navigateTo = (params, newWindow = false) => {
        const queryString = toQueryString(params);
        const url = `${_scriptUrl}?${queryString}`;
        if (newWindow) {
            window.open(url);
        } else {
            window.location.href = url;
        }
    };
    Vue.use({
        install(Vue, options) {
            Vue.prototype.$navigate = function (params, newWindow) {
                navigateTo(params, newWindow);
            }
        }
    });

    /**
     * 获取get请求参数的值
     * @param {String} name
     * @returns {String||null}
     */
    const getQuery = (name) => {
        const reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        const r = window.location.search.substr(1).match(reg);
        if (r != null) {
            return decodeURIComponent(r[2]);
        }
        return null;
    };
    </script>
</head>
<body>
<?= $content ?>
</body>
</html>