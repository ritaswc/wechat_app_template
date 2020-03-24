<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/5/31
 * Time: 14:21
 */


namespace app\modules\mch\models;

class DbOptimizeForm extends MchModel
{
    public $action;

    public function rules()
    {
        return [
            [['action'], 'trim'],
            [['action'], 'required'],
        ];
    }

    public function run()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        switch ($this->action) {
            case 'optimize_engine':
                return $this->optimizeEngine();
                break;
            case 'optimize_charset':
                return $this->optimizeCharset();
                break;
            default:
                return [
                    'code' => 1,
                    'msg' => '错误的请求。',
                ];
                break;
        }
    }

    protected function optimizeEngine()
    {
        $engineName = 'InnoDB';
        $engineList = $this->getSupportDbEngineList();
        if (!in_array($engineName, $engineList)) {
            return [
                'code' => 1,
                'msg' => '您的数据库不支持InnoDB引擎，无法完成操作。',
            ];
        }
        $sqlFile = __DIR__ . '/optimize-sql/optimize-engine.sql';
        if (!file_exists($sqlFile)) {
            return [
                'code' => 1,
                'msg' => '优化的文件不存在，无法完成操作。',
            ];
        }
        $sql = file_get_contents($sqlFile);
        $sql = $this->transSql($sql);
        \Yii::$app->db->createCommand($sql)->execute();
        return [
            'code' => 0,
            'msg' => '操作完成。',
        ];
    }

    protected function optimizeCharset()
    {
        $charsetName = 'utf8mb4';
        $charsetList = $this->getSupportDbCharsetList();
        if (!in_array($charsetName, $charsetList)) {
            return [
                'code' => 1,
                'msg' => '您的数据库不支持utf8mb4字符集，无法完成操作。',
            ];
        }
        $sqlFile = __DIR__ . '/optimize-sql/optimize-charset.sql';
        if (!file_exists($sqlFile)) {
            return [
                'code' => 1,
                'msg' => '优化的文件不存在，无法完成操作。',
            ];
        }
        $sql = file_get_contents($sqlFile);
        $sql = $this->transSql($sql);
        \Yii::$app->db->createCommand($sql)->execute();
        return [
            'code' => 0,
            'msg' => '操作完成。',
        ];
    }

    protected function getSupportDbEngineList()
    {
        $sql = 'SHOW ENGINES';
        $list = \Yii::$app->db->createCommand($sql)->queryAll();
        $engineList = [];
        foreach ($list as $item) {
            if (isset($item['Engine']) && isset($item['Support']) && ($item['Support'] == 'YES' || $item['Support'] == 'DEFAULT')) {
                $engineList[] = $item['Engine'];
            }
        }
        return $engineList;
    }

    protected function getSupportDbCharsetList()
    {
        $sql = 'SHOW CHARSET';
        $list = \Yii::$app->db->createCommand($sql)->queryAll();
        $charsetList = [];
        foreach ($list as $item) {
            if (isset($item['Charset'])) {
                $charsetList[] = $item['Charset'];
            }
        }
        return $charsetList;
    }

    /**
     * 表名称转换（独立版表前缀与微擎版表前缀不一样）
     */
    protected function transSql($sql)
    {
        $rawTablePrefix = 'hjmall_';
        $tablePrefix = \Yii::$app->db->tablePrefix;
        if ($rawTablePrefix == $tablePrefix) {
            return $sql;
        }
        $sql = str_replace($rawTablePrefix, $tablePrefix, $sql);
        return $sql;
    }
}
