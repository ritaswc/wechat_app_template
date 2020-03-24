<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

?>

<div class="panel mb-3">
    <div class="panel-header">页面编辑</div>
    <div class="panel-body">

        <form class="auto-form" method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">页面标题</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" value="<?= $detail['title'] ?>" name="title">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">状态</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input id="radio1" <?= $detail['status'] == 1 ? 'checked' : null ?>
                               value="1"
                               name="status" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">启用</span>
                    </label>
                    <label class="radio-label">
                        <input id="radio2" <?= $detail['status'] == 0 ? 'checked' : null ?>
                               value="0"
                               name="status" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">禁用</span>
                    </label>
                </div>
                <div class="fs-sm text-danger"></div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">模板</label>
                </div>
                <div class="col-sm-6" style="max-width: 360px">
                    <?php if (count($templateList) > 0): ?>
                        <?php foreach ($templateList as $item): ?>
                            <label class="radio-label">
                                <input value="<?= $item['id'] ?>"
                                       <?= $item['id'] == $detail['template_id'] ? 'checked' : '' ?>
                                       name="template_id" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text"><?= $item['name'] ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>

</div>
