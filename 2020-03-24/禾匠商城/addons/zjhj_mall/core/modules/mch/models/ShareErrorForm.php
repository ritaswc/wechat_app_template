<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/1/21
 * Time: 16:40
 */

namespace app\modules\mch\models;


use app\models\ActionLog;
use app\models\Order;
use app\models\User;
use app\models\UserLog;
use yii\data\Pagination;

class ShareErrorForm extends MchModel
{
    public $page;

    public $group;
    public $token;

    public function rules()
    {
        return [
            [['group', 'token'], 'safe']
        ];
    }

    public function search()
    {
        $userQuery = UserLog::find()->alias('ul')->select('ul.after_change')
            ->where('ul.user_id=o.user_id and ul.addtime <= o.addtime')
            ->andWhere(['ul.type' => 'parent_id'])->orderBy(['ul.id' => SORT_DESC])->limit(1);

        $query = Order::find()->alias('o')->where(['o.version' => '3.1.22'])
            ->andWhere(['!=', 'o.parent_id', $userQuery])->andWhere(['!=', 'parent_id', 0])->with('user')
            ->select(['o.*', 'user_log_parent_id' => $userQuery]);

        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count]);

        $list = $query->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();

        foreach ($list as &$item) {
            $item['user_log_parent'] = User::find()->where(['id' => $item['user_log_parent_id']])->asArray()->one();
            $item['order_parent'] = User::find()->where(['id' => $item['parent_id']])->asArray()->one();
        }

        return [
            'list' => $list,
            'pagination' => $pagination
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        if (!is_array($this->group)) {
            return [
                'code' => 1,
                'msg' => '数据提交错误'
            ];
        }
        $userQueryMoney = User::find()->alias('u')->select('u.price')
            ->where('u.id = o.parent_id');

        $userQuery = UserLog::find()->alias('ul')->select('ul.after_change')
            ->where('ul.user_id=o.user_id and ul.addtime <= o.addtime')
            ->andWhere(['ul.type' => 'parent_id'])->orderBy(['ul.id' => SORT_DESC])->limit(1);

        $list = Order::find()->alias('o')->where(['o.version' => '3.1.22', 'o.id' => $this->group])
            ->andWhere(['!=', 'o.parent_id', $userQuery])
            ->andWhere(['<=', 'o.first_price', $userQueryMoney])->andWhere(['!=', 'parent_id', 0])->with('user')
            ->select(['o.*', 'user_log_parent_id' => $userQuery])->all();

        $count = 0;
        /* @var Order[] $list */
        foreach ($list as $item) {
            $clone = clone $item;
            /* @var User $user */
            $parent = User::findOne(['id' => $clone->parent_id]);
            $user = clone $parent;
            $t = \Yii::$app->db->beginTransaction();
            try {
                $item->parent_id = 0;
                if ($item->save()) {
                    $result = "订单ID：{$item->id},修改前parent_id：{$clone->parent_id},修改后parent_id：{$item->parent_id}";
                    if ($item->is_price == 1 && $item->user->money >= $item->first_price) {
                        $parent->price -= $item->first_price;
                        $parent->total_price -= $item->first_price;
                        if ($parent->save()) {
                            $result .= "用户ID：{$parent->id},修改前price：{$user->price},修改后price：{$parent->price},修改前total_price：{$user->total_price},修改后total_price：{$parent->total_price}";
                        } else {
                            throw new \Exception('用户信息存储错误');
                        }
                    }
                    $log = new ActionLog();
                    $log->title = '分销错误订单处理';
                    $log->result = $result;
                    $log->addtime = time();
                    $log->admin_name = $this->token;
                    $log->admin_id = 0;
                    $log->admin_ip = \Yii::$app->request->userIP;
                    $log->route = \Yii::$app->controller->route;
                    $log->action_type = '分销错误订单处理';
                    $log->obj_id = 0;
                    $log->store_id = \Yii::$app->store ? \Yii::$app->store->id : 0;
                    $log->is_delete = 0;
                    $log->type = 0;
                    if ($log->save()) {
                        $t->commit();
                    } else {
                        throw new \Exception('数据保存出错');
                    }
                    $count++;
                } else {
                    throw new \Exception('数据保存出错');
                }
            } catch (\Exception $e) {
                $t->rollBack();
                $item->parent_id = $clone->parent_id;
                $item->save();
                $parent->price = $user->price;
                $parent->total_price = $user->total_price;
                $parent->save();
            }
        }

        return [
            'code' => 0,
            'msg' => "总共操作" . count($list) . "条数据，成功{$count}条数据"
        ];
    }
}