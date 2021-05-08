<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\api\models;

use app\models\common\CommonWx;
use app\models\WxTitle;

class WxForm extends ApiModel
{

    public function index()
    {
        $list = WxTitle::findAll(['store_id' => $this->getCurrentStoreId()]);

        $common = new CommonWx();
        $wxDefaultTitle = $common->wxDefaultTitle();

        foreach ($wxDefaultTitle as $k => $item) {
            foreach ($list as $i) {
                if ($i->url === $item['url'] && $i->title) {
                    $wxDefaultTitle[$k] = [
                        'url' => $i->url,
                        'title' => $i->title,
                    ];
                    break;
                }
            }

        }

        return $wxDefaultTitle;
    }
}