<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 13:19
 */

namespace app\modules\mch\models;

/**
 * @property \app\models\Qrcode $qrcode;
 */
class QrcodeForm extends MchModel
{
    public $qrcode;
    public $store_id;

    public $qrcode_bg;//推广海报背景图
    public $avatar_w;//头像宽度
    public $avatar_x;//头像x坐标
    public $avatar_y;//头像y坐标
    public $qrcode_c;//二维码样式
    public $qrcode_w;//二维码宽度
    public $qrcode_x;//二维码x坐标
    public $qrcode_y;//二维码y坐标
    public $font_w;//字体大小
    public $font_c;//字体颜色
    public $font_x;//字体x坐标
    public $font_y;//字体y坐标

    public function rules()
    {
        return [
            [['avatar_w','avatar_x','avatar_y','qrcode_w','qrcode_x','qrcode_y','font_w','font_x','font_y'],'required'],
            [['avatar_w','avatar_x','avatar_y','qrcode_w','qrcode_x','qrcode_y','font_w','font_x','font_y'],'number','min'=>0],
            [['qrcode_bg'],'string'],
            [['font_c'],'default','value'=>0],
            [['qrcode_bg'],'default','value'=>\Yii::$app->request->baseUrl.'/images/2.png'],
            [['qrcode_c'],'in','range'=>[0,1]]
        ];
    }
    public function attributeLabels()
    {
        return [
            'qrcode_bg'=>'推广海报背景图',
            'avatar_w'=>'头像宽度',
            'avatar_x'=>'头像x坐标',
            'avatar_y'=>'头像y坐标',
            'qrcode_c'=>'二维码样式',
            'qrcode_w'=>'二维码宽度',
            'qrcode_x'=>'二维码x坐标',
            'qrcode_y'=>'二维码y坐标',
            'font_c'=>'字体颜色',
            'font_w'=>'字体大小',
            'font_x'=>'字体x坐标',
            'font_y'=>'字体y坐标',
        ];
    }
    public function save()
    {
        if ($this->validate()) {
            if ($this->qrcode->isNewRecord) {
                $this->qrcode->is_delete = 0;
                $this->qrcode->addtime = time();
            }
            $this->qrcode->store_id = $this->store_id;
            $this->qrcode->qrcode_bg = $this->qrcode_bg;
            $this->qrcode->preview = $this->qrcode_bg;
            $avatar_size = [
                'w'=>$this->avatar_w,
                'h'=>$this->avatar_w,
            ];
            $avatar_position = [
                'x'=>$this->avatar_x,
                'y'=>$this->avatar_y
            ];
            $qrcode_size = [
                'w'=>$this->qrcode_w,
                'h'=>$this->qrcode_w,
                'c'=>($this->qrcode_c === '0')?'false':'true'
            ];
            $qrcode_position = [
                'x'=>$this->qrcode_x,
                'y'=>$this->qrcode_y
            ];
            $font_position = [
                'x'=>$this->font_x,
                'y'=>$this->font_y
            ];
            $font = [
                'size'=>$this->font_w,
                'color'=>$this->font_c
            ];
            $this->qrcode->avatar_size = \Yii::$app->serializer->encode($avatar_size);
            $this->qrcode->avatar_position = \Yii::$app->serializer->encode($avatar_position);
            $this->qrcode->qrcode_size = \Yii::$app->serializer->encode($qrcode_size);
            $this->qrcode->qrcode_position = \Yii::$app->serializer->encode($qrcode_position);
            $this->qrcode->font_position = \Yii::$app->serializer->encode($font_position);
            $this->qrcode->font = \Yii::$app->serializer->encode($font);

            if ($this->qrcode->save()) {
                return [
                    'code'=>0,
                    'msg'=>'成功'
                ];
            } else {
                return [
                    'code'=>1,
                    'msg'=>'网络异常'
                ];
            }
        } else {
            return $this->errorResponse;
        }
    }
}
