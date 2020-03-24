<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/8
 * Time: 18:55
 */

namespace app\modules\mch\models;

use app\models\common\admin\order\CommonOrderSearch;
use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\User;
use app\models\WechatTplMsgSender;
use app\modules\mch\extensions\Export;
use yii\data\Pagination;

class OrderRefundListForm extends MchModel
{
    public $store_id;
    public $user_id;
    public $keyword;
    public $status;
    public $page;
    public $limit;
    public $date_start;
    public $date_end;
    public $keyword_1;
    public $fields;
    public $flag;
    public $platform;//所属平台

    public function rules()
    {
        return [
            [['keyword',], 'trim'],
            [['status', 'page', 'limit', 'user_id', 'keyword_1'], 'integer'],
            [['status',], 'default', 'value' => -1],
            [['page',], 'default', 'value' => 1],
            //[['limit',], 'default', 'value' => 20],
            [['flag', 'date_start', 'date_end'], 'trim'],
            [['flag'], 'default', 'value' => 'NO'],
            [['fields'],'safe']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = OrderRefund::find()->alias('or')
            ->leftJoin(['o' => Order::tableName()], 'o.id=or.order_id')
            ->leftJoin(['od' => OrderDetail::tableName()], 'od.id=or.order_detail_id')
            ->leftJoin(['g' => Goods::tableName()], 'g.id=od.goods_id')
            ->leftJoin(['u' => User::tableName()], 'u.id=or.user_id')
            ->where(['or.store_id' => $this->store_id, 'or.is_delete' => 0, 'o.mch_id' => 0,'o.is_show'=>1]);
        if ($this->status == 0) {
            $query->andWhere(['or.status' => 0]);
        }
        if ($this->status == 1) {
            $query->andWhere(['or.status' => [1, 2, 3]]);
        }

        //TODO 只优化了关键字搜索 持续优化中...
        $commonOrderSearch = new CommonOrderSearch();
        $query = $commonOrderSearch->search($query, $this);
        $query = $commonOrderSearch->keyword($query, $this->keyword_1, $this->keyword);


        $query1 = clone  $query;
        if ($this->flag == "EXPORT") {
            $list_ex = $query1->orderBy('or.addtime DESC')->select('or.id order_refund_id,o.id order_id,o.order_no,o.name,o.mobile,o.address,u.nickname,u.platform,u.id user_id,g.name goods_name,g.id goods_id,or.addtime,od.num,od.attr,od.total_price,or.type refund_type,or.status refund_status,or.desc refund_desc,or.pic_list refund_pic_list,or.refund_price,or.refuse_desc refund_refuse_desc,g.attr goods_attr')->asArray()->all();
            $f = new ExportList();
            $f->fields = $this->fields;
            $f->refundForm($list_ex);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('or.addtime DESC');
        $list = $query->select('or.id order_refund_id,o.id order_id,o.order_no,o.name,o.mobile,o.address,o.pay_type,u.nickname,u.id user_id,u.platform,g.name goods_name,g.id goods_id,or.addtime,od.num,od.attr,od.total_price,or.type refund_type,or.status refund_status,or.desc refund_desc,or.pic_list refund_pic_list,or.refund_price,or.refuse_desc refund_refuse_desc,or.is_agree,or.is_user_send,or.user_send_express,or.user_send_express_no')->asArray()->all();
        foreach ($list as $i => $item) {
            $list[$i]['goods_pic'] = Goods::getGoodsPicStatic($item['goods_id'])->pic_url;
        }
        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];
    }
}
