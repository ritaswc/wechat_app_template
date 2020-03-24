<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '秒杀';
$this->params['active_nav_group'] = 10;
?>

<div class="alert alert-info rounded-0">
    <div>注：设置了开放时间，小程序端才有相关秒杀时间点出现</div>
    <div>注：秒杀入口可以在
        <a target="_blank" href="<?= $urlManager->createUrl(['mch/store/home-nav']) ?>">导航图标</a>、
        <a target="_blank"
           href="<?= $urlManager->createUrl(['mch/store/home-block']) ?>">图片魔方</a>、
        <a target="_blank" href="<?= $urlManager->createUrl(['mch/store/slide']) ?>">轮播图</a>设置。
    </div>
</div>
<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
    </div>
    <div class="panel-body">
        <form class="auto-form" method="post" autocomplete="off">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">开放时间</label>
                </div>
                <div class="col-sm-6">
                    <?php $model->open_time = json_decode($model->open_time, true); ?>
                    <?php for ($i = 0; $i < 24; $i++) : ?>
                        <label class="custom-control custom-checkbox">
                            <input name="open_time[]"<?= is_array($model->open_time) && in_array($i, $model->open_time) ? 'checked' : null ?>
                                   value="<?= $i ?>"
                                   type="checkbox" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description"><?= $i < 10 ? '0' . $i : $i ?>
                                :00~<?= $i < 10 ? '0' . $i : $i ?>:59</span>
                        </label>
                    <?php endfor; ?>
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
