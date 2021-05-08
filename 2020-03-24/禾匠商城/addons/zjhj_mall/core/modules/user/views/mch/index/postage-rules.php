<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:14
 */

$urlManager = Yii::$app->urlManager;
$this->title = '运费规则';
$this->params['active_nav_group'] = 1;
?>
<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link"
                   href="<?= $urlManager->createUrl(['user/mch/index/postage-rules-edit']) ?>">添加新规则</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <table class="table table-bordered bg-white">
            <tr>
                <th>规则名称</th>
                <th hidden>快递公司</th>
                <th>是否默认</th>
                <th>操作</th>
            </tr>
            <?php if (is_array($list) && count($list) > 0) : ?>
                <?php foreach ($list as $item) : ?>
                    <tr>
                        <td><?= $item->name ?></td>
                        <td hidden><?= $item->express ? $item->express : '无' ?></td>
                        <td>

                            <label class="checkbox-label">
                                <input type="checkbox" <?= $item->is_enable == 1 ? 'checked' : null ?>
                                       name="checkbox1[]"
                                       class="default-rule-checkbox"
                                       data-url="<?= $urlManager->createUrl(['user/mch/index/postage-rules-set-enable', 'id' => $item->id, 'type' => $item->is_enable == 1 ? 0 : 1]) ?>">
                                <span class="label-icon"></span>
                                <span class="label-text">默认</span>
                            </label>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-primary"
                               href="<?= $urlManager->createUrl(['user/mch/index/postage-rules-edit', 'id' => $item->id]) ?>">修改</a>
                            <a class="btn btn-sm btn-danger rules-del"
                               href="<?= $urlManager->createUrl(['user/mch/index/postage-rules-delete', 'id' => $item->id]) ?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4" class="text-center text-muted p-5">暂无运费规则</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>
<script>

    $(document).on('change', '.default-rule-checkbox', function () {
        $.loading();
        location.href = $(this).attr('data-url');
    });

    $(document).on('click', '.rules-del', function () {
        var a = $(this);
        $.confirm({
            content: "是否删除该规则？",
            confirm: function () {
                $.loading({
                    content: "正在处理",
                });
                $.ajax({
                    url: a.attr("href"),
                    dataType: "json",
                    success: function (res) {
                        $.loadingHide();
                        $.alert({
                            content: res.msg,
                            confirm: function () {
                                if (res.code == 0) {
                                    location.reload();
                                }
                            }
                        });
                    }
                });
            }
        });
        return false;
    });
</script>