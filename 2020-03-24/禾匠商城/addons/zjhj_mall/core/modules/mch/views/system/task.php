<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/11/22
 * Time: 17:48
 */
/* @var $list \app\models\Task[]*/
$this->title = '';
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="p-4 bg-shaixuan">
                <form method="get">
                    <?php $_s = ['keyword', 'keyword_1', 'date_start', 'date_end', 'page', 'per-page'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>

                    <div flex="dir:left">
                        <div class="mr-3 ml-3">
                            <div class="form-group row">
                                <div>
                                    <div class="input-group">
                                        <input class="form-control" id="date_start" name="startTime"
                                               autocomplete="off"
                                               value="<?= isset($_GET['startTime']) ? trim($_GET['startTime']) : '' ?>">
                                <span class="input-group-btn">
                                            <a class="btn btn-secondary" id="show_date_start" href="javascript:">
                                                <span class="iconfont icon-daterange"></span>
                                            </a>
                                        </span>
                                        <span class="middle-center input-group-addon" style="padding:0 4px">至</span>
                                        <input class="form-control" id="date_end" name="endTime"
                                               autocomplete="off"
                                               value="<?= isset($_GET['endTime']) ? trim($_GET['endTime']) : '' ?>">
                                <span class="input-group-btn">
                                            <a class="btn btn-secondary" id="show_date_end" href="javascript:">
                                                <span class="iconfont icon-daterange"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="middle-center">
                                    <button class="btn btn-primary mr-4">筛选</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-bordered bg-white">
            <tr>
                <td>id</td>
                <td>任务描述</td>
                <td>状态</td>
                <td>触发类</td>
                <td>携带参数</td>
                <td>时间</td>
            </tr>
            <?php foreach($list as $index => $value):?>
                <tr>
                    <td><?= $value->token?></td>
                    <td><?= $value->content?></td>
                    <td><?= $value->executedText?></td>
                    <td><?= $value->class?></td>
                    <td><?= $value->params?></td>
                    <td><?= $value->addTimeText?></td>
                </tr>
            <?php endforeach;?>
        </table>
        <div class="text-center">
            <nav aria-label="Page navigation example">
                <?php echo \yii\widgets\LinkPager::widget([
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
            <div class="text-muted">共<?= $pagination->totalCount ?>条数据</div>
        </div>
    </div>
</div>
