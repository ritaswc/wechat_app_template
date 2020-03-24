<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/6
 * Time: 10:22
 */
function classLoader($class)
{
    $class = str_replace('luweiss\\wechat\\', '', $class);
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/src/' . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
    }else{
        echo $file;
        exit;
    }
}

spl_autoload_register('classLoader');