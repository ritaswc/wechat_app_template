<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/17
 * Time: 16:53
 */

namespace app\modules\mch\controllers\miaosha;

use app\models\MsGoods;
use app\models\MsOrderComment;
use app\models\User;
use app\modules\mch\controllers\Controller;
use yii\data\Pagination;

class CommentController extends Controller
{
    public function actionIndex()
    {
        $query = MsOrderComment::find()->alias('oc')->where(['oc.store_id' => $this->store->id, 'oc.is_delete' => 0]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 20]);
        $list = $query
            ->leftJoin(['u' => User::tableName()], 'oc.user_id=u.id')
            ->leftJoin(['g' => MsGoods::tableName()], 'oc.goods_id=g.id')
            ->select('oc.id,u.nickname,u.avatar_url,u.platform,oc.score,oc.content,oc.pic_list,g.name goods_name,oc.is_hide')
            ->orderBy('oc.addtime DESC')->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();
        return $this->render('index', [
            'list' => $list,
            'pagination' => $pagination,
        ]);
    }

    public function actionHideStatus($id, $status)
    {
        $order_comment = MsOrderComment::findOne([
            'store_id' => $this->store->id,
            'id' => $id,
        ]);
        if ($order_comment) {
            $order_comment->is_hide = $status;
            $order_comment->save();
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    public function actionDeleteStatus($id, $status)
    {
        $order_comment = MsOrderComment::findOne([
            'store_id' => $this->store->id,
            'id' => $id,
        ]);
        if ($order_comment) {
            $order_comment->is_delete = $status;
            $order_comment->save();
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }
}
