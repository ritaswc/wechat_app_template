<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/19
 * Time: 12:10
 */

namespace app\models;

use yii\helpers\VarDumper;

class We7Db
{
    private static $table_prefix = null;

    public static function getTablePrefix()
    {
        if (self::$table_prefix !== null) {
            return self::$table_prefix;
        }
        $config_file = dirname(dirname(dirname(dirname(__DIR__)))) . '/data/config.php';
        if (!file_exists($config_file)) {
            throw new \Exception('微擎配置文件不存在，位置：' . $config_file, 500);
        }
        require $config_file;
        if (!isset($config) || empty($config)) {
            throw new \Exception('读取微擎配置出错，配置信息不存在');
        }
        if (!empty($config['db']['master'])) {
            $prefix = $config['db']['master']['tablepre'];
        } elseif (!empty($config['db'])) {
            $prefix = $config['db']['tablepre'];
        } else {
            throw new \Exception('读取微擎配置出错，数据库配置不存在');
        }
        self::$table_prefix = $prefix;
        return self::$table_prefix;
    }

    public static function getTableName($tableName)
    {
        if (empty($tableName)) {
            return null;
        }
        $prefix = self::getTablePrefix();
        if ($prefix === null || $prefix === '') {
            return $tableName;
        }
        return $prefix . $tableName;
    }
}
