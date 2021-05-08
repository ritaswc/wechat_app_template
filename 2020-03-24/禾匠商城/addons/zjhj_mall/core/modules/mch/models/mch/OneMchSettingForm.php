<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/8/8
 * Time: 18:22
 */

namespace app\modules\mch\models\mch;


use app\models\MchPlugin;
use app\models\MchSetting;
use app\modules\mch\models\MchModel;

class OneMchSettingForm extends MchModel
{
    public $store;

    public $mch_id;

    public $is_share;

    public function rules()
    {
        return [
            [['is_share'],'integer'],
        ];
    }

    public function save()
    {
        if(!$this->validate()){
            return $this->getErrorResponse();
        }
        $mchSetting = MchPlugin::findOne(['store_id'=>$this->store->id,'mch_id'=>$this->mch_id]);
        if(!$mchSetting){
            $mchSetting = new MchPlugin();
            $mchSetting->store_id = $this->store->id;
            $mchSetting->mch_id = $this->mch_id;
        }
        $mchSetting->attributes = $this->attributes;
        if($mchSetting->save()){
            return [
                'code'=>0,
                'msg'=>'保存成功'
            ];
        }else{
            return $this->getErrorResponse($mchSetting);
        }

    }
}