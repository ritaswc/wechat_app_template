<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/11/2
 * Time: 16:47
 */

namespace app\modules\mch\models\diy;


use app\models\Banner;
use app\models\HomeNav;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class NavBForm extends MchModel
{
    public $type;
    public $page;
    public $limit;

    public function search()
    {
        $res = [];
        switch ($this->type) {
            case 'nav':
                $res = $this->getNav();
                break;
            case 'banner':
                $res = $this->getBanner();
                break;
            default:
                $res = [];
                break;
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => $res
        ];
    }

    private function getNav()
    {
        $query = HomeNav::find()->where(['store_id' => $this->store->id, 'is_delete' => 0]);
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page-1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy(['sort'=>SORT_ASC, 'id' => SORT_DESC])->all();

        $navList = [];
        /* @var HomeNav[] $list*/
        foreach($list as $value) {
            $item = [
                'id' => $value->id,
                'name' => $value->name,
                'url' => $value->url,
                'pic_url' => $value->pic_url,
                'open_type' => $value->open_type
            ];
            $navList[] = $item;
        }

        return [
            'list' => $navList,
            'count' => $count,
            'page' => $this->page,
            'page_count' => $pagination->pageCount,
            'page_url' => \Yii::$app->urlManager->createUrl(['mch/diy/diy/get-nav-banner', 'type' => $this->type]),
            'type' => $this->type
        ];
    }

    private function getBanner()
    {
        $query = Banner::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'type' => 1]);
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page-1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy(['sort'=>SORT_ASC])->all();

        $bannerList = [];
        /* @var Banner[] $list*/
        foreach($list as $value) {
            $item = [
                'id' => $value->id,
                'name' => $value->title,
                'url' => $value->page_url,
                'pic_url' => $value->pic_url,
                'open_type' => $value->open_type
            ];
            $bannerList[] = $item;
        }

        return [
            'list' => $bannerList,
            'count' => $count,
            'page' => $this->page,
            'page_count' => $pagination->pageCount,
            'page_url' => \Yii::$app->urlManager->createUrl(['mch/diy/diy/get-nav-banner', 'type' => $this->type]),
            'type' => $this->type
        ];
    }
}