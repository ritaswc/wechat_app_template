<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/15
 * Time: 13:39
 */

namespace app\modules\api\models;

use app\utils\CurlHelper;
use app\utils\GenerateShareQrcode;
use app\utils\GrafikaHelper;
use app\models\Goods;
use app\models\PtGoodsDetail;
use app\models\MiaoshaGoods;
use app\models\MsGoods;
use app\models\PtGoods;
use app\models\Qrcode;
use app\models\Store;
use app\models\YyGoods;
use Curl\Curl;
use Grafika\Color;
use Grafika\EditorInterface;
use Grafika\Gd\Editor;
use Grafika\Grafika;
use app\models\Topic;
use app\models\BargainGoods;
use app\models\LotteryGoods;
use app\models\Pic;
use app\models\StepSetting;

class ShareQrcodeForm extends ApiModel
{
    public $store_id;
    public $user;
    public $user_id;
    public $goods_id;
    public $num;
    public $type; //0--商城海报 1--秒杀海报 2--拼团海报 3--预约海报 4--分销海报 5--砍价海报 6--专题海报 7--抽奖海报 8--布数宝海报

    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type'], 'in', 'range' => [0, 1, 2, 3, 4, 5, 6, 7, 8]],
            [['goods_id', 'num'], 'integer']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->user_id = $this->user ? $this->user->id : ($this->user_id ? $this->user_id : 0);
        if ($this->type == 0) {
            return $this->goods_qrcode();
        } elseif ($this->type == 1) {
            return $this->ms_goods_qrcode();
        } elseif ($this->type == 2) {
            return $this->pt_goods_qrcode();
        } elseif ($this->type == 3) {
            return $this->yy_goods_qrcode();
        } elseif ($this->type == 4) {
            return $this->share_qrcode();
        } elseif ($this->type == 5) {
            return $this->bargain_qrcode();
        } elseif ($this->type == 6) {
            return $this->topic_qrcode();
        } elseif ($this->type == 7) {
            return $this->lottery_qrcode();
        } elseif ($this->type == 8) {
            return $this->step_qrcode();
        } else {
            return [
                'code' => 1,
                'msg' => 'error'
            ];
        }
    }

    //商城商品海报
    public function goods_qrcode()
    {
        if (!$this->goods_id) {
            return [
                'code' => 1,
                'msg' => '未知的商品'
            ];
        }
        $goods = Goods::findOne($this->goods_id);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在',
            ];
        }
        $store = Store::findOne($this->store_id);

        $goods_pic_url = $goods->getGoodsPic(0)->pic_url;

        $goods_pic_save_path = \Yii::$app->basePath . '/web/temp/';
        $version = hj_core_version();
        $goods_pic_save_name = md5("v={$version}&goods_id={$goods->id}&goods_name={$goods->name}&store_name={$store->name}&user_id={$this->user_id}&goods_pic_url={$goods_pic_url}&type=0") . '.jpg';

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

        $goods_qrcode_dst = \Yii::$app->basePath . '/web/statics/images/goods-qrcode-dst.jpg';
        $font_path = \Yii::$app->basePath . '/web/statics/font/st-heiti-light.ttc';

        $editor = Grafika::createEditor(GrafikaHelper::getSupportEditorLib());
        $editor->open($goods_qrcode, $goods_qrcode_dst);
        $editor->open($goods_pic, $goods_pic_path);

        //获取小程序码图片
        if (\Yii::$app->fromAlipayApp()) {
            $scene = "gid={$goods->id}&uid={$this->user_id}";
        } else {
            $scene = "gid:{$goods->id},uid:{$this->user_id}";
        }
        $wxapp_qrcode_file_res = $this->getQrcode($scene, 240, "pages/goods/goods");
        if ($wxapp_qrcode_file_res['code'] == 1) {
            unlink($goods_pic_path);
            return [
                'code' => 1,
                'msg' => '获取商品海报失败：获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
            ];
        }
        $wxapp_qrcode_file_path = $wxapp_qrcode_file_res['file_path'];
        $editor->open($wxapp_qrcode, $wxapp_qrcode_file_path);

        $name_size = 30;
        $name_width = 670;
        //商品名称处理换行
        $name = $this->autowrap($name_size, 0, $font_path, $goods->name, $name_width, 2);
        //加商品名称
        $this->reText($editor, $goods_qrcode, $name, $name_size, 40, 750, new Color('#333333'), $font_path, 0);

        //裁剪商品图片
        //$editor->crop($goods_pic, 670, 670, 'smart');
        $editor->resizeFill($goods_pic, 670, 670);
        //附加商品图片
        $editor->blend($goods_qrcode, $goods_pic, 'normal', 1.0, 'top-left', 40, 40);

        $price = [];
        $attrs = json_decode($goods->attr, true);
        foreach ($attrs as $v) {
            if ($v['price'] > 0) {
                $price[] = $v['price'];
            } else {
                $price[] = $goods->price;
            }
        }
        if (max($price) > min($price)) {
            $goods->price = min($price) . '~' . max($price);
        } else {
            $goods->price = min($price);
        }
        //加商品价格
        if ($goods->is_negotiable) {
            $this->reText($editor, $goods_qrcode, '价格面议', 45, 30, 910, new Color('#ff4544'), $font_path, 0);
        } else {
            $this->reText($editor, $goods_qrcode, '￥' . $goods->price, 45, 30, 910, new Color('#ff4544'), $font_path, 0);
        }
        

        //加商城名称
        $this->reText($editor, $goods_qrcode, $store->name, 20, 40, 1170, new Color('#888888'), $font_path, 0);

        //调整小程序码图片
        $editor->resizeFit($wxapp_qrcode, 240, 240);
        //附加小程序码图片
        $editor->blend($goods_qrcode, $wxapp_qrcode, 'normal', 1.0, 'top-left', 470, 1040);

        //保存图片
        $editor->save($goods_qrcode, $goods_pic_save_path . $goods_pic_save_name, 'jpeg', 85);

        //删除临时图片
        unlink($goods_pic_path);
        unlink($wxapp_qrcode_file_path);

        return [
            'code' => 0,
            'data' => [
                'goods_name' => $goods->name,
                'pic_url' => $pic_url . '?v=' . time(),
            ],
        ];
    }

    //秒杀商品海报
    public function ms_goods_qrcode()
    {
        $store = Store::findOne($this->store_id);

        $miaosha_goods = MiaoshaGoods::findOne([
            'id' => $this->goods_id,
            'is_delete' => 0,
        ]);
        $time = date('m.d', strtotime($miaosha_goods->open_date)) . ' ' . $miaosha_goods->start_time . ':00场';
        $goods = MsGoods::findOne($miaosha_goods->goods_id);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在',
            ];
        }
        $attr_data = json_decode($miaosha_goods->attr, true);
        $miaosha_price = 0.00;

        $price = [];
        foreach ($attr_data as $i => $attr_data_item) {
            if ($attr_data_item['miaosha_price'] > 0) {
                $price[] = (float)$attr_data_item['miaosha_price'];
            } else {
                if ($attr_data_item['price'] > 0) {
                    $price[] = (float)$attr_data_item['price'];
                } else {
                    $price[] = (float)$goods->original_price;
                }
            }

            // if ($miaosha_price == 0) {
            //     $miaosha_price = $attr_data_item['miaosha_price'];
            // } else {
            //     $miaosha_price = min($miaosha_price, $attr_data_item['miaosha_price']);
            // }
        }
        if (max($price) > min($price)) {
            $miaosha_price = min($price) . '~' . max($price);
        } else {
            $miaosha_price = min($price);
        }

        $goods_pic_url = $goods->cover_pic;

        $goods_pic_save_path = \Yii::$app->basePath . '/web/temp/';
        $version = hj_core_version();
        $goods_pic_save_name = md5("v={$version}&f=miaosha&goods_id={$miaosha_goods->id}&goods_name={$goods->name}&store_name={$store->name}&user_id={$this->user_id}&goods_pic_url={$goods_pic_url}&type=1") . '.jpg';

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
        if (\Yii::$app->fromAlipayApp()) {
            $scene = "gid={$miaosha_goods->id}&uid={$this->user_id}";
        } else {
            $scene = "gid:{$miaosha_goods->id},uid:{$this->user_id}";
        }
        $wxapp_qrcode_file_res = $this->getQrcode($scene, 240, "pages/miaosha/details/details");
        if ($wxapp_qrcode_file_res['code'] == 1) {
            unlink($goods_pic_path);
            return [
                'code' => 1,
                'msg' => '获取商品海报失败：获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
            ];
        }
        $wxapp_qrcode_file_path = $wxapp_qrcode_file_res['file_path'];
        $editor->open($wxapp_qrcode, $wxapp_qrcode_file_path);

        if ($this->user) {
            $user = $this->user;
            // 用户头像
            $user_pic_path = $this->saveTempImage($user->avatar_url);
            if (!$user_pic_path) {
                return [
                    'code' => 1,
                    'msg' => '获取商品海报失败：用户头像丢失',
                ];
            }

            list($w, $h) = getimagesize($user_pic_path);
            $user_pic_path = $this->test($user_pic_path, $goods_pic_save_path, $w, $h);
            $editor->open($user_pic, $user_pic_path);
            //调整用户头像图片
            $editor->resizeExactWidth($user_pic, 68);
            //附加用户头像图片
            $editor->blend($goods_qrcode, $user_pic, 'normal', 1.0, 'top-left', 30, 30);

            // 用户名处理
            $username = $this->setName($user->nickname);
            $this->reText($editor, $goods_qrcode, $username, 20, 128, 56, new Color('#5b85cf'), $font_path, 0);
            $namewitch = imagettfbbox(20, 0, $font_path, $username);
            $this->reText($editor, $goods_qrcode, '分享给你一个商品', 20, (132 + $namewitch[2]), 56, new Color('#353535'), $font_path, 0);
            unlink($user_pic_path);
        } else {
            $this->reText($editor, $goods_qrcode, '分享给你一个商品', 20, 30, 56, new Color('#353535'), $font_path, 0);
        }

        $ms_qrcode = \Yii::$app->basePath . '/web/statics/images/ms_qrcode.png';
        $editor->open($ms_qrcode, $ms_qrcode);

        $name_size = 25;
        $name_width = 670;
        //商品名称处理换行
        $name = $this->autowrap($name_size, 0, $font_path, $goods->name, $name_width, 2);
        //加商品名称
        $this->reText($editor, $goods_qrcode, $name, $name_size, 30, 844, new Color('#353535'), $font_path, 0);
        //裁剪商品图片
        //$editor->crop($goods_pic, 670, 670, 'smart');
        $editor->resizeFill($goods_pic, 690, 690);
        //附加商品图片
        $editor->blend($goods_qrcode, $goods_pic, 'normal', 1.0, 'top-left', 30, 126);
        //时间背景
        $time_qrcode = \Yii::$app->basePath . '/web/statics/images/545.png';
        $editor->open($time_qrcode, $time_qrcode);
        //调整标识图片
        $editor->resizeExact($time_qrcode, 240, 64);
        $editor->blend($goods_qrcode, $time_qrcode, 'normal', 1.0, 'top-left', 150, 126);

        //$editor->draw($goods_qrcode, Grafika::createDrawingObject('Polygon', array(array(150,126), array(150,190),array(374,190),array(390,126)), 1, null, new Color('#ff7022')));

        $this->reText($editor, $goods_qrcode, $time, 21, 170, 150, new Color('#ffffff'), $font_path, 0);

        //加商品价格
        // $this->reText($editor, $goods_qrcode, '￥' . (empty($miaosha_price) ? $miaosha_price : $goods->original_price), 20, 30, 962, new Color('#ff4544'), $font_path, 0);

        // 商品价格钱币符
        $this->reText($editor, $goods_qrcode, '￥', 20, 30, 1002, new Color('#ff5c5c'), $font_path, 0);
        //加商品价格
        $this->reText($editor, $goods_qrcode, $miaosha_price, 34, 55, 990, new Color('#ff5c5c'), $font_path, 0);

        //加商城名称
        $this->reText($editor, $goods_qrcode, $store->name, 20, 40, 1200, new Color('#888888'), $font_path, 0);

        //调整小程序码图片
        $editor->resizeFit($wxapp_qrcode, 160, 160);
        //附加小程序码图片
        $editor->blend($goods_qrcode, $wxapp_qrcode, 'normal', 1.0, 'top-left', 536, 948);

        //调整标识图片
        $editor->resizeExact($ms_qrcode, 120, 110);
        //附加标识图片
        $editor->blend($goods_qrcode, $ms_qrcode, 'normal', 1.0, 'top-left', 30, 126);

        //保存图片
        $editor->save($goods_qrcode, $goods_pic_save_path . $goods_pic_save_name, 'jpeg', 85);

        //删除临时图片
        unlink($goods_pic_path);
        unlink($wxapp_qrcode_file_path);

        return [
            'code' => 0,
            'data' => [
                'goods_name' => $goods->name,
                'pic_url' => $pic_url . '?v=' . time(),
            ],
        ];
    }

    //拼团海报
    public function pt_goods_qrcode()
    {
        $goods = PtGoods::findOne($this->goods_id);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在',
            ];
        }
        $detail = PtGoodsDetail::find()->where(['store_id' => $this->store_id, 'goods_id' => $this->goods_id])->all();
        $price = [];
        foreach ($detail as $v) {
            foreach (json_decode($v->attr) as $k1 => $v1) {
                if ($v1->price > 0) {
                    $price [] = (float)$v1->price;
                } else {
                    foreach (json_decode($goods->attr) as $k2 => $v2) {
                        if ($v1->attr_list == $v2->attr_list && $v2->price > 0) {
                            $price[] = (float)$v2->price;
                        } else {
                            $price[] = (float)$goods->price;
                        }
                    }
                }
            }
        }
        foreach (json_decode($goods->attr) as $v) {
            if ($v->price > 0) {
                $price[] = (float)$v->price;
            } else {
                $price[] = (float)$goods->price;
            }
        }

        if (max($price) > min($price)) {
            $goods->price = min($price) . '~' . max($price);
        } else {
            $goods->price = min($price);
        }

        $store = Store::findOne($this->store_id);
        $goods_pic_url = $goods->cover_pic;

        $goods_pic_save_path = \Yii::$app->basePath . '/web/temp/';
        if (!file_exists($goods_pic_save_path)) {
            mkdir($goods_pic_save_path);
        }
        $version = hj_core_version();
        $goods_pic_save_name = md5("v={$version}&goods_id={$goods->id}&goods_name={$goods->name}&store_name={$store->name}&user_id={$this->user_id}&goods_pic_url={$goods_pic_url}&type=2") . '.jpg';

        $pic_url = str_replace('http://', 'https://', \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $goods_pic_save_name);
        if (file_exists($goods_pic_save_path . $goods_pic_save_name)) {
//            return [
//                'code' => 0,
//                'data' => [
//                    'goods_name' => $goods->name,
//                    'pic_url' => $pic_url . '?v=' . time(),
//                ],
//            ];
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
        if (\Yii::$app->fromAlipayApp()) {
            $scene = "gid={$goods->id}&uid={$this->user_id}";
        } else {
            $scene = "gid:{$goods->id},uid:{$this->user_id}";
        }
        $page = "pages/pt/details/details";
        $wxapp_qrcode_file_res = $this->getQrcode($scene, 240, $page);
        if ($wxapp_qrcode_file_res['code'] == 1) {
            unlink($goods_pic_path);
            return [
                'code' => 1,
                'msg' => '获取商品海报失败：获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
            ];
        }
        $wxapp_qrcode_file_path = $wxapp_qrcode_file_res['file_path'];
        $editor->open($wxapp_qrcode, $wxapp_qrcode_file_path);

        $pt_qrcode = \Yii::$app->basePath . '/web/statics/images/pt_qrcode.png';
        $editor->open($pt_qrcode, $pt_qrcode);

        //裁剪商品图片
        $editor->resizeFill($goods_pic, 690, 690);
        //附加商品图片
        $editor->blend($goods_qrcode, $goods_pic, 'normal', 1.0, 'top-left', 30, 126);


        if ($this->user) {
            $user = $this->user;
            // 用户头像
            $user_pic_path = $this->saveTempImage($user->avatar_url);
            if (!$user_pic_path) {
                return [
                    'code' => 1,
                    'msg' => '获取商品海报失败：用户头像丢失',
                ];
            }

            list($w, $h) = getimagesize($user_pic_path);
            $user_pic_path = $this->test($user_pic_path, $goods_pic_save_path, $w, $h);
            $editor->open($user_pic, $user_pic_path);
            //调整用户头像图片
            $editor->resizeExactWidth($user_pic, 68);
            //附加用户头像图片
            $editor->blend($goods_qrcode, $user_pic, 'normal', 1.0, 'top-left', 30, 30);

            // 用户名处理
            $username = $this->setName($user->nickname);
            $this->reText($editor, $goods_qrcode, $username, 20, 128, 56, new Color('#5b85cf'), $font_path, 0);
            $namewitch = imagettfbbox(20, 0, $font_path, $username);
            $this->reText($editor, $goods_qrcode, '分享给你一个商品', 20, (132 + $namewitch[2]), 56, new Color('#353535'), $font_path, 0);
            unlink($user_pic_path);
        } else {
            $this->reText($editor, $goods_qrcode, '分享给你一个商品', 20, 30, 56, new Color('#353535'), $font_path, 0);
        }

        $name_size = 25;
        $name_width = 670;
        //商品名称处理换行
        $name = $this->autowrap($name_size, 0, $font_path, $goods->name, $name_width, 2);
        //加商品名称
        $this->reText($editor, $goods_qrcode, $name, $name_size, 30, 844, new Color('#353535'), $font_path, 0);

        // 商品价格钱币符
        $this->reText($editor, $goods_qrcode, '￥', 20, 30, 1002, new Color('#ff5c5c'), $font_path, 0);
        //加商品价格
        $this->reText($editor, $goods_qrcode, $goods->price, 34, 55, 990, new Color('#ff5c5c'), $font_path, 0);

        //加商城名称
        //$this->reText($editor, $goods_qrcode, $store->name, 20, 40, 1170, new Color('#888888'), $font_path, 0);

        //调整标识图片
        $editor->resizeExact($pt_qrcode, 120, 110);
        //附加标识图片
        $editor->blend($goods_qrcode, $pt_qrcode, 'normal', 1.0, 'top-left', 30, 126);

        //调整小程序码图片
        $editor->resizeFit($wxapp_qrcode, 160, 160);
        //附加小程序码图片
        $editor->blend($goods_qrcode, $wxapp_qrcode, 'normal', 1.0, 'top-left', 536, 948);


        //保存图片
        $editor->save($goods_qrcode, $goods_pic_save_path . $goods_pic_save_name, 'jpeg', 85);

        //删除临时图片
        unlink($goods_pic_path);
        unlink($wxapp_qrcode_file_path);

        return [
            'code' => 0,
            'data' => [
                'goods_name' => $goods->name,
                'pic_url' => $pic_url . '?v=' . time(),
            ],
        ];
    }

    //预约海报
    public function yy_goods_qrcode()
    {
        $goods = YyGoods::findOne($this->goods_id);
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
        $version = hj_core_version();
        $goods_pic_save_name = md5("v={$version}&goods_id={$goods->id}&goods_name={$goods->name}&store_name={$store->name}&user_id={$this->user_id}&goods_pic_url={$goods_pic_url}&type=3") . '.jpg';

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
        if (\Yii::$app->fromAlipayApp()) {
            $scene = "gid={$goods->id}&uid={$this->user_id}";
        } else {
            $scene = "gid:{$goods->id},uid:{$this->user_id}";
        }
        $page = "pages/book/details/details";
        $wxapp_qrcode_file_res = $this->getQrcode($scene, 240, $page);
        if ($wxapp_qrcode_file_res['code'] == 1) {
            unlink($goods_pic_path);
            return [
                'code' => 1,
                'msg' => '获取商品海报失败：获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
            ];
        }
        $wxapp_qrcode_file_path = $wxapp_qrcode_file_res['file_path'];
        $editor->open($wxapp_qrcode, $wxapp_qrcode_file_path);
        //裁剪商品图片
        //$editor->crop($goods_pic, 670, 670, 'smart');
        $editor->resizeFill($goods_pic, 690, 690);
        //附加商品图片
        $editor->blend($goods_qrcode, $goods_pic, 'normal', 1.0, 'top-left', 30, 126);

        if ($this->user) {
            $user = $this->user;
            // 用户头像
            $user_pic_path = $this->saveTempImage($user->avatar_url);
            if (!$user_pic_path) {
                return [
                    'code' => 1,
                    'msg' => '获取商品海报失败：用户头像丢失',
                ];
            }

            list($w, $h) = getimagesize($user_pic_path);
            $user_pic_path = $this->test($user_pic_path, $goods_pic_save_path, $w, $h);
            $editor->open($user_pic, $user_pic_path);
            //调整用户头像图片
            $editor->resizeExactWidth($user_pic, 68);
            //附加用户头像图片
            $editor->blend($goods_qrcode, $user_pic, 'normal', 1.0, 'top-left', 30, 30);

            // 用户名处理
            $username = $this->setName($user->nickname);
            $this->reText($editor, $goods_qrcode, $username, 15, 128, 56, new Color('#5b85cf'), $font_path, 0);
            $namewitch = imagettfbbox(15, 0, $font_path, $username);
//        var_dump($namewitch[2]);die();
            $this->reText($editor, $goods_qrcode, '分享给你一个商品', 15, (132 + $namewitch[2]), 56, new Color('#353535'), $font_path, 0);
            unlink($user_pic_path);
        } else {
            $this->reText($editor, $goods_qrcode, '分享给你一个商品', 15, 30, 56, new Color('#353535'), $font_path, 0);
        }

        $name_size = 25;
        $name_width = 670;
        //商品名称处理换行
        $name = $this->autowrap($name_size, 0, $font_path, $goods->name, $name_width, 2);
        //加商品名称
        $this->reText($editor, $goods_qrcode, $name, $name_size, 30, 844, new Color('#353535'), $font_path, 0);

        // 商品价格钱币符
        $this->reText($editor, $goods_qrcode, '预约金额 ￥', 20, 30, 962, new Color('#ff5c5c'), $font_path, 0);
        //加商品价格
        $this->reText($editor, $goods_qrcode, $goods->price, 29, 170, 950, new Color('#ff5c5c'), $font_path, 0);

        //加商城名称
//        $this->reText($editor, $goods_qrcode, $store->name, 20, 40, 1170, new Color('#888888'), $font_path, 0);

        //调整小程序码图片
        $editor->resizeFit($wxapp_qrcode, 160, 160);
        //附加小程序码图片
        $editor->blend($goods_qrcode, $wxapp_qrcode, 'normal', 1.0, 'top-left', 536, 948);
//        $editor->blend($goods_qrcode, $wxapp_qrcode, 'normal', 1.0, 'top-left', 470, 1040);

        //保存图片
        $editor->save($goods_qrcode, $goods_pic_save_path . $goods_pic_save_name, 'jpeg', 85);

        //删除临时图片
        unlink($goods_pic_path);
        unlink($wxapp_qrcode_file_path);

        return [
            'code' => 0,
            'data' => [
                'goods_name' => $goods->name,
                'pic_url' => $pic_url . '?v=' . time(),
            ],
        ];
    }

    //分销海报
    public function share_qrcode()
    {
        $save_root = \Yii::$app->basePath . '/web/temp/';
        if (!is_dir($save_root)) {
            mkdir($save_root);
            file_put_contents($save_root . '.gitignore', "*\r\n!.gitignore");
        }
        $version = hj_core_version();
        $save_name = md5("v={$version}&store_id={$this->store_id}&user_id={$this->user->id}") . '.jpg';
        $pic_url = str_replace('http://', 'https://', \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $save_name);
        if (file_exists($save_root . $save_name)) {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => $pic_url . '?v=' . time()
            ];
        }

        $store_qrcode = Qrcode::findOne(['store_id' => $this->store_id, 'is_delete' => 0]);
        if (!$store_qrcode) {
            return [
                'code' => 1,
                'msg' => '请先在后台设置分销海报'
            ];
        }

        //昵称位置
        $font_position = json_decode($store_qrcode->font_position, true);
        //小程序码位置
        $qrcode_position = json_decode($store_qrcode->qrcode_position, true);
        //头像位置
        $avatar_position = json_decode($store_qrcode->avatar_position, true);
        //头像大小
        $avatar_size = json_decode($store_qrcode->avatar_size, true);
        //小程序码大小
        $qrcode_size = json_decode($store_qrcode->qrcode_size, true);
        //昵称大小
        $font_size = json_decode($store_qrcode->font, true);
        //背景图下载到临时目录
        $qrcode_bg = self::saveTempImage($store_qrcode->qrcode_bg);
        if (!$qrcode_bg) {
            return [
                'code' => 1,
                'msg' => '获取背景图片失败'
            ];
        }
        //用户头像下载到临时目录
        $user_avatar = self::saveTempImage($this->user->avatar_url);
        if (!$user_avatar) {
            return [
                'code' => 1,
                'msg' => '获取用户头像失败'
            ];
        }
        //背景图宽高
        list($qrcode_bg_w, $qrcode_bg_h) = getimagesize($qrcode_bg);
        if ($qrcode_bg_w == 0) {
            return [
                'code' => 1,
                'msg' => '获取背景图片失败'
            ];
        }
        //文字字体
        $font_path = \Yii::$app->basePath . '/web/statics/font/st-heiti-light.ttc';

        //比例尺
        $percent = 750 / 300;

        //获取小程序码图片
        $width = doubleval($qrcode_size['w'] * $percent);
        if (\Yii::$app->fromAlipayApp()) {
            $scene = "user_id={$this->user_id}";
        } else {
            $scene = "{$this->user_id}";
        }
        $wxapp_qrcode_file_res = $this->getQrcode($scene);
        if ($wxapp_qrcode_file_res['code'] == 1) {
            unlink($qrcode_bg);
            return [
                'code' => 1,
                'msg' => '获取商品海报失败：获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
            ];
        }
        $wxapp_qrcode_file_path = $wxapp_qrcode_file_res['file_path'];

        $editor = Grafika::createEditor(['Gd']);
        //获取背景图
        $editor->open($qrcode_bg_dst, $qrcode_bg);
        $editor->resizeExact($qrcode_bg_dst, 750, 1200);
        //获取小程序码
        if (isset($qrcode_size['c']) && $qrcode_size['c'] == 'true') {
            list($w, $h) = getimagesize($wxapp_qrcode_file_path);

            $wxapp_qrcode_file_path = $this->test($wxapp_qrcode_file_path, $save_root, $w, $w);
        }
        $editor->open($wxapp_qrcode_dst, $wxapp_qrcode_file_path);
        $editor->resizeExact($wxapp_qrcode_dst, $width, $width);
        //将小程序码添加到背景图
        $qrcode_x = $qrcode_position['x'] * $percent;
        $qrcode_y = $qrcode_position['y'] * $percent;
        if ($qrcode_x >= 0 && $qrcode_x <= 750 && $qrcode_y >= 0 && $qrcode_y <= 1200) {
            $editor->blend($qrcode_bg_dst, $wxapp_qrcode_dst, 'normal', 1.0, 'top-left', $qrcode_x, $qrcode_y);
        }
        if ($avatar_size['w'] > 0) {
            //获取头像
            $avatar_w = $avatar_size['w'] * $percent;
            $avatar_h = $avatar_size['h'] * $percent;
            $avatar_x = $avatar_position['x'] * $percent;
            $avatar_y = $avatar_position['y'] * $percent;
            if ($avatar_x >= 0 && $avatar_x <= 750 && $avatar_y >= 0 && $avatar_y <= 1200) {
                list($w, $h) = getimagesize($user_avatar);
                $user_avatar = $this->test($user_avatar, $save_root, $w, $h);
                $editor->open($avatar_dst, $user_avatar);
                //裁剪头像
                $editor->resizeExact($avatar_dst, $avatar_w, $avatar_h);
                //将头像添加到背景图
                $editor->blend($qrcode_bg_dst, $avatar_dst, 'normal', 1.0, 'top-left', $avatar_x, $avatar_y);
            }
        }
        if ($font_size['size'] > 0) {
            $color = \app\models\Color::find()->andWhere(['id' => (int)$font_size['color']])->asArray()->one();
            //附加用户昵称
            $font = $font_size['size'] * $percent * 0.74;
            $font_x = $font_position['x'] * $percent;
            $font_y = $font_position['y'] * $percent + 1;
            if ($font_x >= 0 && $font_x <= 750 && $font_y >= 0 && $font_y <= 1200) {
                $this->reText($editor, $qrcode_bg_dst, $this->user->nickname, $font, $font_x, $font_y, new Color($color['color']), $font_path, 0);
            }
        }

        //保存图片
        $editor->save($qrcode_bg_dst, $save_root . $save_name, 'jpeg', 85);

        //删除临时图片
        unlink($qrcode_bg);
        unlink($user_avatar);
        unlink($wxapp_qrcode_file_path);
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => $pic_url . '?v=' . time()
        ];
    }

    //砍价海报
    public function bargain_qrcode()
    {
        $bargain = BargainGoods::find()->where(['goods_id' => $this->goods_id])->with('goods')->one();
        $goods = $bargain['goods'];
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
        $version = hj_core_version();
        $goods_pic_save_name = md5("v={$version}&goods_id={$goods->id}&goods_name={$goods->name}&store_name={$store->name}&user_id={$this->user_id}&goods_pic_url={$goods_pic_url}&type=5") . '.jpg';

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
        if (\Yii::$app->fromAlipayApp()) {
            $scene = "gid={$goods->id}&uid={$this->user_id}";
        } else {
            $scene = "gid:{$goods->id},uid:{$this->user_id}";
        }
        $page = "bargain/goods/goods";
        $wxapp_qrcode_file_res = $this->getQrcode($scene, 240, $page);
        if ($wxapp_qrcode_file_res['code'] == 1) {
            unlink($goods_pic_path);
            return [
                'code' => 1,
                'msg' => '获取商品海报失败：获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
            ];
        }
        $wxapp_qrcode_file_path = $wxapp_qrcode_file_res['file_path'];
        $editor->open($wxapp_qrcode, $wxapp_qrcode_file_path);

        $pt_qrcode = \Yii::$app->basePath . '/web/statics/images/bargain-hb-good.png';
        $editor->open($pt_qrcode, $pt_qrcode);

        //裁剪商品图片
        $editor->resizeFill($goods_pic, 690, 690);
        //附加商品图片
        $editor->blend($goods_qrcode, $goods_pic, 'normal', 1.0, 'top-left', 30, 126);


        if ($this->user) {
            $user = $this->user;
            // 用户头像
            $user_pic_path = $this->saveTempImage($user->avatar_url);
            if (!$user_pic_path) {
                return [
                    'code' => 1,
                    'msg' => '获取商品海报失败：用户头像丢失',
                ];
            }

            list($w, $h) = getimagesize($user_pic_path);
            $user_pic_path = $this->test($user_pic_path, $goods_pic_save_path, $w, $h);
            $editor->open($user_pic, $user_pic_path);
            //调整用户头像图片
            $editor->resizeExactWidth($user_pic, 68);
            //附加用户头像图片
            $editor->blend($goods_qrcode, $user_pic, 'normal', 1.0, 'top-left', 30, 30);

            // 用户名处理
            $username = $this->setName($user->nickname);
            $this->reText($editor, $goods_qrcode, $username, 20, 128, 56, new Color('#5b85cf'), $font_path, 0);
            $namewitch = imagettfbbox(20, 0, $font_path, $username);
            $this->reText($editor, $goods_qrcode, '分享给你一个商品', 20, (132 + $namewitch[2]), 56, new Color('#353535'), $font_path, 0);
            unlink($user_pic_path);
        } else {
            $this->reText($editor, $goods_qrcode, '分享给你一个商品', 20, 30, 56, new Color('#353535'), $font_path, 0);
        }


        $name_size = 25;
        $name_width = 670;
        $end_time = date('m.d H:i', $bargain->end_time);
        //商品名称处理换行
        $name = $this->autowrap($name_size, 0, $font_path, $goods->name, $name_width, 2);
        //加商品名称
        $this->reText($editor, $goods_qrcode, $name, $name_size, 30, 844, new Color('#353535'), $font_path, 0);

        // 商品价格钱币符
        $this->reText($editor, $goods_qrcode, '最低￥', 20, 30, 1002, new Color('#ff5c5c'), $font_path, 0);
        //加商品价格
        $this->reText($editor, $goods_qrcode, $bargain->min_price, 34, 105, 990, new Color('#ff5c5c'), $font_path, 0);

        //加商城名称
        $this->reText($editor, $goods_qrcode, $store->name, 20, 40, 1170, new Color('#888888'), $font_path, 0);

        //调整标识图片
        $editor->resizeExact($pt_qrcode, 690, 120);
        //附加标识图片
        $editor->blend($goods_qrcode, $pt_qrcode, 'normal', 1.0, 'top-center', 0, 696);

        //调整小程序码图片
        $editor->resizeFit($wxapp_qrcode, 160, 160);
        //附加小程序码图片
        $editor->blend($goods_qrcode, $wxapp_qrcode, 'normal', 1.0, 'top-left', 536, 948);

        //倒计时时间
        $this->reText($editor, $goods_qrcode, $end_time, $name_size, 400, 770, new Color('#ffffff'), $font_path, 0);
        //保存图片
        $editor->save($goods_qrcode, $goods_pic_save_path . $goods_pic_save_name, 'jpeg', 85);

        //删除临时图片
        unlink($goods_pic_path);
        unlink($wxapp_qrcode_file_path);

        return [
            'code' => 0,
            'data' => [
                'goods_name' => $goods->name,
                'pic_url' => $pic_url . '?v=' . time(),
            ],
        ];
    }

    /**
     * 专题海报
     * @return [type] [description]
     */
    public function topic_qrcode()
    {
        $goods = Topic::findOne($this->goods_id);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '专题不存在',
            ];
        }

        $store = Store::findOne($this->store_id);

        $goods_pic_url = $goods->qrcode_pic ? $goods->qrcode_pic : $goods->cover_pic;

        $goods_pic_save_path = \Yii::$app->basePath . '/web/temp/';
        $version = hj_core_version();
        $goods_pic_save_name = md5("v={$version}&goods_id={$goods->id}&goods_name={$goods->title}&store_name={$store->name}&user_id={$this->user_id}&goods_pic_url={$goods_pic_url}&type=0") . '.jpg';

        $pic_url = str_replace('http://', 'https://', \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $goods_pic_save_name);
        if (file_exists($goods_pic_save_path . $goods_pic_save_name)) {
            return [
                'code' => 0,
                'data' => [
                    'goods_name' => $goods->title,
                    'pic_url' => $pic_url . '?v=' . time(),
                ],
            ];
        }

        $goods_pic_path = $this->saveTempImage($goods_pic_url);
        if (!$goods_pic_path) {
            return [
                'code' => 1,
                'msg' => '获取海报失败：显示图片丢失',
            ];
        }

        $goods_qrcode_dst = \Yii::$app->basePath . '/web/statics/images/goods-qrcode-dst.jpg';
        $font_path = \Yii::$app->basePath . '/web/statics/font/st-heiti-light.ttc';
        $bargain_hb_down = \Yii::$app->basePath . '/web/statics/images/topic-hb-down.png';

        $editor = Grafika::createEditor(GrafikaHelper::getSupportEditorLib());
        $editor->open($goods_qrcode, $goods_qrcode_dst);
        $editor->open($goods_pic, $goods_pic_path);
        $editor->open($goods_down, $bargain_hb_down);

        //获取小程序码图片
        if (\Yii::$app->fromAlipayApp()) {
            $scene = "gid={$goods->id}&uid={$this->user_id}";
        } else {
            $scene = "gid:{$goods->id},uid:{$this->user_id}";
        }
        $wxapp_qrcode_file_res = $this->getQrcode($scene, 240, "pages/topic/topic");
        if ($wxapp_qrcode_file_res['code'] == 1) {
            unlink($goods_pic_path);
            return [
                'code' => 1,
                'msg' => '获取专题海报失败：获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
            ];
        };

        $wxapp_qrcode_file_path = $wxapp_qrcode_file_res['file_path'];
        $editor->open($wxapp_qrcode, $wxapp_qrcode_file_path);

        $name_size = 25;
        $name_width = 670;

        //专题标题
        $name = $this->autowrap($name_size, 0, $font_path, $goods->title, $name_width, 2);
        $this->reText($editor, $goods_qrcode, $name, 25, 40, 48, new Color('#353535'), $font_path, 0);

        //专题图片
        $editor->resizeFill($goods_pic, 670, 394);
        $editor->blend($goods_qrcode, $goods_pic, 'normal', 1.0, 'top-center', 0, 178);

        //专题浏览
        $read = $goods->virtual_read_count + $goods->read_count;
        if ($read > 10000) {
            $read = ($read / 10000) . '万+人浏览';
        } else {
            $read = $read . '人浏览';
        }
        $this->reText($editor, $goods_qrcode, $read, 20, 40, 604, new Color('#919191'), $font_path, 0);

        //专题内容
        $content = strip_tags($goods->content);
        $content = str_replace(array("\r\n", "\r", "\n", "&nbsp;", " "), "", $content);
        $content = $this->autowrap($name_size, 0, $font_path, $content, $name_width, 2);
        $this->reText($editor, $goods_qrcode, $content, $name_size, 40, 660, new Color('#353535'), $font_path, 0);
        //
        $bs = '打开小程序阅读全文';
        $this->reText($editor, $goods_qrcode, $bs, $name_size, 230, 768, new Color('#f87c21'), $font_path, 0);

        //调整小程序码图片
        $editor->resizeFit($goods_down, 24, 40);
        //附加小程序码图片
        $editor->blend($goods_qrcode, $goods_down, 'normal', 1.0, 'top-center', 0, 810);

        //加商城名称
        $this->reText($editor, $goods_qrcode, $store->name, 20, 40, 1170, new Color('#888888'), $font_path, 0);
        //调整小程序码图片
        $editor->resizeFit($wxapp_qrcode, 240, 240);
        //附加小程序码图片
        $editor->blend($goods_qrcode, $wxapp_qrcode, 'normal', 1.0, 'top-left', 470, 1040);

        //保存图片
        $editor->save($goods_qrcode, $goods_pic_save_path . $goods_pic_save_name, 'jpeg', 85);

        //删除临时图片
        unlink($goods_pic_path);
        unlink($wxapp_qrcode_file_path);

        return [
            'code' => 0,
            'data' => [
                'goods_name' => $goods->title,
                'pic_url' => $pic_url . '?v=' . time(),
            ],
        ];
    }

    //抽奖海报
    public function lottery_qrcode()
    {
        $lottery = LotteryGoods::find()->where(['id' => $this->goods_id])->with('goods')->one();
        $goods = $lottery->goods;

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
        $version = hj_core_version();
        $goods_pic_save_name = md5("v={$version}&goods_id={$goods->id}&goods_name={$goods->name}&store_name={$store->name}&user_id={$this->user_id}&goods_pic_url={$goods_pic_url}&type=5") . '.jpg';

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
        if (\Yii::$app->fromAlipayApp()) {
            $scene = "gid={$this->goods_id}&uid={$this->user_id}";
        } else {
            $scene = "gid:{$this->goods_id},uid:{$this->user_id}";
        }
        $page = "lottery/goods/goods";
        $wxapp_qrcode_file_res = $this->getQrcode($scene, 240, $page);
        if ($wxapp_qrcode_file_res['code'] == 1) {
            unlink($goods_pic_path);
            return [
                'code' => 1,
                'msg' => '获取商品海报失败：获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
            ];
        }
        $wxapp_qrcode_file_path = $wxapp_qrcode_file_res['file_path'];
        $editor->open($wxapp_qrcode, $wxapp_qrcode_file_path);

        $lottery_qrcode = \Yii::$app->basePath . '/web/statics/images/lottery_qrcode.png';
        $lottery_free = \Yii::$app->basePath . '/web/statics/images/lottery_free.png';
        $lottery_line = \Yii::$app->basePath . '/web/statics/images/lottery_line.png';
        $editor->open($lottery_line, $lottery_line);
        $editor->open($lottery_qrcode, $lottery_qrcode);
        $editor->open($lottery_free, $lottery_free);

        //裁剪商品图片
        $editor->resizeFill($goods_pic, 690, 690);
        //附加商品图片
        $editor->blend($goods_qrcode, $goods_pic, 'normal', 1.0, 'top-left', 30, 126);


        if ($this->user) {
            $user = $this->user;
            // 用户头像
            $user_pic_path = $this->saveTempImage($user->avatar_url);
            if (!$user_pic_path) {
                return [
                    'code' => 1,
                    'msg' => '获取商品海报失败：用户头像丢失',
                ];
            }

            list($w, $h) = getimagesize($user_pic_path);
            $user_pic_path = $this->test($user_pic_path, $goods_pic_save_path, $w, $h);
            $editor->open($user_pic, $user_pic_path);
            //调整用户头像图片
            $editor->resizeExactWidth($user_pic, 68);
            //附加用户头像图片
            $editor->blend($goods_qrcode, $user_pic, 'normal', 1.0, 'top-left', 30, 30);

            // 用户名处理
            $username = $this->setName($user->nickname);
            $this->reText($editor, $goods_qrcode, $username, 20, 128, 56, new Color('#5b85cf'), $font_path, 0);
            $namewitch = imagettfbbox(20, 0, $font_path, $username);
            $this->reText($editor, $goods_qrcode, '分享给你一个商品', 20, (132 + $namewitch[2]), 56, new Color('#353535'), $font_path, 0);
            unlink($user_pic_path);
        } else {
            $this->reText($editor, $goods_qrcode, '分享给你一个商品', 20, 30, 56, new Color('#353535'), $font_path, 0);
        }


        $name_size = 25;
        $name_width = 670;

        //商品名称处理换行
        $name = $this->autowrap($name_size, 0, $font_path, $goods->name, $name_width, 2);
        //加商品名称
        $this->reText($editor, $goods_qrcode, $name, $name_size, 30, 844, new Color('#353535'), $font_path, 0);

        $attr = json_decode($lottery->attr, true);
        $attr_id_list = array_reduce($attr, create_function('$v,$w', '$v[]=$w["attr_id"];return $v;'));
        $original_price = '￥' . $goods->getAttrInfo($attr_id_list)['price'];
        $len = 60 + (strlen($original_price) - 3) * 12.5;

        // 商品价格
        $this->reText($editor, $goods_qrcode, $original_price, $name_size, 182, 965, new Color('#999999'), $font_path, 0);

        //调整免费标识图片
        $editor->resizeExact($lottery_line, $len, 2);
        //附加免费标识图片
        $editor->blend($goods_qrcode, $lottery_line, 'normal', 1.0, 'top-left', 180, 977);

        //调整免费标识图片
        $editor->resizeExact($lottery_free, 120, 56);
        //附加免费标识图片
        $editor->blend($goods_qrcode, $lottery_free, 'normal', 1.0, 'top-left', 30, 950);

        //调整标识图片
        $editor->resizeExact($lottery_qrcode, 690, 174);
        //附加标识图片
        $editor->blend($goods_qrcode, $lottery_qrcode, 'normal', 1.0, 'top-center', 0, 642);

        //调整小程序码图片
        $editor->resizeFit($wxapp_qrcode, 160, 160);
        //附加小程序码图片
        $editor->blend($goods_qrcode, $wxapp_qrcode, 'normal', 1.0, 'top-left', 536, 948);


        //保存图片
        $editor->save($goods_qrcode, $goods_pic_save_path . $goods_pic_save_name, 'jpeg', 85);

        //删除临时图片
        unlink($goods_pic_path);
        unlink($wxapp_qrcode_file_path);

        return [
            'code' => 0,
            'data' => [
                'goods_name' => $goods->name,
                'pic_url' => $pic_url . '?v=' . time(),
            ],
        ];
    }

    public function step_qrcode()
    {
        if (!$this->goods_id) {
            return [
                'code' => 1,
                'msg' => '未知模块'
            ];
        }

        $pic = Pic::find()->select('id,pic_url')->where(['store_id' => $this->store_id, 'is_delete' => 0, 'type' => 1,'id' => $this->goods_id])->one();
        if (!$pic) {
            return [
                'code' => 1,
                'msg' => '背景不存在',
            ];
        }
        $store = Store::findOne($this->store_id);

        $goods_pic_url = $pic->pic_url;
        $goods_pic_save_path = \Yii::$app->basePath . '/web/temp/';
        $version = hj_core_version();
        $goods_pic_save_name = md5("v={$version}&num={$this->num}&goods_id={$this->goods_id}&store_name={$store->name}&user_id={$this->user_id}&goods_pic_url={$goods_pic_url}&type=0") . '.jpg';

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
                'msg' => '获取海报失败：商品图片丢失',
            ];
        }

        $goods_qrcode_dst = \Yii::$app->basePath . '/web/statics/images/step-qrcode-bj.jpg';
        $font_path = \Yii::$app->basePath . '/web/statics/font/st-heiti-light.ttc';

        $editor = Grafika::createEditor(GrafikaHelper::getSupportEditorLib());
        $editor->open($goods_qrcode, $goods_qrcode_dst);
        $editor->open($goods_pic, $goods_pic_path);

        //获取小程序码图片
        if (\Yii::$app->fromAlipayApp()) {
            $scene = "gid={$this->goods_id}&uid={$this->user_id}";
        } else {
            $scene = "gid:{$this->goods_id},uid:{$this->user_id}";
        }
        $wxapp_qrcode_file_res = $this->getQrcode($scene, 240, "step/index/index");
        if ($wxapp_qrcode_file_res['code'] == 1) {
            unlink($goods_pic_path);
            return [
                'code' => 1,
                'msg' => '获取海报失败：获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
            ];
        }
        $wxapp_qrcode_file_path = $wxapp_qrcode_file_res['file_path'];
        $editor->open($wxapp_qrcode, $wxapp_qrcode_file_path);


        if ($this->user) {
            $user = $this->user;
            // 用户头像
            $user_pic_path = $this->saveTempImage($user->avatar_url);
            if (!$user_pic_path) {
                return [
                    'code' => 1,
                    'msg' => '获取海报失败：用户头像丢失',
                ];
            }

            list($w, $h) = getimagesize($user_pic_path);
            $user_pic_path = $this->test($user_pic_path, $goods_pic_save_path, $w, $h);
            $editor->open($user_pic, $user_pic_path);
            //调整用户头像图片
            $editor->resizeExactWidth($user_pic, 68);
            //附加用户头像图片
            $editor->blend($goods_qrcode, $user_pic, 'normal', 1.0, 'top-left', 298, 950);

            // 用户名处理
            $username = $this->setName($user->nickname);
            $namewitch = imagettfbbox(20, 0, $font_path, $username);
            $this->reText($editor, $goods_qrcode, $username, 20, 398, 960, new Color('#5b85cf'), $font_path, 0);
           
            $text = '已走了' . $this->num . '步';
            $textwitch = imagettfbbox(20, 0, $font_path, $text);

            $this->reText($editor, $goods_qrcode, $text, 20, 398, 995, new Color('#353535'), $font_path, 0);
            unlink($user_pic_path);
        }

        //裁剪图片
        $editor->resizeFill($goods_pic, 750, 900);
        //附加背景图片
        $editor->blend($goods_qrcode, $goods_pic, 'normal', 1.0, 'top-center', 0, 0);
        
        $setting = StepSetting::find()->where(['store_id' => $this->store_id])->one();
        $qrcode_title = $setting->qrcode_title ? $setting->qrcode_title : '走路还能赚钱';

        $this->reText($editor, $goods_qrcode, $qrcode_title, 25, 298, 1040, new Color('#353535'), $font_path, 0);
        $this->reText($editor, $goods_qrcode, '长按识别小程序码', 20, 298, 1093, new Color('#999999'), $font_path, 0);

        //调整小程序码图片
        $editor->resizeFit($wxapp_qrcode, 160, 160);
        // 附加小程序码图片
        $editor->blend($goods_qrcode, $wxapp_qrcode, 'normal', 1.0, 'top-left', 45, 950);

        //保存图片
        $editor->save($goods_qrcode, $goods_pic_save_path . $goods_pic_save_name, 'jpeg', 85);

        //删除临时图片
        unlink($goods_pic_path);
        unlink($wxapp_qrcode_file_path);
        
        $pic_list = Pic::find()->select('id,pic_url')->where(['store_id' => $this->store_id, 'is_delete' => 0, 'type' => 1])->all();
        return [
            'code' => 0,
            'data' => [
                'goods_name' => $this->goods_id,
                'pic_url' => $pic_url . '?v=' . time()
            ],
        ];
    }

    //获取网络图片到临时目录
    private function saveTempImage($url)
    {
        if (!is_dir(\Yii::$app->runtimePath . '/image')) {
            mkdir(\Yii::$app->runtimePath . '/image');
        }
        $save_path = \Yii::$app->runtimePath . '/image/' . md5($url) . '.jpg';
        CurlHelper::download($url, $save_path);
        return $save_path;
    }

    //生成圆角图片
    public function test($url, $path = './', $w, $h, $is_true = 'true')
    {
//        $w = 110; $h=110; // original size
        $original_path = $url;
        $dest_path = $path . uniqid('r', true) . '.png';
        $src = imagecreatefromstring(file_get_contents($original_path));
        if ($is_true == 'true') {
            $newpic = imagecreatetruecolor($w, $h);
            imagealphablending($newpic, false);
            $transparent = imagecolorallocatealpha($newpic, 0, 0, 0, 127);
            $r = $w / 2;
            for ($x = 0; $x < $w; $x++) {
                for ($y = 0; $y < $h; $y++) {
                    $c = imagecolorat($src, $x, $y);
                    $_x = $x - $w / 2;
                    $_y = $y - $h / 2;
                    if ((($_x * $_x) + ($_y * $_y)) < ($r * $r)) {
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
        for ($i = 0; $i < mb_strlen($string, 'UTF-8'); $i++) {
            $letter[] = mb_substr($string, $i, 1, 'UTF-8');
        }
        $line_count = 0;
        foreach ($letter as $l) {
            $teststr = $content . " " . $l;
            $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
            // 判断拼接后的字符串是否超过预设的宽度
            if (($testbox[2] > $width) && ($content !== "")) {
                $line_count++;
                if ($max_line && $line_count >= $max_line) {
                    $content = mb_substr($content, 0, -1, 'UTF-8') . "...";
                    break;
                }
                $content .= "\n";
            }
            $content .= $l;
        }
        return $content;
    }

    /**
     * @param integer $fontsize 字体大小
     * @param integer $angle 角度
     * @param string $fontface 字体名称
     * @param string $string 字符串
     * @param integer $width 预设宽度
     */
    public function setName($text)
    {
        if (mb_strlen($text, 'UTF-8') > 8) {
            $text = mb_substr($text, 0, 8, 'UTF-8') . '...';
        }
        return $text;
    }

    private function getQrcode($scene, $width = 240, $page = null)
    {
        return GenerateShareQrcode::getQrcode($this->store_id, $scene, $width, $page);
    }

    /**
     * @param $editor EditorInterface
     * @param $qrcode
     * @param $text
     * @param int $size
     * @param int $x
     * @param int $y
     * @param null $color
     * @param string $font
     * @param int $angle
     */
    private function reText(&$editor, $qrcode, $text, $size = 12, $x = 0, $y = 0, $color = null, $font = '', $angle = 0)
    {
        $search = ['“', '”', '：', '’', '‘', '©', '®', '™'];
//        $text = str_replace($search, '', $text);
        $str = '';
        for ($i = 0; $i < mb_strlen($text, 'UTF-8'); $i++) {
            $item = mb_substr($text, $i, 1, 'UTF-8');
            if (!in_array($item, $search)) {
                $str .= mb_convert_encoding($item,"html-entities","utf-8");
            } else {
                $str .= $item;
            }
        }
        $editor->text($qrcode, $str, $size, $x, $y, $color, $font, $angle);
    }
}
