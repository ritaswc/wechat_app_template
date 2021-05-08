<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$cat = [
    1 => '关于我们',
    2 => '服务中心',
];
$cat_id = Yii::$app->request->get('cat_id', 2);
$urlManager = Yii::$app->urlManager;
$this->title = '拼团规则';
$this->params['active_nav_group'] = 10;
$this->params['is_group'] = 1;

?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off">
            <div class="form-body">
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">标题</label>
                    </div>
                    <div class="col-9">
                        <div class="input-group">
                            <input readonly class="form-control cat-name" name="title"
                                   value="<?= $model->title ?: '拼团规则' ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="col-form-label required">排序</label>
                    </div>
                    <div class="col-9">
                        <div class="input-group">
                            <input class="form-control cat-name" name="sort"
                                   value="<?= $model->sort ? $model->sort : 100 ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label required">内容</label>
                    </div>
                    <div class="col-9">
                        <div class="input-group">
                        <textarea id="editor" style="width: 100%"
                                  name="content"><?= $model->content ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                    </div>
                    <div class="col-9">
                        <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    </div>
                </div>

            </div>
        </form>
    </div>

</div>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.config.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.all.min.js"></script>
<script>
    var ue = UE.getEditor('editor', {
        serverUrl: "<?=$urlManager->createUrl(['upload/ue'])?>",
    });
</script>