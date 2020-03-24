<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/3
 * Time: 14:36
 * Version: 1.5.2
 */

namespace app\models\common\admin\update;

use Comodojo\Zip\Zip;
use Curl\Curl;
use Yii;

class CommonUpdate
{
    private $api_root = 'http://cloud.zjhejiang.com/api/mall';

    public function checkConnect()
    {
        $api = "{$this->api_root}/index";
        $curl = new Curl();
        $curl->get($api, [
            'data' => $this->getSiteData(),
        ]);
        $res = json_decode($curl->response, true);
        if (!$res) {
            return [
                'page' => 'error',
                'msg' => '云服务器连接失败，请检查您的服务器与云服务器的连接是否正常',
                'curl' => $curl,
                'error_code' => $curl->error_code,
            ];
        }
        if ($res['code'] == 1) {
            return [
                'page' => 'error',
                'msg' => $res['msg'],
                'curl' => $curl,
                'error_code' => $curl->error_code,
            ];
        } else {
            return [
                'page' => 'index',
                'version' => hj_core_version(),
                'res' => $res,
                'version_list' => [],
            ];
        }
    }

    public function update()
    {
        if (Yii::$app->request->isPost) {
            $api = "{$this->api_root}/version";
            $target_version = Yii::$app->request->post('target_version');
            $curl = new Curl();
            $curl->get($api, [
                'data' => $this->getSiteData(),
                'target_version' => $target_version,
            ]);
            $res = json_decode($curl->response, true);
            if (!$res) {
                return [
                    'code' => 1,
                    'msg' => '更新失败，与云服务器连接失败',
                ];
            }

            if ($res['code'] != 0) {
                return $res;
            }

            $temp_dir = Yii::$app->basePath . "/temp/update/version/{$target_version}";
            $this->mkdir($temp_dir);
            $src_file = "{$temp_dir}/src.zip";
            $db_file = "{$temp_dir}/db.sql";

            $curl->get($res['data']['src_file']);
            if (!$curl->error) {
                file_put_contents($src_file, $curl->response);
            } else {
                return [
                    'code' => 1,
                    'msg' => '更新失败，更新文件src.zip下载失败',
                ];
            }

            $curl->get($res['data']['db_file']);
            if (!$curl->error) {
                file_put_contents($db_file, $curl->response);
            } else {
                return [
                    'code' => 1,
                    'msg' => '更新失败，更新文件db.sql下载失败',
                ];
            }
            $t = Yii::$app->db->beginTransaction();
            try {
                $sql = file_get_contents($db_file);
                $sql = \SqlFormatter::compress($sql);
                if ($sql) {
                    $sql = str_replace('hjmall_', \Yii::$app->db->tablePrefix, $sql);
                    $sql_list = \SqlFormatter::splitQuery($sql);
                    foreach ($sql_list as $_s) {
                        try {
                            Yii::$app->db->createCommand($_s)->execute();
                        } catch (\Exception $e) {
                        }
                    }
                }
                $zip = Zip::open($src_file);
                $zip->extract(Yii::$app->basePath);
                $zip->close();
                $t->commit();
                return [
                    'code' => 0,
                    'msg' => '版本更新成功，已更新至v' . $target_version,
                ];
            } catch (\Exception $e) {
                $t->rollBack();
                return [
                    'code' => 1,
                    'msg' => '更新失败：' . $e->getMessage(),
                ];
            }
        }
    }

    private function getSiteData()
    {
        $data = base64_encode(json_encode((object)[
            'host' => Yii::$app->request->hostName,
            'from_url' => Yii::$app->request->absoluteUrl,
            'current_version' => hj_core_version(),
        ]));
        return $data;
    }

    private function mkdir($dir)
    {
        if (!is_dir($dir)) {
            if (!$this->mkdir(dirname($dir))) {
                return false;
            }
            if (!mkdir($dir)) {
                return false;
            }
        }
        return true;
    }
}
