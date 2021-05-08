<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/6
 * Time: 11:17
 */
$urlManager = Yii::$app->urlManager;
$this->title = '版权设置';
$this->params['active_nav_group'] = 1;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form method="get" class="form-inline mb-3">
            <?php foreach ($_GET as $name => $value) :
                if (!in_array($name, ['keyword'])) : ?>
                <input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
                <?php endif;
            endforeach; ?>
            <input placeholder="商城名称" class="form-control mr-3" name="keyword"
                   value="<?= \Yii::$app->request->get('keyword') ?>">
            <button class="btn btn-primary">查找</button>
        </form>
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>商城名称</th>
                <th>设置版权</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $item) :?>
                <tr>
                    <td><?=$item['id']?></td>
                    <td><?=$item['name']?></td>
                    <td>
                        <a href="<?=$urlManager->createUrl(['admin/admin/copyright','id'=>$item['id'],'url'=>$urlManager->createUrl(['admin/admin/copyright-list'])])?>">修改</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

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
    </div>
</div>

