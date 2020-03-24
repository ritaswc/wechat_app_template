<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/3 10:26
 */


namespace app\hejiang\cloud\forms;


use app\hejiang\cloud\HttpClient;
use app\models\Model;
use app\utils\CurlHelper;
use Comodojo\Zip\Zip;

class UpdateForm extends Model
{
    public $src_file;
    public $db_file;

    public function rules()
    {
        return [
            [['src_file', 'db_file'], 'required'],
            [['src_file', 'db_file'], 'url'],
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            if (!extension_loaded('zip')) {
                throw new \Exception('PHP环境尚未安装ZIP扩展，无法更新。');
            }
            $srcFile = $this->downloadFile($this->src_file);
            $dbFile = $this->downloadFile($this->db_file);
            $this->updateDb($dbFile);
            $this->updateSrc($srcFile);
            return [
                'code' => 0,
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
            ];
        }
    }

    private function downloadFile($url)
    {
        $saveDir = \Yii::$app->runtimePath . '/update/';
        if (!is_dir($saveDir)) {
            if (!mkdir($saveDir)) {
                throw new \Exception('目录创建失败，请检查站点目录是否有写入权限。');
            }
        }
        $saveName = md5($url) . mb_substr($url, mb_strripos($url, '.'));
        CurlHelper::download($url, $saveDir . $saveName);
        if (!file_exists($saveDir . $saveName)) {
            throw new \Exception('更新文件下载失败，请检查站点目录是否有写入权限。');
        }
        return $saveDir . $saveName;
    }

    private function updateDb($dbFile)
    {
        $sql = file_get_contents($dbFile);
        if (!$sql) {
            return;
        }
        $sql = str_replace('hjmall_', \Yii::$app->db->tablePrefix, $sql);
        $sqlList = \SqlFormatter::splitQuery($sql);
        foreach ($sqlList as $_s) {
            try {
                \Yii::$app->db->createCommand($_s)->execute();
            } catch (\Exception $e) {
            }
        }
    }

    private function updateSrc($srcFile)
    {
        if (!is_writable(\Yii::$app->basePath . '/version.json')) {
            throw new \Exception('文件更新失败，请检查站点目录是否有写入权限。');
        }
        $zip = Zip::open($srcFile);
        try {
            $zip->extract(\Yii::$app->basePath);
        } catch (\Exception $e) {
            throw new \Exception('文件更新失败，请检查站点目录是否有写入权限。');
        }
        $zip->close();
    }
}
