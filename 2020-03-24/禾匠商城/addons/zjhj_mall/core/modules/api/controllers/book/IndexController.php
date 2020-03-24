<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: 禾匠团队
 */

namespace app\modules\api\controllers\book;

use app\models\YyCat;
use app\models\YySetting;
use app\modules\api\models\book\CommentListForm;
use app\modules\api\models\book\GoodsAttrInfoForm;
use app\modules\api\models\book\ShopListForm;
use app\modules\api\models\book\YyGoodsForm;
use app\modules\api\models\ShareQrcodeForm;
use app\hejiang\ApiResponse;

/**
 * Class IndexController
 * @package app\modules\api\controller\group
 * 预约首页模块
 */
class IndexController extends Controller
{
    /**
     * @return mixed|string
     * 预约首页
     */
    public function actionIndex()
    {
        // 获取导航分类
        $cat = YyCat::find()
            ->select('id,name')->andWhere(['is_delete' => 0, 'store_id' => $this->store_id])
            ->orderBy('sort ASC')
            ->asArray()
            ->all();
        $yyGoods = new YyGoodsForm();
        $yyGoods->store_id = $this->store_id;
        $yyGoods->user_id = \Yii::$app->user->id;
        $goods = $yyGoods->getList();
        $catShow = YySetting::findOne(['store_id' => $this->store_id]);

        $data = array(
            'cat' => $cat,
            'goods' => $goods,
            'cat_show' => $catShow->cat,
        );
        return new ApiResponse(0, 'success', $data);
    }

    /**
     * @return mixed|string
     * 数据加载
     */
    public function actionGoodList()
    {
        $yyGoods = new YyGoodsForm();
        $yyGoods->store_id = $this->store_id;
        $yyGoods->user_id = \Yii::$app->user->id;
        $goods = $yyGoods->getList();
        return new ApiResponse(0, 'success', $goods);
    }

    /**
     * @param int $gid
     * @return mixed|string
     * 商品详情
     */
    public function actionGoodDetails($gid = 0)
    {
        $ptGoods = new YyGoodsForm();
        $ptGoods->store_id = $this->store_id;
        $ptGoods->gid = $gid;
        $ptGoods->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($ptGoods->getInfo());
    }

    /**
     * 门店列表
     */
    public function actionShopList()
    {
        $ids = \Yii::$app->request->get('ids');
        $form = new ShopListForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->ids = $ids;
        $form->attributes = \Yii::$app->request->get();
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    /**
     * 商品评价
     */
    public function actionGoodsComment()
    {
        $form = new CommentListForm();
        $form->attributes = \Yii::$app->request->get();
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    //获取商品二维码海报
    public function actionGoodsQrcode()
    {
        $form = new ShareQrcodeForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store_id;
        $form->type = 3;
        if (!\Yii::$app->user->isGuest) {
            $form->user = \Yii::$app->user->identity;
            $form->user_id = \Yii::$app->user->id;
        }
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    public function actionGoodsAttrInfo()
    {
        $form = new GoodsAttrInfoForm();
        $form->attributes = \Yii::$app->request->get();
        return $form->search();
    }
}
