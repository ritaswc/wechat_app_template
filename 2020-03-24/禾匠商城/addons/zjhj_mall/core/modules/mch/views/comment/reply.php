<?php
/**
 * Created by PhpStorm.
 * User:
 * Date:
 * Time:
 */
defined('YII_ENV') or exit('Access Denied');
/* @var $sms \app\models\SmsSetting */
$urlManager = Yii::$app->urlManager;
$this->title = '回复评价';
$this->params['active_nav_group'] = 1;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="">
            <form method="post" class="form auto-form" autocomplete="off"
                  return="<?= $urlManager->createUrl(['mch/comment/index']) ?>">
                <div class="form-body">
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label">用户</label>
                        </div>
                        <div class="col-5">
                            <label class="col-form-label"><?= $list['nickname']?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label">商品名称</label>
                        </div>
                        <div class="col-5">
                            <label class="col-form-label"><?= $list['goods_name']?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label">评分</label>
                        </div>
                        <div class="col-5">
                            <label class="col-form-label">
                                <?php if ($list['score'] == 3) :
                                    ?>好评<?php
                                endif; ?>
                                <?php if ($list['score'] == 2) :
                                    ?>中评<?php
                                endif; ?>
                                <?php if ($list['score'] == 1) :
                                    ?>差评<?php
                                endif; ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label">详情</label>
                        </div>
                        <label class="col-5"><?= $list['content'] ?></label>
                        <?php $pic_list = json_decode($list['pic_list']); ?>
                        <div class="img-view-list">
                            <?php foreach ($pic_list as $pic) : ?>
                                <img height="50px" class="img-view" src="<?= $pic?>" alt="">
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">回复评价</label>
                        </div>
                        <div class="col-sm-6">
                            <textarea autocomplete="off" class="form-control" type="text" name="reply_content"
                                   ><?= $list['reply_content'] ?></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                        </div>
                        <div class="col-5">
                            <input type="button" class="btn btn-default mr-4" 
                            name="Submit" onclick="javascript:history.back(-1);" value="返回">
                            <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>