<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/12
 * Time: 14:50
 */
$urlManager = Yii::$app->urlManager;
?>
<div>
    <span>砍价设置</span>
    <span class="step-location" id="step9"></span>
</div>
<div>
    <div class="form-group row">
        <div class="col-3 text-right">
            <label class=" col-form-label required">最低价</label>
        </div>
        <div class="col-9">
            <div class="input-group short-row">
                <input class="form-control" name="plugins[min_price]" type="number" min="0" value="<?=$plugins['min_price']?>">
                <span class="input-group-addon">元</span>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-3 text-right">
            <label class=" col-form-label required">活动时间</label>
        </div>
        <div class="col-9">
            <div class="input-group short-row">
                <input class="form-control" id="date_start" name="plugins[begin_time]" value="<?=$plugins['begin_time']?>">
                <span class="input-group-addon">~</span>
                <input class="form-control" id="date_end" name="plugins[end_time]" value="<?=$plugins['end_time']?>">
            </div>
            <div class="text-danger">商品砍价时间，到时间则活动结束</div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-3 text-right">
            <label class=" col-form-label required">砍价时间</label>
        </div>
        <div class="col-9">
            <div class="input-group short-row">
                <input class="form-control" name="plugins[time]" value="<?=$plugins['time']?>">
                <span class="input-group-addon">小时</span>
            </div>
            <div class="text-danger">若到时间没有购买，则视为砍价失败</div>
        </div>
    </div>
    <div class="form-group row kj-div">
        <div class="col-3 text-right">
            <label class=" col-form-label required">砍价方式</label>
        </div>
        <div class="col-9">
            <div class="input-group short-row">
                <span class="input-group-addon">参与人数</span>
                <input class="form-control" name="plugins[people]" type="number" min="0" value="<?=$plugins['people']?>">
            </div>
            <div class="text-danger mb-4">
                <div>若不填或填0，则表示不限参与人数，砍完为止</div>
                <div>若填写参与人数，则参与人数必须大于1；刚好达到参与人数时砍价完成</div>
            </div>
            <div class="input-group short-row mb-4">
                <span class="input-group-addon">前</span>
                <input class="form-control" name="plugins[human]" type="number" min="0" value="<?=$plugins['human']?>">
                <span class="input-group-addon">人砍价</span>
                <input class="form-control" name="plugins[first_min_money]" type="number" min="0" value="<?=$plugins['first_min_money']?>">
                <span class="input-group-addon">~</span>
                <input class="form-control" name="plugins[first_max_money]" type="number" min="0" value="<?=$plugins['first_max_money']?>">
                <span class="input-group-addon">元</span>
            </div>
            <div class="input-group short-row">
                <span class="input-group-addon">剩余砍价</span>
                <input class="form-control" name="plugins[second_min_money]" type="number" min="0" value="<?=$plugins['second_min_money']?>">
                <span class="input-group-addon">~</span>
                <input class="form-control" name="plugins[second_max_money]" type="number" min="0" value="<?=$plugins['second_max_money']?>">
                <span class="input-group-addon">元</span>
            </div>
            <div class="text-danger">
                <div>前N个人砍价波动值，剩余价格波动值</div>
            </div>
        </div>
    </div>
</div>

