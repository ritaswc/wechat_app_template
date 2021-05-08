<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/22
 * Time: 14:53
 */

namespace app\modules\api\models\group;

use app\utils\GenerateShareQrcode;
use app\utils\GrafikaHelper;
use app\models\Goods;
use app\models\OrderDetail;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\Store;
use app\models\YyGoods;
use app\modules\api\models\ApiModel;
use Curl\Curl;
use Grafika\Color;
use Grafika\Grafika;
use yii\helpers\VarDumper;

class OrderGoodsQrcodeForm extends ApiModel
{
    public $store_id;
    public $order_id;
    public $user_id;

    public function rules()
    {
        return [
            [['order_id'], 'required'],
        ];
    }

    public function search()
    {
        $order = PtOrder::findOne($this->order_id);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在',
            ];
        }
        $od = PtOrderDetail::findOne(['order_id'=>$order->id]);
        $goods = PtGoods::findOne([$od->goods_id]);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在',
            ];
        }
        $store = Store::findOne($this->store_id);
        $goods_pic_url = $goods->cover_pic;

        $goods_pic_save_path = \Yii::$app->basePath . '/web/temp/';
        if (!file_exists($goods_pic_save_path)) {
            mkdir($goods_pic_save_path);
        }
        $goods_pic_save_name = md5("v=1.6.2&goods_id={$goods->id}&order_id={$order->id}&goods_name={$goods->name}&store_name={$store->name}&user_id={$this->user_id}&goods_pic_url={$goods_pic_url}") . '.jpg';

        $pic_url = str_replace('http://', 'https://', \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $goods_pic_save_name);
        if (file_exists($goods_pic_save_path . $goods_pic_save_name)) {
            return [
                'code' => 0,
                'data' => [
                    'goods_name' => $goods->name,
                    'pic_url' => $pic_url . '?v=' . time(),
                ],
            ];
        }

        $goods_pic_path = $this->saveTempImage($goods_pic_url);
        if (!$goods_pic_path) {
            return [
                'code' => 1,
                'msg' => '获取商品海报失败：商品图片丢失',
            ];
        }

        $goods_qrcode_dst = \Yii::$app->basePath . '/web/statics/images/goods-qrcode-dst-1.png';
        $font_path = \Yii::$app->basePath . '/web/statics/font/st-heiti-light.ttc';

        $editor = Grafika::createEditor(GrafikaHelper::getSupportEditorLib());
        $editor->open($goods_qrcode, $goods_qrcode_dst);
        $editor->open($goods_pic, $goods_pic_path);

        //获取小程序码图片
        $wxapp_qrcode_file_res = $this->getQrcode($order->id, $this->user_id);
        if ($wxapp_qrcode_file_res['code'] == 1) {
            unlink($goods_pic_path);
            return [
                'code' => 1,
                'msg' => '获取商品海报失败：获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
            ];
        }
        $wxapp_qrcode_file_path = $wxapp_qrcode_file_res['file_path'];
        $editor->open($wxapp_qrcode, $wxapp_qrcode_file_path);

        $user = \Yii::$app->user->identity;
        // 用户头像
        $user_pic_path = $this->saveTempImage($user->avatar_url);
        if (!$user_pic_path) {
            return [
                'code' => 1,
                'msg' => '获取商品海报失败：用户头像丢失',
            ];
        }

        list($w,$h) = getimagesize($user_pic_path);
        $user_pic_path = $this->test($user_pic_path, $goods_pic_save_path, $w, $h);
        $editor->open($user_pic, $user_pic_path);
        //调整用户头像图片
        $editor->resizeExactWidth($user_pic, 68);
        //附加用户头像图片
        $editor->blend($goods_qrcode, $user_pic, 'normal', 1.0, 'top-left', 30, 30);

        // 用户名处理
        $username = $this->setName($user->nickname);
        $editor->text($goods_qrcode, $username, 20, 128, 56, new Color('#5b85cf'), $font_path, 0);
        $namewitch = imagettfbbox(20, 0, $font_path, $username);
//        var_dump($namewitch[2]);die();
        $editor->text($goods_qrcode, '邀请您一起拼单！', 20, (132+$namewitch[2]), 56, new Color('#353535'), $font_path, 0);

        //裁剪商品图片
        //$editor->crop($goods_pic, 670, 670, 'smart');
        $editor->resizeFill($goods_pic, 690, 690);
        //附加商品图片
        $editor->blend($goods_qrcode, $goods_pic, 'normal', 1.0, 'top-left', 30, 126);

        $name_size = 20;
        $name_width = 670;
        //商品名称处理换行
        $name = $this->autowrap($name_size, 0, $font_path, $goods->name, $name_width, 2);
        //加商品名称
        $editor->text($goods_qrcode, $name, $name_size, 30, 844, new Color('#353535'), $font_path, 0);

        // 商品价格钱币符
        $editor->text($goods_qrcode, '￥', 20, 30, 962, new Color('#ff5c5c'), $font_path, 0);
        //加商品价格
        $editor->text($goods_qrcode, $goods->price, 34, 48, 950, new Color('#ff5c5c'), $font_path, 0);

        //调整小程序码图片
        $editor->resizeExactWidth($wxapp_qrcode, 160);
        //附加小程序码图片
        $editor->blend($goods_qrcode, $wxapp_qrcode, 'normal', 1.0, 'top-left', 536, 948);

        //保存图片
        $editor->save($goods_qrcode, $goods_pic_save_path . $goods_pic_save_name, 'jpeg', 85);

        //删除临时图片
        unlink($goods_pic_path);
        unlink($wxapp_qrcode_file_path);
        unlink($user_pic_path);

        return [
            'code' => 0,
            'data' => [
                'goods_name' => $goods->name,
                'pic_url' => $pic_url . '?v=' . time(),
            ],
        ];
    }

    /**
     * @param $order_id
     * @param $user_id
     * @return array
     * 生成二维码
     */
    private function getQrcode($order_id, $user_id)
    {
        if(\Yii::$app->fromAlipayApp()){
            $scene = "oid={$order_id}&uid={$user_id}";
        }else{
            $scene = "oid:{$order_id},uid:{$user_id}";
        }
        $res = GenerateShareQrcode::getQrcode($this->store_id,$scene,240,'pages/pt/group/details');
        return $res;
    }

    /**
     * @param integer $fontsize 字体大小
     * @param integer $angle 角度
     * @param string $fontface 字体名称
     * @param string $string 字符串
     * @param integer $width 预设宽度
     */
    private function autowrap($fontsize, $angle, $fontface, $string, $width, $max_line = null)
    {
        // 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
        $content = "";
        // 将字符串拆分成一个个单字 保存到数组 letter 中
        $letter = [];
        for ($i = 0; $i < mb_strlen($string); $i++) {
            $letter[] = mb_substr($string, $i, 1);
        }
        $line_count = 0;
        foreach ($letter as $l) {
            $teststr = $content . " " . $l;
            $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
            // 判断拼接后的字符串是否超过预设的宽度
            if (($testbox[2] > $width) && ($content !== "")) {
                $line_count++;
                if ($max_line && $line_count >= $max_line) {
                    $content = mb_substr($content, 0, -1) . "...";
                    break;
                }
                $content .= "\n";
            }
            $content .= $l;
        }
        return $content;
    }

    /**
     * @param $text
     * @return mixed
     * 设置用户名
     */
    public function setName($text)
    {
        if (strlen($text) > 10) {
            $text = substr_replace($text, '...', 10);
        }
        return $text;
    }

    /**
     * @param $url
     * @return string
     * 获取网络图片到临时目录
     */
    private function saveTempImage($url)
    {
        $wdcp_patch = false;
        $wdcp_patch_file = \Yii::$app->basePath . '/patch/wdcp.json';
        if (file_exists($wdcp_patch_file)) {
            $wdcp_patch = json_decode(file_get_contents($wdcp_patch_file), true);
            if ($wdcp_patch && in_array(\Yii::$app->request->hostName, $wdcp_patch)) {
                $wdcp_patch = true;
            } else {
                $wdcp_patch = false;
            }
        }
        if ($wdcp_patch) {
            $url = str_replace('http://', 'https://', $url);
        }

        if (!is_dir(\Yii::$app->runtimePath . '/image')) {
            mkdir(\Yii::$app->runtimePath . '/image');
        }
        $save_path = \Yii::$app->runtimePath . '/image/' . md5($url) . '.jpg';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $img = curl_exec($ch);
        curl_close($ch);
        $fp = fopen($save_path, 'w');
        fwrite($fp, $img);
        fclose($fp);
        return $save_path;
    }

    /**
     * @param $content
     * @return string
     * 保存图片内容到临时文件
     */
    private function saveTempImageByContent($content)
    {
        $save_path = \Yii::$app->runtimePath . '/image/' . md5(base64_encode($content)) . '.jpg';
        $fp = fopen($save_path, 'w');
        fwrite($fp, $content);
        fclose($fp);
        return $save_path;
    }

    /**
     * @param $url
     * @param string $path
     * @param $w
     * @param $h
     * @return string
     * 将图片设置成圆角
     */
    public function test($url, $path = './', $w, $h)
    {
        $original_path= $url;
        $dest_path = $path.uniqid().'.png';
        $src = imagecreatefromstring(file_get_contents($original_path));
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
            imagepng($newpic, $dest_path);
            imagedestroy($newpic);
            imagedestroy($src);
            unlink($url);
        return $dest_path;
    }
}
