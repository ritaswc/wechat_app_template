<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/11
 * Time: 17:34
 */

namespace app\plugins\bargain\events;


use app\models\BargainGoods;
use app\modules\mch\events\goods\BaseAddGoodsEvent;
use Hejiang\Event\Dispatchable;
use Hejiang\Event\EventArgument;
use app\plugins\bargain\models\GoodsForm;
/**
 * @property \app\models\Goods $goods
 */
class AddGoodsEvent extends BaseAddGoodsEvent implements Dispatchable
{
    public $goods;

    /**
     * @param EventArgument $args ['plugin'=>'插件类型','goods'=>'商品侧数据','post'=>'是否是post请求','data'=>'插件侧数据']
     */
    public function onDispatch(EventArgument $args)
    {
        $args['plugin'] = get_plugin_type();
        if (isset($args['plugin']) && $args['plugin'] == 2) {
            $this->goods = $args['goods'];
            $bargain = BargainGoods::findOne(['goods_id'=>$this->goods->id]);
            if(!$bargain){
                $bargain = new BargainGoods();
            }
            $form = new GoodsForm();
            $form->bargain = $bargain;
            if (isset($args['post']) && $args['post']) {
                // 编辑商品页面请求提交
                $form->goods = $this->goods;
                $form->attributes = $args['data'];
                $args->pushResult($form->save());
            } else {
                // 编辑商品页面渲染
                $newList = $form->search();
                $args->pushResult([
                    'url' => '@app/plugins/bargain/view/goods-edit.php',
                    'data' => [
                        'plugins'=>$newList
                    ]
                ]);
            }
        }
    }
}