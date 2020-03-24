<?php
defined('YII_ENV') or exit('Access Denied');

use \app\models\User;
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '参与详情';
$this->params['active_nav_group'] = 4;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>头像</th>
                    <th>所属平台</th>
                    <th>邀请人</th>
                    <th>幸运码数量</th>
                    <th>状态</th>
                    <th>创建时间</th>
                </tr>
            </thead>
            <?php foreach ($list as $k=>$u) : ?>
                <tr>   
                    <td><?= $u['user']['id']; ?></td>
                    <td>
                        <img src="<?= $u['user']['avatar_url'] ?>" style="width: 34px;height: 34px;margin: -.6rem 0;">&nbsp&nbsp&nbsp<?= $u['user']['nickname']; ?>
                    </td>
                    <td>
                        <?php if (isset($u['user']['platform']) && intval($u['user']['platform']) === 0): ?>
                            <span class="badge badge-success">微信</span>
                        <?php elseif (isset($u['user']['platform']) && intval($u['user']['platform']) === 1): ?>
                            <span class="badge badge-primary">支付宝</span>
                        <?php else: ?>
                            <span class="badge badge-default">未知</span>
                        <?php endif; ?>
                    </td>
                    <td><img <?= $u['childSelf']?'':'hidden' ?> src="<?= $u['childSelf']['user']['avatar_url'] ?>" style="width: 34px;height: 34px;margin: -.6rem 0;">&nbsp&nbsp&nbsp<?= $u['childSelf']['user']['nickname'];?></td>
                    <td>
                        <a href="#" data-toggle="modal" data-target="#myModal" onclick="edit_stock(<?= $k ?>,<?= $u['lottery_id'] ?>,<?= $u['user_id'] ?>)"><?= $u['lucky_num']; ?></a>
                    </td>
                    <td>
                        <span class="badge badge-success"><?= $u['is_award'] == 0 ? '待开奖' : '' ?></span>
                        <span class="badge badge-success"><?= $u['is_award'] == 1 ? '未中奖' : '' ?></span>
                        <span class="badge badge-danger"><?= $u['is_award'] == 2 ? '已中奖' : '' ?></span>
                        <span class="badge badge-danger"><?= $u['is_award'] == 3 ? '已中奖' : '' ?></span>
                    </td>
                    <td><?= $u['addtime']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="text-center">
            <nav aria-label="Page navigation example">
                <?php echo LinkPager::widget([
                    'pagination' => $pagination,
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                    'maxButtonCount' => 5,
                    'options' => [
                        'class' => 'pagination',
                    ],
                    'prevPageCssClass' => 'page-item',
                    'pageCssClass' => "page-item",
                    'nextPageCssClass' => 'page-item',
                    'firstPageCssClass' => 'page-item',
                    'lastPageCssClass' => 'page-item',
                    'linkOptions' => [
                        'class' => 'page-link',
                    ],
                    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                ])
            ?>
            </nav>
        </div> 

    </div>
</div>

<div class="modal fade" aria-labelledby="myModalLabel" aria-hidden="true" id="myModal" style="margin-top:200px;">
    <div class="modal-dialog"  id="app">
        <div class="modal-content">
            <div class="modal-header" style="height:40px;">
                <h5 class="modal-title" id="myModalLabel">
                    邀请名单
                </h5>
            </div>
            <div class="modal-body">

                <table class="table table-bordered bg-white">
                    <thead>
                        <tr>

                            <th>基本信息</th>
                            <th>所得幸运码</th>
                            <th>状态</th>                            
                            <th>参与时间</th>
                        </tr>
                    </thead>
                    <tr v-if="self">
                        <td>
                            <img :src="self['user']['avatar_url'] " style="width: 34px;height: 34px;margin: -.6rem 0;">&nbsp&nbsp{{self['user']['nickname']}}
                        </td>
                            <td>{{self['lucky_code']}}</td>
                        <td>
                            <span class="badge badge-success" v-if="self.status == 0">待开奖</span>
                            <span class="badge badge-success" v-if="self.status == 1">未中奖</span>
                            <span class="badge badge-danger" v-if="self.status == 2">已中奖</span>
                            <span class="badge badge-danger" v-if="self.status == 3">已中奖</span>
                        </td>
                        <td>{{self.addtime}}</td>
                    </tr>

                </table>





                <table class="table table-bordered bg-white">
                    <thead>
                        <tr>

                            <th>基本信息</th>
                            <th>邀请所得幸运码</th>
                            <th>状态</th>                            
                            <th>邀请时间</th>
                        </tr>
                    </thead>
                    <tr v-for="(v,index) in invite">
                        <td><img :src="v.childId.avatar_url" style="width: 34px;height: 34px;margin: -.6rem 0;">&nbsp&nbsp{{v.childId.nickname}}</td>
                        <td v-text="v.lucky_code"></td>
                        <td>
                            <span class="badge badge-success" v-if="v.status == 0">待开奖</span>
                            <span class="badge badge-success" v-if="v.status == 1">未中奖</span>
                            <span class="badge badge-danger" v-if="v.status == 2">已中奖</span>
                            <span class="badge badge-danger" v-if="v.status == 3">已中奖</span>
                        </td>
                        <td>{{v.childId.addtime}}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer" style="height:60px;">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="close">关闭</button>
            </div>
        </div>
    </div>
</div>



<script>
    var app = new Vue({
        el: '#app',
        data: {
            invite:[],
            list:<?=json_encode((array)$list, JSON_UNESCAPED_UNICODE)?>,
            self:'',
        }
    });

    function edit_stock(k,lottery_id,user_id){
        app.self = app.list[k];

        $.ajax({
            url: "<?= $urlManager->createUrl(['mch/lottery/default/partake-detail']) ?>",
            dataType: 'json',
            data: {
                lottery_id:lottery_id,
                user_id:user_id,
            },
            success: function (res) {
                if (res.code == 0) {
                    app.invite = res.list;
                }
            }
        });
    };
</script>