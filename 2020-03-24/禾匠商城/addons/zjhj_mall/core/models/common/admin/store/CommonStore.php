<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\models\common\admin\store;

use app\models\Goods;
use app\models\Model;
use app\models\Order;
use app\models\Store;
use app\models\User;
use app\models\We7Db;
use yii\data\Pagination;
use yii\db\Query;

use app\models\PtOrder;
use app\models\MsOrder;
use app\models\BargainOrder;
use app\models\YyOrder;
use app\models\IntegralOrder;
use app\models\PtGoods;
use app\models\YyGoods;
use app\models\MsGoods;
use app\models\IntegralGoods;

class CommonStore extends Model
{
    public $page;
    public $limit;
    public $keyword;
    public $is_ind;

    public function rules()
    {
        return [
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 20],
            [['keyword'], 'trim']
        ];
    }

    public function storeList()
    {
        $query = new Query();
        $query->select('s.*')->from(['s' => Store::tableName()]);
        if (!$this->is_ind) {
            $query->innerJoin(['w' => We7Db::getTableName('account_wxapp')], 'w.uniacid=s.acid');
        } else {
            $query->andWhere(['>', 's.admin_id', 0]);
        }
        if ($this->keyword) {
            $query->andWhere(['like', 's.name', $this->keyword]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->all();

        return [
            'list' => $list,
            'pagination' => $pagination
        ];
    }

    public function storeInfo($mchId = 0)
    {
        $goodCount = Goods::find()
            ->where([
                'store_id' => $this->getCurrentStoreId(),
                'is_delete' => Model::IS_DELETE_FALSE,
                'mch_id' => $mchId,
                'type' => 0
            ])->count();

        $orderCount = Order::find()->where([
            'store_id' => $this->getCurrentStoreId(),
            'is_delete' => Model::IS_DELETE_FALSE,
            'is_cancel' => Order::IS_CANCEL_FALSE,
            'mch_id' => $mchId,
            'type' => 0
        ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]])->count();

        $userCount = User::find()->where([
            'store_id' => $this->getCurrentStoreId(),
            'is_delete' => Model::IS_DELETE_FALSE,
            'type' => User::USER_TYPE_MEMBER
        ])->count();

        return [
            'user_count' => $userCount ? intval($userCount) : 0,
            'goods_count' => $goodCount ? intval($goodCount) : 0,
            'order_count' => $orderCount ? intval($orderCount) : 0,
        ];
    }

    public function storeInfoPlug($name)
    {
        switch ($name){
            case 'pt':
                $form = PtOrder::find();
                $goods = PtGoods::find();
                break;
            case 'ms':
                $form = MsOrder::find();
                $goods = MsGoods::find();
                break;
            case 'yy':
                $form = YyOrder::find();
                $goods = YyGoods::find();
                break;     
            case 'kj':
                $form = Order::find();
                $goods = Goods::find();
                break;
            case 'jf':
                $form = IntegralOrder::find();
                $goods = IntegralGoods::find();
                break;
            case 'mch':
                $form = Order::find();
                $goods = Goods::find();
                break;
            default:
               return;
        }

        if($name=='kj'){
            $where = [
                'store_id' => $this->getCurrentStoreId(),
                'is_delete' => Model::IS_DELETE_FALSE,
                'type' => 2
            ];
        }else if ($name=='mch'){
            $where = ['and', ['store_id' => $this->getCurrentStoreId(), 'is_delete' => Model::IS_DELETE_FALSE],['<>', 'mch_id', 0]];

        } else {
            $where = [
                'store_id' => $this->getCurrentStoreId(),
                'is_delete' => Model::IS_DELETE_FALSE,
            ];
        }
        $goodCount = $goods->where($where)->count();

        if($name=='kj'){
            $where = [
            'store_id' => $this->getCurrentStoreId(),
            'is_delete' => Model::IS_DELETE_FALSE,
            'is_cancel' => Order::IS_CANCEL_FALSE,
            'type' => 2
            ];
        }else if($name == 'mch') {
            $where = ['and', ['store_id' => $this->getCurrentStoreId(), 'is_cancel' => Order::IS_CANCEL_FALSE, 'is_delete' => Model::IS_DELETE_FALSE],['<>', 'mch_id', 0]];
        }else {
            $where = [
                'store_id' => $this->getCurrentStoreId(),
                'is_delete' => Model::IS_DELETE_FALSE,
                'is_cancel' => Order::IS_CANCEL_FALSE,
            ];            
        }

        $orderCount = $form->where($where)->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]])->count();

        $userCount = User::find()->where([
            'store_id' => $this->getCurrentStoreId(),
            'is_delete' => Model::IS_DELETE_FALSE,
            'type' => User::USER_TYPE_MEMBER
        ])->count();

        return [
            'user_count' => $userCount ? intval($userCount) : 0,
            'goods_count' => $goodCount ? intval($goodCount) : 0,
            'order_count' => $orderCount ? intval($orderCount) : 0,
        ];
    }
}
