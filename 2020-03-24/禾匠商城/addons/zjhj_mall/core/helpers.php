<?php

/**
 * 强调：此处不要出现 use 语句！
 */

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string $key
     * @param  mixed $default
     * @param  string $delimiter
     * @return mixed
     */
    function env($key, $default = null, $delimiter = '')
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if (strlen($value) > 1 && str_starts_with($value, '"') && str_ends_with($value, '"')) {
            $value = substr($value, 1, -1);
        }

        if (strlen($delimiter) > 0) {
            if (strlen($value) == 0) {
                $value = $default;
            } else {
                $value = explode($delimiter, $value);
            }
        }

        return $value;
    }
}

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('str_starts_with')) {
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    function str_starts_with($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && substr($haystack, 0, strlen($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('str_ends_with')) {
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    function str_ends_with($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if (substr($haystack, -strlen($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('define_once')) {
    /**
     * Define a const if not exists.
     *
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    function define_once($name, $value = true)
    {
        return defined($name) or define($name, $value);
    }
}

if (!function_exists('dd')) {
    /**
     * Dump the passed variable and end the script.
     *
     * @param  mixed $arg
     * @return void
     */
    function dd($arg)
    {
        echo "<pre>";
        // http_response_code(500);
        \yii\helpers\VarDumper::dump($arg);
        die(1);
    }
}

if (!function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}
if (!function_exists('pay_notify_url')) {
    /**
     * 拼接支付回调地址
     *
     * @param  string $suffix
     * @return string
     */
    function pay_notify_url($suffix)
    {
        $hostInfo = \Yii::$app->request->hostInfo;
        $protocol = env('PAY_NOTIFY_PROTOCOL', false);
        if ($protocol === 'http') {
            $hostInfo = str_replace('https:', 'http:', $hostInfo);
        }
        if ($protocol === 'https') {
            $hostInfo = str_replace('http:', 'https:', $hostInfo);
        }
        $hostInfo .= \Yii::$app->request->baseUrl . $suffix;
        return $hostInfo;
    }
}

function hj_core_version()
{
    static $version = null;
    if ($version) {
        return $version;
    }
    $file = __DIR__ . '/version.json';
    if (!file_exists($file)) {
        throw new Exception('Version not found');
    }
    $res = json_decode(file_get_contents($file), true);
    if (!is_array($res)) {
        throw new Exception('Version cannot be decoded');
    }
    return $version = $res['version'];
}

function hj_pdo_run($sql)
{
    try {
        $sql = str_replace('hjmall_', WE7_TABLE_PREFIX, $sql);
        \Yii::$app->db->createCommand($sql)->execute();
        // $res = pdo_query($sql);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function get_plugin()
{
    $route = \Yii::$app->requestedRoute;
    $route = str_replace('\\', '/', $route);
    $match = explode('/', $route);
    return $match;
}

function get_plugin_type()
{
    $plugin = get_plugin();
    $list = [
        'goods' => 0,
        'pond' => 1,
        'bargain' => 2,
        'lottery' => 4,
        'step' => 5,
    ];
    if (isset($list[$plugin[1]])) {
        $type = $list[$plugin[1]];
    } else {
        $type = 0;
    }
    return $type;
}

function get_plugin_url()
{
    $plugin = get_plugin();
    array_pop($plugin);
    return implode('/', $plugin);
}
