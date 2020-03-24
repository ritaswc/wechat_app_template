<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/2
 * Time: 0:11
 */

namespace app\modules\api\models;

use app\hejiang\ApiResponse;
use app\models\Cat;

class CatListForm extends ApiModel
{
    public $store_id;
    public $limit;

    public function rules()
    {
        return [
            [['store_id', 'limit'], 'integer'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Cat::find()->where([
            'is_delete' => 0,
            'parent_id' => 0,
            'is_show' => 1,
        ]);
        if ($this->store_id) {
            $query->andWhere(['store_id' => $this->store_id]);
        }
        if ($this->limit) {
            $query->limit($this->limit);
        }
        $query->orderBy('sort ASC');
        $list = $query->select('id,store_id,parent_id,name,pic_url,big_pic_url,advert_pic,advert_url')->asArray()->all();
        foreach ($list as $i => $item) {
            $sub_list = Cat::find()->where([
                'is_delete' => 0,
                'parent_id' => $item['id'],
                'is_show' => 1,
            ])->orderBy('sort ASC')
                ->select('id,store_id,parent_id,name,pic_url,big_pic_url')->asArray()->all();
            $list[$i]['list'] = $sub_list ? $sub_list : [];
        }
        $data = [
            'list'=>$list
        ];
        return new ApiResponse(0, 'success', $data);
    }
}
