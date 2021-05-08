<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/3
 * Time: 11:47
 */

namespace app\modules\mch\models\goods;


use app\models\Goods;
use app\models\GoodsPic;
use app\modules\mch\models\MchModel;
use yii\web\UploadedFile;

class Taobaocsv extends MchModel
{
    public $store_id;

    public $excel;
    public $zip;
    public $mch_id;

    public function rules()
    {
        return [
            [['excel'], 'file', 'extensions' => ['excel']],
            [['zip'], 'file', 'extensions' => ['zip']],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        set_time_limit(0);
        $filename = $_FILES['excel']['name'];
        $tmpname = $_FILES['excel']['tmp_name'];
        $path = \Yii::$app->basePath . '/web/temp/';
        if(!is_dir($path)){
            mkdir($path);
        }
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (($ext != 'xlsx') && ($ext != 'xls')) {
            return [
                'code' => 1,
                'msg' => '请上传excel文件'
            ];
        }
        $file = time() . $this->store_id . '.' . $ext;
        $uploadfile = $path . $file;
        $result = move_uploaded_file($tmpname, $uploadfile);
        // 加载zip
        $imagePath = \Yii::$app->basePath . '/web/uploads/image/tbi/' . date('Y') . '/' . date('m') . '/';
        try {
            $this->get_zip_originalsize($_FILES['zip']['tmp_name'], $imagePath);
        } catch (\Exception $e) {
            unlink($uploadfile);
            return [
                'code' => 1,
                'msg' => $e->getMessage()
            ];
        }

        // 读取Excel文件
        $reader = \PHPExcel_IOFactory::createReader(($ext == 'xls' ? 'Excel5' : 'Excel2007'));
        $excel = $reader->load($uploadfile);
        $sheet = $excel->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnCount = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $row = 1;
        $colIndex = [];
        $arr = [];
        while ($row <= $highestRow) {
            $rowValue = array();
            $col = 0;
            while ($col < $highestColumnCount) {
                $rowValue[] = (string)$sheet->getCellByColumnAndRow($col, $row)->getValue();
                ++$col;
            }
            if(count($rowValue) == 0){
                unlink($uploadfile);
                return [
                    'code' => 1,
                    'msg' => '上传文件内容不符合规范'
                ];
            }else{
                if($row == 1){

                }else if($row == 2){
                    $colIndex = array_flip($rowValue);
                }else if($row == 3){
                }else{
                    $newItem = [
                        'title' => $rowValue[$colIndex['title']],
                        'price' => $rowValue[$colIndex['price']],
                        'num' => $rowValue[$colIndex['num']],
                        'description' => $rowValue[$colIndex['description']],
                    ];
                    $picContents = $rowValue[$colIndex['picture']];
                    $allpics = explode(';', $picContents);
                    $pics = array();
                    $optionpics = array();

                    foreach ($allpics as $imgurl) {
                        if (empty($imgurl)) {
                            continue;
                        }

                        $picDetail = explode('|', $imgurl);
                        $picDetail = explode(':', $picDetail[0]);
                        $imgRootUrl = str_replace('http://', 'https://', \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/uploads/image/tbi/' . date('Y') . '/' . date('m') . '/' . $picDetail[0] . '.png');
                        $imgurl = $imagePath . $picDetail[0] . '.png';
                        if (@fopen($imgurl, 'r')) {
                            if ($picDetail[1] == 1) {
                                $pics[] = $imgRootUrl;
                            }

                            if ($picDetail[1] == 2) {
                                $optionpics[$picDetail[0]] = $imgRootUrl;
                            }
                        }
                    }

                    $newItem['pics'] = $pics;
                    $res = $this->save($newItem);
                    if($res){
                        $arr[] = $res;
                    }
                }
            }
            ++$row;
        }
        $count = count($arr);
        unlink($uploadfile);
        return [
            'code' => 0,
            'msg' => "共导入{$count}条数据"
        ];
    }

    // 获取字旁文件的内容
    private function get_zip_originalsize($filename, $path)
    {
        if (!file_exists($filename)) {
            throw new \Exception('文件不存在', 1);
        }

        $filename = iconv('utf-8', 'gb2312', $filename);
        $path = iconv('utf-8', 'gb2312', $path);
        $resource = zip_open($filename);

        while ($dir_resource = zip_read($resource)) {
            if (zip_entry_open($resource, $dir_resource)) {
                $file_name = $path . zip_entry_name($dir_resource);
                $file_path = substr($file_name, 0, strrpos($file_name, '/'));

                if (!is_dir($file_path)) {
                    mkdir($file_path, 511, true);
                }

                if (!is_dir($file_name)) {
                    $file_size = zip_entry_filesize($dir_resource);

                    if ($file_size < 1024 * 1024 * 10) {
                        $file_content = zip_entry_read($dir_resource, $file_size);
                        $ext = strrchr($file_name, '.');

                        if ($ext == '.png') {
                            file_put_contents($file_name, $file_content);
                        } else {
                            if ($ext == '.tbi') {
                                $file_name = substr($file_name, 0, strlen($file_name) - 4);
                                file_put_contents($file_name . '.png', $file_content);
                            }
                        }
                    }
                }

                zip_entry_close($dir_resource);
            }
        }

        zip_close($resource);
    }

    private function save($list = [])
    {
        if(count($list) == 0){
            return false;
        }
        $goods = new Goods();
        $goods->name = $list['title'];
        $goods->store_id = $this->store_id;
        $goods->price = $list['price'];
        $goods->original_price = $list['price'];
        $goods->goods_num = $list['num'];
        $goods->use_attr = 0;
        $goods->detail = $list['description'];
        $goods->cover_pic = count($list['pics']) >= 1 ? $list['pics'][0] : '';
        if($this->mch_id){
            $goods->mch_id = $this->mch_id;
        }else{
            $goods->mch_id = 0;
        }

        list($default_attr, $default_attr_group) = Goods::getDefaultAttr($this->store_id);
        $attr = [
            [
                'attr_list' => [
                    [
                        'attr_group_name' => $default_attr_group->attr_group_name,
                        'attr_id' => $default_attr->id,
                        'attr_name' => $default_attr->attr_name,
                    ],
                ],
                'num' => intval($goods->goods_num) ? intval($goods->goods_num) : 0,
                'price' => $goods->price,
                'no' => ''
            ],
        ];
        $goods->attr = \Yii::$app->serializer->encode($attr);
        if($goods->save()){
            //商品图片保存
            foreach ($list['pics'] as $pic_url) {
                $goods_pic = new GoodsPic();
                $goods_pic->goods_id = $goods->id;
                $goods_pic->pic_url = $pic_url;
                $goods_pic->is_delete = 0;
                $goods_pic->save();
            }
            return true;
        }else{
            return false;
        }
    }
}