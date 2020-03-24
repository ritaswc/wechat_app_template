<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/8
 * Time: 23:36
 */

namespace app\models;

use app\utils\GrafikaHelper;
use Grafika\Grafika;
use Grafika\ImageInterface;
use Hejiang\Storage\Drivers\Local;
use Hejiang\Storage\Helpers\UrlConverter;
use OSS\OssClient;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use yii\base\Exception;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * @property UploadConfig $upload_config
 * @property Store $store
 */
class UploadForm extends Model
{
    public $upload_config;
    public $store;
    public $group_id;
    public $mch_id;

    public $image;
    public $file;
    public $video;

    public static function getMaxUploadSize()
    {
        $upload_max_size = self::get_upload_max_filesize_byte();
        $upload_max_size = intval($upload_max_size / (1024 * 1024));

        $post_max_size = self::get_post_max_filesize_byte();
        $post_max_size = intval($post_max_size / (1024 * 1024));

        return min($upload_max_size, 50, $post_max_size);
    }

    public function rules()
    {
        $min = self::getMaxUploadSize();
        return [
            [['image'], 'file'],
            [['video'], 'file', 'maxSize' => $min * 1024 * 1024, 'message' => '上传视频超过' . $min]
        ];
    }

    private static function get_upload_max_filesize_byte($dec = 2)
    {
        $max_size = ini_get('upload_max_filesize');
        preg_match('/(^[0-9\.]+)(\w+)/', $max_size, $info);
        $size = $info[1];
        $suffix = strtoupper($info[2]);
        $a = array_flip(array("B", "KB", "MB", "GB", "TB", "PB"));
        $b = array_flip(array("B", "K", "M", "G", "T", "P"));
        $pos = $a[$suffix] && $a[$suffix] !== 0 ? $a[$suffix] : $b[$suffix];
        return round($size * pow(1024, $pos), $dec);
    }

    private static function get_post_max_filesize_byte($dec = 2)
    {
        $max_size = ini_get('post_max_size');
        preg_match('/(^[0-9\.]+)(\w+)/', $max_size, $info);
        $size = $info[1];
        $suffix = strtoupper($info[2]);
        $a = array_flip(array("B", "KB", "MB", "GB", "TB", "PB"));
        $b = array_flip(array("B", "K", "M", "G", "T", "P"));
        $pos = $a[$suffix] && $a[$suffix] !== 0 ? $a[$suffix] : $b[$suffix];
        return round($size * pow(1024, $pos), $dec);
    }

    public function saveImageOld($name = null)
    {
        if (!$name) {
            foreach ($_FILES as $_name => $file) {
                $name = $_name;
            }
        }
        if (!$name) {
            $name = 'image';
        }
        $this->image = UploadedFile::getInstanceByName($name);
        if (!$this->validate()) {
            return $this->errors;
        }

        $allow_type = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'webp',];
        if (!in_array($this->image->extension, $allow_type)) {
            return [
                'code' => 1,
                'msg' => '上传文件格式不正确，请上传' . implode('/', $allow_type) . '格式的图片',
            ];
        }

        $fileMd5 = md5_file($this->image->tempName);
        $saveName = $fileMd5 . '.' . $this->image->extension;
        $saveDir = '/web/temuploads/image/' . substr($saveName, 0, 2) . '/';
        $res = $this->saveFile($this->image->tempName, $saveDir, $saveName);
        if ($res['code'] == 0) {
            $res['data']['extension'] = $this->image->extension;
            $res['data']['type'] = $this->getFileType($this->image->extension);
            $res['data']['size'] = $this->image->size;
        }
        $this->saveData($res);
        return $res;
    }

    /**
     * @param string $file //文件路径
     */
    private function saveFile($file_path, $saveDir, $saveName, $type = 0)
    {
        if (!$this->upload_config || $this->upload_config->storage_type == '') {
            return $this->saveToLocal($file_path, $saveDir, $saveName, $type);
        }
        if ($this->upload_config->storage_type == 'aliyun') {
            return $this->saveToAliyun($file_path, $saveDir, $saveName, $type);
        }
        if ($this->upload_config->storage_type == 'qcloud') {
            return [];
        }
        if ($this->upload_config->storage_type == 'qiniu') {
            return $this->saveToQiniu($file_path, $saveDir, $saveName, $type);
        }
    }

    /**
     * @param string $file_path //文件路径
     */
    private function saveToLocal($file_path, $saveDir, $saveName, $type = 0)
    {
        $webRoot = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/';
        $saveRoot = str_replace('\\', '/', \Yii::$app->basePath) . '/web/';
        if (!is_dir($saveRoot . $saveDir)) {
            $this->mkdir($saveRoot . $saveDir);
        }
        if (file_exists($saveRoot . $saveDir . $saveName)) {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'url' => $webRoot . $saveDir . $saveName,
                ],
            ];
        }
        if ($type == 0) {
            move_uploaded_file($file_path, $saveRoot . $saveDir . $saveName);
            $this->compressImage($saveRoot . $saveDir . $saveName);
        } else {
            move_uploaded_file($file_path, $saveRoot . $saveDir . $saveName);
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'url' => $webRoot . $saveDir . $saveName,
            ],
        ];
    }

    /**
     * @param string $file_path //文件路径
     */
    private function saveToQiniu($file_path, $saveDir, $saveName, $type = 0)
    {
        $config = json_decode($this->upload_config->qiniu, true);
        $auth = new Auth($config['access_key'], $config['secret_key']);
        $token = $auth->uploadToken($config['bucket']);
        $uploader = new UploadManager();
        list($res, $err) = $uploader->putFile($token, $saveDir . $saveName, $file_path);
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        $url = $config['domain'] . '/' . $saveDir . $saveName;
        if ($type == 0) {
            $url .= ($config['style_api'] ? '?' . $config['style_api'] : '');
        }
        if (!$err) {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'url' => $url,
                ],
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '上传失败，您的服务器与七牛服务器网络通讯异常：' . $err->getResponse()->error,
            ];
        }
    }


    private function saveData($res)
    {
        \Yii::trace('===>1');
        if ($res['code'] != 0 || !$this->store) {
            return;
        }
        $url = $res['data']['url'];
        $exist = UploadFile::findOne([
            'store_id' => $this->store->id,
            'is_delete' => 0,
            'file_url' => $url,
        ]);
        \Yii::trace('===>2');
        if ($exist) {
            $exist->group_id = $this->group_id;
            $exist->addtime = time();
            $exist->save();
            return;
        }
        $model = new UploadFile();
        $model->store_id = $this->store->id;
        $model->file_url = $url;
        $model->extension = $res['data']['extension'];
        $model->type = $res['data']['type'];
        $model->size = $res['data']['size'];
        $model->addtime = time();
        $model->is_delete = 0;
        if ($this->group_id) {
            $model->group_id = $this->group_id;
        } else {
            $model->group_id = 0;
        }
        if ($this->mch_id) {
            $model->mch_id = $this->mch_id;
        } else {
            $model->mch_id = 0;
        }
        \Yii::trace('===>3');
        $model->save();
        \Yii::trace('===>4' . json_encode($model->errors, JSON_UNESCAPED_UNICODE));
    }

    private function getFileType($extension)
    {
        $type_list = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp',],
            'video' => ['mp4', 'flv', 'ogg', 'mov',],
        ];
        foreach ($type_list as $type => $exs) {
            if (in_array($extension, $exs)) {
                return $type;
            }
        }
        return '';
    }

    private function compressImage($imageFile)
    {
        \Yii::trace("Grafika Start");
        $maxWidth = 1920;
        $maxHeight = 1920;
        $editor = Grafika::createEditor(GrafikaHelper::getSupportEditorLib());
        /* @var  ImageInterface $image */
        $editor->open($image, $imageFile);
        $imageWidth = $image->getWidth();
        $imageHeight = $image->getHeight();
        $newWidth = $imageWidth;
        $newHeight = $imageHeight;
        if ($imageWidth > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = intval($newWidth * $imageHeight / $imageWidth);
        }
        if ($newHeight > $maxHeight) {
            $newHeight = $maxHeight;
            $newWidth = intval($newHeight * $imageWidth / $imageHeight);
        }
        $editor->resize($image, $newWidth, $newHeight);
        $editor->save($image, $imageFile, null, 85);
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

    /**
     * @param null $file_path //文件路径
     * @param null $saveName
     * @return array
     */
    public function saveQrcode($file_path = null, $saveName = null)
    {
        $saveDir = 'uploads/linshi/';
        if (!$this->upload_config || $this->upload_config->storage_type == '') {
            $webRoot = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/';
            try {
                $this->compressImage($file_path);
            } catch (\Exception $e) {
//                var_dump($e);
            }
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'url' => $webRoot . $saveDir . $saveName,
                ],
            ];
        }
        $res = $this->saveFile($file_path, $saveDir, $saveName);
        return $res;
    }

    public function saveVideoOld($name = null)
    {
        if (!$name) {
            foreach ($_FILES as $_name => $file) {
                $name = $_name;
            }
        }
        if (!$name) {
            $name = 'video';
        }
        $this->video = UploadedFile::getInstanceByName($name);
        if (empty($_FILES) || $this->video->error != 0) {
            return [
                'code' => 1,
                'msg' => '上传视频大小超过php配置大小，请检查php上传配置',
                'data' => $_FILES
            ];
        }
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $allow_type = ['mpg', 'm4v', 'mp4', 'avi', 'rmvb', 'mkv'];
        if (!in_array($this->video->extension, $allow_type)) {
            return [
                'code' => 1,
                'msg' => '上传文件格式不正确，请上传' . implode('/', $allow_type) . '格式的视频',
                'data' => $_FILES,
            ];
        }

        $fileMd5 = md5_file($this->video->tempName);
        $saveRoot = str_replace('\\', '/', \Yii::$app->basePath) . '/web/';
        $webRoot = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/';
        $saveName = $fileMd5 . '.' . $this->video->extension;
        $saveDir = 'uploads/video/' . 'store_' . $this->store->id . '/';
        if (!is_dir($saveRoot . $saveDir)) {
            $this->mkdir($saveRoot . $saveDir);
        }
//        if (file_exists($saveRoot . $saveDir . $saveName)) {
//            return [
//                'code' => 0,
//                'msg' => 'success_1',
//                'data' => [
//                    'url' => $webRoot . $saveDir . $saveName,
//                ],
//            ];
//        }
//        $this->video->saveAs($saveRoot . $saveDir . $saveName);
        $res = $this->saveFile($this->video->tempName, $saveDir, $saveName, 1);
        return $res;
    }

    /**
     * 保存到阿里云
     * @param string $file_path //文件路径
     */
    private function saveToAliyun($file_path, $saveDir, $saveName, $type = 0)
    {
        require_once '../extensions/aliyun-oss-php-sdk-2.2.4/autoload.php';
        $config = json_decode($this->upload_config->aliyun, true);
        if ($config['CNAME'] == 0) {
            $ossClient = new OssClient($config['access_key'], $config['secret_key'], $config['domain']);
        } else {
            $ossClient = new OssClient($config['access_key'], $config['secret_key'], $config['domain'], true);
        }
        list($res, $err) = $ossClient->uploadFile($config['bucket'], $saveDir . $saveName, $file_path);
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        if ($config['CNAME'] == 0) {
            $arr = explode('//', $config['domain']);
            $url = $arr[0] . '//' . $config['bucket'] . '.' . $arr[1] . '/' . $saveDir . $saveName;
        } else {
            $url = $config['domain'] . '/' . $saveDir . $saveName;
        }
        if ($type == 0) {
            $url .= ($config['style_api'] ? '?' . $config['style_api'] : '');
        }
        if (!$err) {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'url' => $url,
                ],
            ];
        }
    }

    private function setDriver($name = null)
    {
        if (!$name) {
            foreach ($_FILES as $_name => $file) {
                $name = $_name;
            }
        }
        if (!$name) {
            $name = 'video';
        }

        $storage = \Yii::$app->storage;
        switch ($this->upload_config->storage_type) {
            case 'aliyun':
                $config = \Yii::$app->serializer->decode($this->upload_config->aliyun);
                $driver = [
                    'class' => 'Hejiang\Storage\Drivers\Aliyun',
                    'accessKey' => $config->access_key,
                    'secretKey' => $config->secret_key,
                    'bucket' => $config->bucket,
                    'isCName' => $config->CNAME,
                    'endPoint' => $config->domain,
                ];
                $driver['urlCallback'] = function ($objectUrl, $saveTo) use ($config) {
                    $url = $objectUrl;
                    if ($config->style_api) {
                        $url = $url . '?' . $config->style_api;
                    }
                    return $url;
                };
                break;
            case 'qiniu':
                $config = \Yii::$app->serializer->decode($this->upload_config->qiniu);
                $driver = [
                    'class' => 'Hejiang\Storage\Drivers\Qiniu',
                    'accessKey' => $config->access_key,
                    'secretKey' => $config->secret_key,
                    'bucket' => $config->bucket
                ];
                $driver['urlCallback'] = function ($objectUrl, $saveTo) use ($config) {
                    $url = $config->domain . '/' . $saveTo;
                    if ($config->style_api) {
                        $url = $url . '?' . $config->style_api;
                    }
                    return $url;
                };
                break;
            case 'qcloud':
                $config = \Yii::$app->serializer->decode($this->upload_config->qcloud);
                preg_match('/(.*?)-(\d+)\.cos\.?(.*?)\.myqcloud\.com/', $config->region, $region);
                if (!$region || $region == 0) {
                    return [
                        'code' => 1,
                        'msg' => '默认域名不正确'
                    ];
                }
                $driver = [
                    'class' => 'Hejiang\Storage\Drivers\Qcloud',
                    'accessKey' => $config->secret_id,
                    'secretKey' => $config->secret_key,
                    'bucket' => $config->bucket,
                    'region' => $region[3],
                    'appId' => $region[2],
                    'urlCallBack' => new UrlConverter($config->domain)
                ];
                break;
            default:
                $driver = [
                    'class' => 'Hejiang\Storage\Drivers\Local'
                ];
                break;
        }
        $storage->setDriver($driver);
        return $storage->getUploadedFile($name);
    }

    public function saveImage($name = null)
    {
        $data = Option::get('overrun', 0, 'admin', [
            'max_picture' => 1,
            'max_diy' => 20,
            'over_picture' => 0,
            'over_diy' => 0,
        ]);
        $max = $data['max_picture'] * 1024 * 1024;
        if ($data['over_picture'] == 1) {
            $max = -1;
        }
        $imgFile = isset($_FILES['file']['size']) ? $_FILES['file']['size'] : 0;
        if ($max > 0 && $imgFile > $max) {
            return [
                'code' => 1,
                'msg' => '请上传小于'. $data['max_picture'] .'MB的图片',
            ];
        }
        
        $this->image = $this->setDriver($name);
        if ($max > 0 && $this->image->size > $max) {
            return [
                'code' => 1,
                'msg' => '请上传小于'. $data['max_picture'] .'MB的图片',
            ];
        }
        if (!$this->validate()) {
            return $this->errors;
        }

        $allow_type = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'webp',];
        if (!in_array($this->image->extension, $allow_type)) {
            return [
                'code' => 1,
                'msg' => '上传文件格式不正确，请上传' . implode('/', $allow_type) . '格式的图片',
            ];
        }

        $uniqueName = sha1_file($this->image->tempName);
        $saveDir = 'image/' . 'store_' . $this->store->id . '/' . $uniqueName;
        try {
            $url = $this->image->saveWithOriginalExtension($saveDir);
            if (!$url) {
                return [
                    'code' => 1,
                    'msg' => '文件创建失败'
                ];
            }

            $res = [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'url' => $url,
                    'extension' => $this->image->extension,
                    'size' => $this->image->size,
                    'type' => $this->getFileType($this->image->extension),
                ]
            ];
            $this->saveData($res);
            return $res;
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage()
            ];
        }
    }

    public function saveVideo($name = null)
    {
        $this->video = $this->setDriver($name);
        if (empty($_FILES) || $this->video->error != 0) {
            return [
                'code' => 1,
                'msg' => '上传视频大小超过php配置大小，请检查php上传配置',
                'data' => $_FILES
            ];
        }
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $allow_type = ['mpg', 'm4v', 'mp4', 'avi', 'rmvb', 'mkv'];
        if (!in_array($this->video->extension, $allow_type)) {
            return [
                'code' => 1,
                'msg' => '上传文件格式不正确，请上传' . implode('/', $allow_type) . '格式的视频',
                'data' => $_FILES,
            ];
        }
        $uniqueName = sha1_file($this->video->tempName);
        $saveDir = 'video/' . 'store_' . $this->store->id . '/' . $uniqueName;
        try {
            $url = $this->video->saveWithOriginalExtension($saveDir);
            if (!$url) {
                return [
                    'code' => 1,
                    'msg' => '文件创建失败'
                ];
            }
            $res = [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'url' => $url,
                    'extension' => $this->video->extension,
                    'size' => $this->video->size,
                    'type' => $this->getFileType($this->video->extension)
                ]
            ];
            $this->saveData($res);
            return $res;
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage()
            ];
        }
    }

    // 保存文件到缓存目录
    public function saveFileTemp($name = null)
    {
        if (!$name) {
            foreach ($_FILES as $_name => $file) {
                $name = $_name;
            }
        }
        if (!$name) {
            $name = 'video';
        }

        $storageTemp = \Yii::$app->storageTemp;
        $storageTemp->setDriver([
            'class' => 'Hejiang\Storage\Drivers\Local'
        ]);
        $this->file = $storageTemp->getUploadedFile($name);

        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $allow_type = ['mp4', 'jpg', 'png', 'jpeg', 'gif', 'xlsx', 'xls', 'csv'];
        if (!in_array($this->file->extension, $allow_type)) {
            return [
                'code' => 1,
                'msg' => '上传文件格式不正确，请上传' . implode('/', $allow_type) . '格式的文件',
                'data' => $_FILES,
            ];
        }
        try {
            $url = $this->file->saveAsUniqueHash();
            if (!$url) {
                return [
                    'code' => 1,
                    'msg' => '文件创建失败'
                ];
            }
            $res = [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'url' => $url,
                    'extension' => $this->file->extension,
                    'size' => $this->file->size,
                    'type' => $this->getFileType($this->file->extension)
                ]
            ];
            return $res;
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage()
            ];
        }
    }
}
