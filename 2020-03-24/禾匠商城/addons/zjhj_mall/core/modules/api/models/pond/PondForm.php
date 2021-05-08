<?php

namespace app\modules\api\models\pond;

use app\models\Pond;
use app\modules\api\models\ApiModel;
use app\models\Coupon;
use app\models\PondSetting;
use app\models\PondLog;
use app\models\GoodsCat;
use app\modules\api\controllers\pond\PondController;
use app\modules\api\models\StoreFrom;
use app\models\User;
use app\utils\GenerateShareQrcode;
use app\utils\GrafikaHelper;
use app\models\Register;
use Curl\Curl;
use Grafika\Color;
use Grafika\Grafika;

class PondForm extends ApiModel
{
    public $user_id;
    public $store_id;

    public function index()
    {
        //todo 上版本之间兼任
        $store = new StoreFrom();
        $images = $store->search()['pond']['pond'];
        $images['pond-head'] = $images['pond_head'];
        $images['pond-success'] = $images['pond_success'];
        $images['pond-empty'] = $images['pond_empty'];
        ////
        $list = Pond::find()
            ->where([
                'store_id' => $this->store_id,
            ])->with(['gift' => function ($query) {
                $query->where([
                    'store_id' => $this->store_id,
                    'is_delete' => 0
                ]);
            }])->with(['coupon' => function ($query) {
                $query->where([
                    'store_id' => $this->store_id,
                    'is_delete' => 0
                ]);
            }])
            ->orderBy('orderby ASC,id ASC')
            ->asArray()->all();

        foreach ($list as &$v) {
            switch ($v['type']) {
                case 1:
                    $v['name'] = $v['name'] ? $v['name'] : $v['price'].'元余额';
                    break;
                case 2:
                    $v['name'] = $v['coupon']['name'];
                    break;
                case 3:
                    $v['name'] = $v['name'] ? $v['name'] : $v['num'].'积分';
                    if (!$v['image_url']) {
                        $v['image_url'] = '/pond/images/pond-jf.png';
                    }
                    break;
                case 4:
                    $v['name'] = $v['gift']['name'];
                    break;
                case 5:
                    $v['name'] ='谢谢参与';
                    if (!$v['image_url']) {
                        $v['image_url'] = '/pond/images/pond-xx.png';
                    }
                    break;
                default:
            }
        };
        unset($v);

        $setting = PondSetting::findOne(['store_id' => $this->store_id]);
        $oppty = $setting['oppty'];

        if ($setting['start_time'] > time() || $setting['end_time'] < time()) {
            return [
                'code' => 1,
                'data' => [
                    'list' => $list,
                    'oppty' => $oppty,
                    'register' => $setting['deplete_register'],
                    'time' => false,
                    'images' => $images,
                    'integral' => true,
                ]
            ];
        }
        if ($setting->type == 1) {
            $start_time = strtotime(date('Y-m-d 00:00:00', time()));
            $end_time = $start_time + 86400;
        } elseif ($setting->type == 2) {
            $start_time = $setting['start_time'];
            $end_time = $setting['end_time'];
        }
        $log = PondLog::find()
            ->where(['store_id' => $this->store_id, 'user_id' => $this->user_id])
            ->andWhere(['>', 'create_time', $start_time])
            ->andWhere(['<', 'create_time', $end_time])
            ->count();

        if ($setting['deplete_register'] > 0) {
            $user = User::findOne(['id' => $this->user_id, 'store_id' => $this->store_id]);
            if ($user->integral < $setting['deplete_register']) {
                return [
                    'code' => 1,
                    'data' => [
                        'list' => $list,
                        'oppty' => $setting['oppty'] - $log,
                        'time' => true,
                        'register' => $setting['deplete_register'],
                        'integral' => false,
                    ],

                ];
            }
        }
        
        if ($log >= $setting['oppty']) {
            return [
                'code' => 1,
                'data' => [
                    'list' => $list,
                    'oppty' => 0,
                    'images' => $images,
                    'time' => true,
                    'register' => $setting['deplete_register'],
                    'integral' => true,
                ]
            ];
        } else {
            return [
                'code' => 0,
                'data' => [
                    'list' => $list,
                    'oppty' => $setting['oppty'] - $log,
                    'images' => $images,
                    'time' => true,
                    'register' => $setting['deplete_register'],
                    'integral' => true,
                ]
            ];
        }
    }

    protected function get_rand($probability)
    {
        // 概率数组的总概率精度
        $max = array_sum($probability);
        foreach ($probability as $key => $val) {
            //$rand_number = mt_rand(1, $max);//从1到max中随机一个值
            $rand_number = $this->random_num(1, $max);

            if ($rand_number <= $val) {//如果这个值小于等于当前中奖项的概率，我们就认为已经中奖
                return $key;
            } else {
                $max -= $val;//否则max减去当前中奖项的概率，然后继续参与运算
            }
        }
    }

    public function lottery()
    {
        $list = PondSetting::findOne(['store_id' => $this->store_id]);

        if ($list['start_time'] > time() || $list['end_time'] < time()) {
            return [
                'code' => 1,
                'msg' => '活动已结束或未开启'
            ];
        }

        if ($list->type == 1) {
            $start_time = strtotime(date('Y-m-d 00:00:00', time()));
            $end_time = $start_time + 86400;
        } elseif ($list->type == 2) {
            $start_time = $list['start_time'];
            $end_time = $list['end_time'];
        } else {
            return [
                'code' => 1,
                'msg' => '参数错误'
            ];
        }
        $log = PondLog::find()
            ->where(['store_id' => $this->store_id, 'user_id' => $this->user_id])
            ->andWhere(['>', 'create_time', $start_time])
            ->andWhere(['<', 'create_time', $end_time])
            ->count();
        if ($log >= $list['oppty']) {
            return [
                'code' => 1,
                'msg' => '机会已用完'
            ];
        }

        if ($list->deplete_register > 0) {
            $user = User::findOne(['id' => $this->user_id, 'store_id' => $this->store_id]);
            if ($user->integral < $list->deplete_register) {
                return [
                    'code' => 1,
                    'msg' => '积分不足',
                ];
            }
        }

        $pond = Pond::find()->where(['store_id' => $this->store_id])->all();

        $succ = array();
        $err = array();
        foreach ($pond as $k => $v) {
            if ($v->type != 5) {
                if ($v->stock > 0) {
                    $succ[$v->id] = $v->stock;
                }
            } else {
                $err[$v->id] = $v->id;
            }
        }


        // $rand = mt_rand(0,10000);
        $rand = $this->random_num(1, 10000);

        $max = array_sum($succ);

        if (empty($err)) {
            if ($max > 0) {
                $id = $this->get_rand($succ);
            } else {
                return [
                    'code' => 1,
                    'msg' => '网络异常'
                ];
            }
        } else {
            if ($rand < $list['probability'] && $max > 0) {
                $id = $this->get_rand($succ);
            } else {
                $id = array_rand($err, 1);
            }
        }

        $form = Pond::findOne([
            'store_id' => $this->store_id,
            'id' => $id
        ]);

        $pondLog = new PondLog;
        $pondLog->store_id = $this->store_id;
        $pondLog->user_id = $this->user_id;
        $pondLog->type = $form->type;
        $pondLog->num = $form->num;
        if ($form->type == 1) {
            $pondLog->num = 0;
            $pondLog->price = floatval($form->price);
        }
        $pondLog->status = 0;
        $pondLog->pond_id = $id;
        $pondLog->coupon_id = $form->coupon_id;
        if ($form->type == 4) {
            $pondLog->attr = $form->attr;
        }
        $pondLog->gift_id = $form->gift_id;
        $pondLog->create_time = time();

        $t = \Yii::$app->db->beginTransaction();
        if ($form->type != 5) {
            $sql = 'select * from ' . Pond::tableName() . ' where store_id = ' . $this->store_id . ' and id = ' . $id . ' for update';
            $pond = \Yii::$app->db->createCommand($sql)->queryOne();

            //判断库存是否大于0
            if ($pond['stock'] > 0) {
                //将库存数量减1
                $form->stock = $pond['stock'] - 1;
                if (!$form->save()) {
                    $t->rollBack();
                    return [
                        'code' => 1,
                        'msg' => '网络异常'
                    ];
                }
            } else {
                $pondLog->type = 5;
                if (empty($err)) {
                    return [
                        'code' => 1,
                        'msg' => '网络异常'
                    ];
                } else {
                    $id = array_rand($err, 1);
                };

                $pondLog->pond_id = $id;
                $pondLog->coupon_id = 0;
                $pondLog->attr = '';
                $pondLog->num = 0;
                $pondLog->gift_id = 0;
                $pondLog->price = 0;
            }
        }
        $msg = '成功';
        if ($list->deplete_register > 0) {
            $num = (int)$list->deplete_register;

            $user = User::findOne(['id' => $this->user_id, 'store_id' => $this->store_id]);
            $register = new Register();
            $register->store_id = $this->store_id;
            $register->user_id = $this->user_id;
            $register->register_time = '..';
            $register->addtime = time();
            $register->continuation = 0;
            $register->type = 17;
            $register->integral = $num * -1;
            $register->save();

            $user->integral -= $num;
            if ($user->integral < $num) {
                $msg = '积分不足';
            }
            if ($user->integral < 0) {
                return [
                    'code' => 1,
                    'msg' => '积分不足'
                ];
            }
            if (!$register->save()) {
                $t->rollBack();
                return $this->getErrorResponse($register);
            }
            if (!$user->save()) {
                $t->rollBack();
                return $this->getErrorResponse($user);
            }
        }

        if ($pondLog->save()) {
            $t->commit();
            $array = [
                'oppty' => $list['oppty'] - $log - 1,
                'id' => $id,
                'p_id' => $pondLog->id,
            ];
            return [
                'code' => 0,
                'msg' => $msg,
                'data' => (object)$array
            ];
        } else {
            return $this->getErrorResponse($pondLog);
        }
    }

    public function setting()
    {
        $list = PondSetting::findOne(['store_id' => $this->store_id]);
        $list->end_time = date('Y.m.d H', $list->end_time);
        $list->start_time = date('Y.m.d H', $list->start_time);
        if ($list) {
            return [
                'code' => 0,
                'msg' => '成功',
                'data' => $list
            ];
        }
    }

    protected function random_num($min, $max)
    {
        return mt_rand() % ($max - $min + 1) - $min;
    }

    public function qrcode()
    {
        $goods_qrcode_dst = \Yii::$app->basePath . '/web/statics/images/pond_qrcode.png';
        $font_path = \Yii::$app->basePath . '/web/statics/font/st-heiti-light.ttc';
        $editor = Grafika::createEditor(GrafikaHelper::getSupportEditorLib());
        $editor->open($goods_qrcode, $goods_qrcode_dst);

        //获取小程序码图片
        $user_id =  \Yii::$app->user->id;
        if (\Yii::$app->fromAlipayApp()) {
            $scene = "user_id={$user_id}";
        } else {
            $scene = "{$user_id}";
        }
        $wxapp_qrcode_file_res = $this->getQrcode($scene, 240, "pond/pond/pond");
        if ($wxapp_qrcode_file_res['code'] == 1) {
            return [
                'code' => 1,
                'msg' => '获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
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
                'msg' => '获取海报失败：用户头像丢失',
            ];
        }

        $goods_pic_save_path = \Yii::$app->basePath . '/web/temp/';
        $goods_pic_save_name = md5("v=1.6.2&user_id={$user_id}") . '.jpg';

        list($w, $h) = getimagesize($user_pic_path);
        $user_pic_path = $this->avatar($user_pic_path, $goods_pic_save_path, $w, $h);
        $editor->open($user_pic, $user_pic_path);


        //附加用户头像
        $editor->resizeExactWidth($user_pic, 80);
        $editor->blend($goods_qrcode, $user_pic, 'normal', 1.0, 'top-left', 160, 480);

        //加上文字
        $username = $this->setName($user->nickname);
        $editor->text($goods_qrcode, $username, 25, 272, 490, new Color('#ffffff'), $font_path, 0);
        $editor->text($goods_qrcode, '邀请你一起抽大奖', 25, 272, 530, new Color('#ffffff'), $font_path, 0);

        //加下文字
        $editor->text($goods_qrcode, '扫描二维码', 30, 270, 1064, new Color('#ffffff'), $font_path, 0);
        $editor->text($goods_qrcode, '和我一起抽奖！', 30, 240, 1114, new Color('#ffffff'), $font_path, 0);
        //附加小程序码图片
        $editor->resizeFit($wxapp_qrcode, 400, 400);
        $editor->blend($goods_qrcode, $wxapp_qrcode, 'normal', 1.0, 'top-center', 0, 616);

        //保存图片
        $editor->save($goods_qrcode, $goods_pic_save_path . $goods_pic_save_name, 'jpeg', 85);

        $pic_url = str_replace('http://', 'https://', \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $goods_pic_save_name);

        //删除临时图片
        unlink($wxapp_qrcode_file_path);

        return [
            'code' => 0,
            'data' => [
                'pic_url' => $pic_url . '?v=' . time(),
            ],
        ];
    }

    private function setName($text)
    {
        if (mb_strlen($text, 'UTF-8') > 8) {
            $text = mb_substr($text, 0, 8, 'UTF-8') . '...';
        }
        return $text;
    }
        //获取网络图片到临时目录
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

    //第一步生成圆角图片
    public function avatar($url, $path = './', $w, $h)
    {
        $original_path = $url;
        $dest_path = $path . uniqid() . '.png';
        $src = imagecreatefromstring(file_get_contents($original_path));
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
        imagepng($newpic, $dest_path);
        imagedestroy($newpic);
        imagedestroy($src);
        unlink($url);
        return $dest_path;
    }

    private function getQrcode($scene, $width = 430, $page = null)
    {
        return GenerateShareQrcode::getQrcode($this->store_id, $scene, $width, $page);
    }

    //保存图片内容到临时文件
    private function saveTempImageByContent($content)
    {
        if (!is_dir(\Yii::$app->runtimePath . '/image')) {
            mkdir(\Yii::$app->runtimePath . '/image');
        }
        $save_path = \Yii::$app->runtimePath . '/image/' . md5(base64_encode($content)) . '.jpg';
        $fp = fopen($save_path, 'w');
        fwrite($fp, $content);
        fclose($fp);
        return $save_path;
    }
}
