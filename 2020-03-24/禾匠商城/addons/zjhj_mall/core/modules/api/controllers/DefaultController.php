<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 15:15
 */

namespace app\modules\api\controllers;

use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\models\alipay\MpConfig;
use app\models\AppNavbar;
use app\models\Article;
use app\models\common\CommonDistrict;
use app\models\common\CommonFormId;
use app\models\Option;
use app\models\Setting;
use app\models\StorePermission;
use app\models\task\order\OrderAutoCanCel;
use app\models\task\order\Test;
use app\models\UploadConfig;
use app\models\UploadForm;
use app\modules\api\models\CatListForm;
use app\modules\api\models\CommentListForm;
use app\modules\api\models\CouponListForm;
use app\modules\api\models\GoodsAttrInfoForm;
use app\modules\api\models\GoodsForm;
use app\modules\api\models\GoodsListForm;
use app\modules\api\models\IndexForm;
use app\modules\api\models\SearchForm;
use app\modules\api\models\ShareQrcodeForm;
use app\modules\api\models\ShopListForm;
use app\modules\api\models\StoreConfigForm;
use app\modules\api\models\StoreFrom;
use app\modules\api\models\TopicForm;
use app\modules\api\models\TopicListForm;
use app\modules\api\models\VideoForm;
use app\modules\api\models\ShopForm;
use app\modules\api\models\WxForm;
use app\modules\api\models\TopicTypeForm;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
        ]);
    }

    /**
     * 首页接口
     */
    public function actionIndex()
    {
        $form = new IndexForm();
        $form->store_id = $this->store->id;
        $form->_platform = \Yii::$app->request->get('_platform');
        $form->page_id = \Yii::$app->request->get('page_id');
        return $form->search();
    }

    /**
     * 分类列表
     */
    public function actionCatList()
    {
        $form = new CatListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        return $form->search();
    }

    /**
     * 购买数据
     */
    public function actionBuyData()
    {
        $key = "buy_data";
        $cache = \Yii::$app->cache;
        $data = json_decode($cache->get($key));
        $cha_time = time() - (int)$data->time;
        unset($data->time);

        if ($data && $data->store_id == $this->store_id) {
            $user = mb_strlen($data->user, 'UTF-8') > 5 ? mb_substr($data->user, 0, 4, 'UTF-8') . '...' : $data->user;
            $address = mb_strlen($data->address, 'UTF-8') > 8 ? mb_substr($data->address, 0, 7, 'UTF-8') . '...' : $data->address;
            switch ($data->type) {
                case 2:
                    $data->url = '/pages/book/details/details?id=' . $data->goods;
                    $data->content = $user . '预约了' . $address;
                    break;
                case 3:
                    $data->url = '/pages/miaosha/details/details?id=' . $data->goods;
                    $data->content = $user . '秒杀了' . $address;
                    break;
                case 4:
                    $data->url = '/pages/pt/details/details?gid=' . $data->goods;
                    $data->content = $user . '拼团了' . $address;
                    break;
                default:
                    $data->url = '/pages/goods/goods?id=' . $data->goods;
                    $data->content = $user . '购买了' . $address;
                    break;
            }
            return new BaseApiResponse([
                'code' => 0,
                'data' => $data,
                'cha_time' => $cha_time,
                'md5' => md5(json_encode($data)),
            ]);
        } else {
            return new ApiResponse(1, 'Null');
        }
    }

    /**
     * 商品列表
     */
    public function actionGoodsList()
    {
        $form = new GoodsListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        return $form->search();
    }


    /**
     * 商品推荐
     */
    public function actionGoodsRecommend()
    {
        if (!$this->store->is_recommend) {
            return new ApiResponse(1, 'error');
        }
        $form = new GoodsListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->goods_id = \Yii::$app->request->get('goods_id');
        $form->recommend_count = $this->store->recommend_count;
        $form->limit = 6;
        $form->store_id = $this->store->id;
        return $form->recommend();
    }

    /**
     * 商品详情
     */
    public function actionGoods()
    {
        $form = new GoodsForm();
        $form->attributes = \Yii::$app->request->get();
        if (!\Yii::$app->user->isGuest) {
            $form->user_id = \Yii::$app->user->id;
        }
        $form->store_id = $this->store->id;
        return $form->search();
    }

    /**
     * 省市区数据
     */
    public function actionDistrict()
    {
        $commonDistrict = new CommonDistrict();
        $district = $commonDistrict->search();
        return new ApiResponse(0, 'success', $district);
    }


    public function actionGoodsAttrInfo()
    {
        $form = new GoodsAttrInfoForm();
        $form->attributes = \Yii::$app->request->get();
        return $form->search();
    }

    public function actionStore()
    {
        if (!$this->store) {
            return new ApiResponse(1, 'Store Is NULL');
        }
        $config = StoreConfigForm::getConfig();
        return new ApiResponse(0, 'success', $config);
    }

    public function actionUploadImage()
    {
        $form = new UploadForm();
        $upload_config = UploadConfig::findOne(['store_id' => $this->store->id]);
        $form->upload_config = $upload_config;
        return new BaseApiResponse($form->saveImage('image'));
    }

    //商品评价列表
    public function actionCommentList()
    {
        $form = new CommentListForm();
        $form->attributes = \Yii::$app->request->get();
        return $form->search();
    }

    //文章列表
    public function actionArticleList()
    {
        $list = Article::find()->where([
            'store_id' => $this->store->id,
            'is_delete' => 0,
            'article_cat_id' => \Yii::$app->request->get('cat_id'),
        ])->orderBy('sort DESC,addtime DESC')
            ->select('id,title')->asArray()->all();
        return new ApiResponse(0, 'success', ['list' => $list]);
    }

    //文章详情
    public function actionArticleDetail()
    {
        $id = \Yii::$app->request->get('id');
        if ($id == 'about_us') {
            $model = Article::findOne([
                'store_id' => $this->store->id,
                'article_cat_id' => 1,
            ]);
            if (!$model) {
                $model = new Article();
            }

            $data = [
                'id' => $model->id,
                'title' => $model->title,
                'content' => $model->content,
            ];
            return new ApiResponse(0, 'success', $data);
        } else {
            $model = Article::find()->where([
                'is_delete' => 0,
                'id' => $id,
            ])->select('id,title,content')->asArray()->one();
            if (empty($model)) {
                return new ApiResponse(1, '内容不存在');
            }
            return new ApiResponse(0, 'success', $model);
        }
    }

    //核销二维码  已废弃
    public function actionQrcode($path)
    {
        include \Yii::$app->basePath . '/extensions/phpqrcode/phpqrcode.php';
        \QRcode::png($path);
    }

    public function actionVideoList()
    {
        $form = new VideoForm();
        $form->store_id = $this->store_id;
        $form->attributes = \Yii::$app->request->get();
        $form->limit = 10;
        return $form->getList();
    }

    public function actionCouponList()
    {
        $form = new CouponListForm();
        $form->store_id = $this->store_id;
        $form->user_id = \Yii::$app->user->identity->id;
        $list = $form->getList();
        return new ApiResponse(0, 'success', ['list' => $list]);
    }

    //获取商品二维码海报
    public function actionGoodsQrcode()
    {
        $form = new ShareQrcodeForm();
        $form->attributes = \Yii::$app->request->get();

        $form->store_id = $this->store_id;
        $form->type = 0;
        if (!\Yii::$app->user->isGuest) {
            $form->user = \Yii::$app->user->identity;
            $form->user_id = \Yii::$app->user->id;
        }
        return new BaseApiResponse($form->search());
    }

    //专题列表
    public function actionTopicList()
    {
        $form = new TopicListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store_id;
        return $form->search();
    }

    //专题详情
    public function actionTopic()
    {
        $form = new TopicForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store_id;
        if (!\Yii::$app->user->isGuest) {
            $form->user_id = \Yii::$app->user->id;
        }
        return $form->search();
    }

    //专题分类
    public function actionTopicType()
    {
        $form = new TopicTypeForm();
        $form->store_id = $this->store_id;
        return $form->search();
    }

    //专题海报
    public function actionTopicQrcode()
    {
        $form = new ShareQrcodeForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store_id;
        $form->type = 6;
        if (!\Yii::$app->user->isGuest) {
            $form->user = \Yii::$app->user->identity;
            $form->user_id = \Yii::$app->user->id;
        }
        return new BaseApiResponse($form->search());
    }

    //底部导航栏
    public function actionNavbar()
    {
        $navbar = AppNavbar::getNavbar($this->store->id);

        foreach ($navbar['navs'] as &$item) {
            $newNavs = [];
            if (isset($item['params']) && $item['open_type'] !== 'redirect' && $item['open_type'] !== '') {
                foreach ($item['params'] as $k => $v) {
                    $newNavs[$v['key']] = $v['value'];
                }
                $item['params'] = $newNavs;
            } else {
                if (isset($item['params']) && !empty($item['params'])) {
                    $param = '';
                    foreach ($item['params'] as $k => $v) {
                        if($item['url'] == '/pages/pt/index/index' && !$v['value']) {
                            continue;
                        }
                        if ($k === 0) {
                            $param .= '?' . $v['key'] . '=' . $v['value'];
                        } else {
                            $param .= '&' . $v['key'] . '=' . $v['value'];
                        }
                    }
                    $item['new_url'] = $item['url'] . $param;
                } else {
                    $item['new_url'] = $item['url'];
                }
            }
            if ($num = strpos($item['url'], '?')) {
                $item['url'] = substr($item['url'], 0, $num);
            }
        }
        unset($item);
        return new ApiResponse(0, 'success', $navbar);
    }

    //顶部导航栏颜色
    public function actionNavigationBarColor()
    {
        $navigation_bar_color = Option::get('navigation_bar_color', $this->store->id, 'app', [
            'frontColor' => '#000000',
            'backgroundColor' => '#ffffff',
        ]);
        return new ApiResponse(0, 'success', $navigation_bar_color);
    }

    //门店列表
    public function actionShopList()
    {
        $form = new ShopListForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->get();
        return $form->search();
    }

    //门店详情
    public function actionShopDetail()
    {
        $form = new ShopForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->get();
        return $form->search();
    }

    /**
     * 商品列表
     */
    public function actionSearch()
    {
        $form = new SearchForm();
        $form->attributes = \Yii::$app->request->get();
        $form->defaultCat = json_decode(\Yii::$app->request->get('defaultCat'));
        $form->store_id = $this->store->id;
        return $form->search();
    }

    /**
     * 搜索分类
     */
    public function actionCats()
    {
        $form = new SearchForm();
        $cats = $form->cats();

        return new ApiResponse(0, 'success', $cats);
    }

    public function actionFormId()
    {
        $formIdList = \Yii::$app->request->post('formIdList');
        $res = CommonFormId::save($formIdList);

        return new ApiResponse(0, 'success', $res);

    }
}
