<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2018/2/24
 * Time: 10:37
 */

namespace app\modules\mch\models\group;

use app\models\PtRobot;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class RobotForm extends MchModel
{
    public $store_id;
    public $limit;

    public $name;
    public $pic;

    public $robot;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'pic', 'store_id'], 'required'],
            [['store_id'], 'integer'],
            [['name', 'pic'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '机器人名',
            'pic' => '头像',
            'is_delete' => '是否删除',
            'addtime' => '添加时间',
            'store_id' => 'Store ID',
        ];
    }

    /**
     * @return array
     * 获取拼团机器人列表
     */
    public function getList()
    {
        $query = PtRobot::find()
            ->andWhere(['is_delete' => 0, 'store_id' => $this->store_id]);

//        $keyword = \Yii::$app->request->get('keyword');
//        if (trim($keyword)) {
//            $query->andWhere([
//                'OR',
//                ['LIKE', 'g.name', $keyword],
//                ['LIKE', 'c.name', $keyword],
//            ]);
//        }
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        $list = $query
            ->orderBy('addtime ASC')
            ->offset($p->offset)
            ->limit($p->limit)
            ->all();

        return [$list, $p];
    }

    /**
     * @return array
     * 拼团机器人编辑操作
     */
    public function save()
    {
        if ($this->validate()) {
            $robot = $this->robot;
            if ($robot->isNewRecord) {
                $robot->is_delete = 0;
                $robot->addtime = time();
            }
            $robot->attributes = $this->attributes;
            if ($robot->save()) {
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            } else {
                return $this->getErrorResponse($robot);
            }
        } else {
            return $this->errorResponse;
        }
    }
}
