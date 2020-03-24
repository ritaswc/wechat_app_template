<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '用户列表';
$this->params['active_nav_group'] = 4;
?> 

<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">

        <div class="float-right mb-4">
            <form method="get">
                <?php $_s = ['keyword', 'page', 'per-page'] ?>
                <?php foreach ($_GET as $_gi => $_gv) :
                    if (in_array($_gi, $_s)) {
                        continue;
                    } ?>
                    <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                <?php endforeach; ?>
                <div class="input-group">
                    <input class="form-control"
                           placeholder="昵称"
                           name="keyword"
                           autocomplete="off"
                           value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-primary">搜索</button>
                    </span>
                </div>
            </form>
        </div>

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>基本信息</th>
                    <th>邀请人数</th>
                    <th>步数加成(千分之)</th>
                    <th>活力币</th>
                    <th>创建时间</th>
                    <th>详情</th>
                </tr>
            </thead>
            <?php foreach ($list as $u) : ?>
                <tr>   
                    <td><?= $u['id']; ?></td>
                    <td>
                        <img src="<?= $u['user']['avatar_url'] ?>"
                         style="width: 34px;height: 34px;margin: -.6rem 0;">&nbsp&nbsp&nbsp<?= $u['user']['nickname']; ?>
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#myModal" onclick="invite_show(<?= $u['id'] ?>)"><?= $u['child_num'];?>
                        </a>
                    </td>
                    <td><?= $u['ratio'];?></td>
                    <td><?= $u['step_currency']; ?></td>
                    <td><?= date('Y-m-d H:i', $u['create_time']); ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/step/default/log', 'id' => $u['id']]) ?>">兑换记录</a>
                    </td>
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
                                <th>邀请加成(千分之)</th>                            
                                <th>邀请时间</th>
                            </tr>
                        </thead>
                        <tr v-for="(v,index) in invite_list">   
                            <td><img :src="v.user.avatar_url" style="width: 34px;height: 34px;margin: -.6rem 0;">&nbsp&nbsp&nbsp{{v.user.nickname}}</td>
                            <td>{{v.invite_ratio}}</td>
                            <td>{{new Date(parseInt(v.create_time) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ')}}</td>
                        </tr>
                    </table>
                    <div class="text-center">
                        <a href="javascript:" onclick="invite_more()">加载更多</a>
                    </div>
                </div>

                <div class="modal-footer" style="height:60px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="close">关闭</button>
                </div>
            </div>
        </div>
    </div>
    
</div>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            invite_list:[],
            page:1,
            more:false,
            id:'',
        }
    });
    function invite_show(id)
    {   
        app.id = id;
        app.page = 1;
        $.ajax({
            url: "<?= $urlManager->createUrl(['mch/step/default/invite']) ?>",
            dataType: 'json',
            data: {
                id:app.id,
                page:app.page,
            },
            success: function (res) {
                if (res.code == 0) {
                    app.invite_list = res.invite_list;
                    console.log(app.invite_list);
                }
            }
        });

    };

    function invite_more()
    {   
        if(app.more){
            return;
        }
        app.page = app.page + 1;
        $.ajax({
            url: "<?= $urlManager->createUrl(['mch/step/default/invite']) ?>",
            dataType: 'json',
            data: {
                id:app.id,
                page:app.page,
            },
            success: function (res) {
                if (res.code == 0) {
                    if(res.invite_list.length==0) {
                        app.more = true;
                        return;
                    }
                    app.invite_list = app.invite_list.concat(res.invite_list);
                }
            }
        });
    }
</script>