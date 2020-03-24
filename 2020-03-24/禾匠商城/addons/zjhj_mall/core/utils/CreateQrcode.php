<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 9:04
 */

namespace app\utils;

/**
 * @property \app\models\Qrcode $store_qrcode;
 */
class CreateQrcode
{
    public $store_qrcode;//店铺推广海报设置

    public $qrcode_bg;//海报背景图  750×1206
    public $qrcode;//二维码  默认420×420
    public $avatar;//头像
    public $name;//昵称

    public $avatar_w = 180;//头像宽
    public $avatar_h = 180;//头像高

    public $avatar_x = 30;//头像x坐标
    public $avatar_y = 290;//头像y坐标
    public $qrcode_w = 280;//二维码宽度
    public $qrcode_x = 188;//二维码x坐标
    public $qrcode_y = 526;//二维码y坐标
    public $qrcode_true = 'true';//二维码y坐标
    public $font_size = 36;//字体大小
    public $font_color = ['r' => '0', 'g' => '0', 'b' => '0'];//字体大小
    public $font_x = 364;//字体x坐标
    public $font_y = 360;//字体y坐标
    public $save_name;//字体y坐标

    /**
     * @return array
     * 不处理成圆形
     */
    public function ungetQrcode()
    {
        $store_qrcode = $this->store_qrcode;
        //创建图片的实例
        $qrcode_bg = imagecreatefromstring(file_get_contents($this->qrcode_bg));
        $qrcode = imagecreatefromstring(file_get_contents($this->qrcode));
        $avatar = imagecreatefromstring(file_get_contents($this->avatar));

        //获取水印图片的宽高
        list($qrcode_w, $qrcode_h,$qrcode_type) = getimagesize($this->qrcode);
        list($avatar_w, $avatar_h,$avatar_type) = getimagesize($this->avatar);
        list($qrcode_bg_w, $qrcode_bg_h) = getimagesize($store_qrcode->qrcode_bg);

        //压缩图片
        $new_width = $this->avatar_w;
        $new_height = $this->avatar_h;
        $image_thump = imagecreatetruecolor($new_width, $new_height);
        //将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度
        imagecopyresampled($image_thump, $avatar, 0, 0, 0, 0, $new_width, $new_height, $avatar_w, $avatar_h);
        imagedestroy($avatar);
        $avatar = $image_thump;


        //将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
        imagecopymerge($qrcode_bg, $qrcode, $this->qrcode_x, $this->qrcode_y, 0, 0, $qrcode_w, $qrcode_h, 100);
        imagecopymerge($qrcode_bg, $avatar, $this->avatar_x, $this->avatar_y, 0, 0, $new_width, $new_height, 100);
        //如果水印图片本身带透明色，则使用imagecopy方法
        //imagecopy($dst, $src, 10, 10, 0, 0, $src_w, $src_h);

        $font = \Yii::$app->basePath . '/web/statics/font/AaBanSong.ttf';//字体
//        $white = imagecolorallocate($qrcode_bg, 255, 255, 255);//字体颜色
//        imagefttext($qrcode_bg, 36, 0, 254, 360, $white,$font, '我是：');
        $purple = imagecolorallocate($qrcode_bg, $this->font_size['r'], $this->font_size['g'], $this->font_size['b']);//字体颜色
        imagefttext($qrcode_bg, $this->font_size, 0, $this->font_x, $this->font_y, $purple, $font, $this->name);
//        imagefttext($qrcode_bg, 36, 0, 254, 420, $white,$font, '代言真的有钱赚！');
//        imagefttext($qrcode_bg, 32, 0, 284, 1000, $white,$font, '扫描二维码');
//        imagefttext($qrcode_bg, 32, 0, 264, 1050, $white,$font, '和我一起赚钱！');

        //输出图片
        list($qrcode_bg_w, $qrcode_bg_h, $qrcode_bg_type) = getimagesize($qrcode_bg);
        switch ($qrcode_bg_type) {
            case 1://GIF
                header('Content-Type: image/gif');
                imagegif($qrcode_bg);
                break;
            case 2://JPG
                header('Content-Type: image/jpeg');
                imagejpeg($qrcode_bg);
                break;
            case 3://PNG
                header('Content-Type: image/png');
                imagepng($qrcode_bg);
                break;
            default:
                break;
        }
        $saveRoot = \Yii::$app->basePath . '/web/';
        $saveDir = 'qrcode/';
        if (!is_dir($saveRoot . $saveDir)) {
            mkdir($saveRoot . $saveDir);
            file_put_contents($saveRoot . $saveDir . '.gitignore', "*\r\n!.gitignore");
        }
        $webRoot = \Yii::$app->request->baseUrl . '/';
        $saveName = md5(uniqid()) . '.jpg';
        imagepng($qrcode_bg, $saveRoot . $saveDir . $saveName);
//        file_put_contents($saveRoot.$saveDir.$saveName,$qrcode_bg);
        imagedestroy($qrcode_bg);
        imagedestroy($qrcode);
        imagedestroy($avatar);
        $qrcode = \Yii::$app->request->hostInfo . $webRoot . $saveDir . $saveName;
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => $qrcode
        ];
    }


    /**
     * @todo : 本函数用于 将方形的图片压缩后
     *     再裁减成圆形
     *     与背景图合并
     * @return
     */
    public function getQrcode()
    {
        //头像
        $headimgurl = $this->avatar;
        //二维码
        $qrcode = $this->qrcode;
        //背景图
        $bgurl = $this->qrcode_bg;
        $imgs['dst'] = $bgurl;
        //保存到本地  图片临时存储路径
        $saveRoot = \Yii::$app->basePath . '/web/temp/';
        $saveDir = '';
        if (!is_dir($saveRoot . $saveDir)) {
            mkdir($saveRoot . $saveDir);
            file_put_contents($saveRoot . $saveDir . '.gitignore', "*\r\n!.gitignore");
        }
        $webRoot = \Yii::$app->request->baseUrl . '/';


        //第一步 压缩图片
        $imggzip = $this->resize_img($headimgurl, $saveRoot.$saveDir, $this->avatar_w);
        $qrcodezip = $this->resize_img($qrcode, $saveRoot.$saveDir, $this->qrcode_w);
        //第二步 裁减成圆角图片
        $imgs['src'] = $this->test($imggzip, $saveRoot.$saveDir, $this->avatar_w, $this->avatar_w);
        $qrcode_info = getimagesize($qrcode);
        $imgs['qrcode'] = $this->test($qrcodezip, $saveRoot.$saveDir, $this->qrcode_w, $this->qrcode_w, $this->qrcode_true);
        //第三步 合并图片
        $dest = $this->mergerImg($imgs, $saveRoot);
//        return [
//            'code'=>0,
//            'msg'=>'success',
//            'data'=>\Yii::$app->request->hostInfo . $webRoot.'qrcode/'.$dest[1]
//        ];
        unlink($qrcode);
        return $dest[1];
    }

    /**
     * @param $url
     * @param string $path
     * @return string
     * 压缩图片
     */
    public function resize_img($url, $path = './', $old_width)
    {
        $imgname = $path.uniqid('r', true).'.jpg';
        $file = $url;
        list($width, $height) = getimagesize($file); //获取原图尺寸
        $percent = ($old_width/$width);
        //缩放尺寸
        $newwidth = $width * $percent;
        $newheight = $height * $percent;
        $src_im = imagecreatefromjpeg($file);
        $dst_im = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        imagejpeg($dst_im, $imgname); //输出压缩后的图片
        imagedestroy($dst_im);
        imagedestroy($src_im);
        return $imgname;
    }
    //第一步生成圆角图片
    public function test($url, $path = './', $w, $h, $is_true = 'true')
    {
//        $w = 110; $h=110; // original size
        $original_path= $url;
        $dest_path = $path.uniqid('r', true).'.png';
        $src = imagecreatefromstring(file_get_contents($original_path));
        if ($is_true == 'true') {
            $newpic = imagecreatetruecolor($w, $h);
            imagealphablending($newpic, false);
            $transparent = imagecolorallocatealpha($newpic, 0, 0, 0, 127);
            $r=$w/2;
            for ($x=0; $x<$w; $x++) {
                for ($y=0; $y<$h; $y++) {
                    $c = imagecolorat($src, $x, $y);
                    $_x = $x - $w/2;
                    $_y = $y - $h/2;
                    if ((($_x*$_x) + ($_y*$_y)) < ($r*$r)) {
                        imagesetpixel($newpic, $x, $y, $c);
                    } else {
                        imagesetpixel($newpic, $x, $y, $transparent);
                    }
                }
            }
            imagesavealpha($newpic, true);
            // header('Content-Type: image/png');
            imagepng($newpic, $dest_path);
            imagedestroy($newpic);
            imagedestroy($src);
            unlink($url);
        } else {
            imagesavealpha($src, true);
            // header('Content-Type: image/png');
            imagepng($src, $dest_path);
            unlink($url);
        }
        return $dest_path;
    }
    //php 合并图片
    public function mergerImg($imgs, $path = './')
    {
        $name = $this->save_name;
        $imgname = $path.$name;
        list($max_width, $max_height,$qrcode_bg_type) = getimagesize($imgs['dst']);
        $dests = imagecreatetruecolor($max_width, $max_height);
        switch ($qrcode_bg_type) {
            case 1://GIF
                $dst_im = imagecreatefromgif($imgs['dst']);
                break;
            case 2://JPG
                $dst_im = imagecreatefromjpeg($imgs['dst']);
                break;
            case 3://PNG
                $dst_im = imagecreatefrompng($imgs['dst']);
                break;
            default:
                $dst_im = imagecreatefromstring(file_get_contents($imgs['dst']));
                break;
        }
        imagecopy($dests, $dst_im, 0, 0, 0, 0, $max_width, $max_height);
        imagedestroy($dst_im);
        $src_im = imagecreatefrompng($imgs['src']);
        $src_info = getimagesize($imgs['src']);
        $qrcode_im = imagecreatefrompng($imgs['qrcode']);
        $qrcode_info = getimagesize($imgs['qrcode']);
        imagecopy($dests, $src_im, $this->avatar_x, $this->avatar_y, 0, 0, $src_info[0], $src_info[1]);
        imagedestroy($src_im);
        imagecopy($dests, $qrcode_im, $this->qrcode_x, $this->qrcode_y, 0, 0, $qrcode_info[0], $qrcode_info[1]);
        imagedestroy($qrcode_im);


        $font = \Yii::$app->basePath . '/web/statics/font/AaBanSong.ttf';//字体
        $purple = imagecolorallocate($dests, $this->font_color['r'], $this->font_color['g'], $this->font_color['b']);//字体颜色
        imagefttext($dests, $this->font_size, 0, $this->font_x, $this->font_y, $purple, $font, $this->name);
        //字体x,y坐标是字符串的左下角坐标

        // var_dump($imgs);exit;
        // header("Content-type: image/jpeg");
        imagejpeg($dests, $imgname);
        unlink($imgs['dst']);
        unlink($imgs['src']);
        unlink($imgs['qrcode']);
        return [$dests,$name];
    }
}
