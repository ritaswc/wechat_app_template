<?php
$this->title = '小程序标题设置';
?>
<div class="panel mb-3">
    <div class="panel-header">小程序标题设置</div>
    <div class="panel-body">

        <form class="auto-form" method="post">
            <?php foreach ($list as $index=>$item): ?>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label"><?= $item['title'] ?></label>
                </div>
                <div class="col-sm-3">
                    <input name="model[<?=$index?>][title]" class="form-control" value="<?= $item['new_title'] ?>">
                </div>
                <input style="display: none;" name="model[<?=$index?>][url]" value="<?= $item['url'] ?>">
            </div>
            <?php endforeach; ?>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right"></div>
                <div class="col-sm-6">
                    <span style="color: red;">保存后在小程序端使用 清除缓存 功能刷新数据</span>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right"></div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>

    </div>
</div>
