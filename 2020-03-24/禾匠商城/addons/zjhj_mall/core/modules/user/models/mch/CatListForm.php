<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/15
 * Time: 17:23
 */

namespace app\modules\user\models\mch;

use app\models\MchCat;
use app\modules\user\models\UserModel;

class CatListForm extends UserModel
{
    public $mch_id;

    public function search()
    {
        $list = MchCat::find()->where(['mch_id' => $this->mch_id, 'parent_id' => 0, 'is_delete' => 0])
            ->orderBy('sort,addtime DESC')
            ->asArray()->all();
        foreach ($list as $i => $item) {
            $sub_list = MchCat::find()->where(['mch_id' => $this->mch_id, 'parent_id' => $item['id'], 'is_delete' => 0])
                ->orderBy('sort,addtime DESC')
                ->asArray()->all();
            $list[$i]['list'] = $sub_list;
        }
        return $list;
    }
}
