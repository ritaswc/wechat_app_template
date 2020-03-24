<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/1/31
 * Time: 10:15
 */
/** @var \app\models\FxhbSetting $model */
$this->title = '红包设置';
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post">

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">开启活动</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input type="radio" name="game_open" value="0" <?= $model->game_open == 0 ? 'checked' : null ?>>
                        <span class="label-icon"></span>
                        <span class="label-text">关闭</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="game_open" value="1" <?= $model->game_open == 1 ? 'checked' : null ?>>
                        <span class="label-icon"></span>
                        <span class="label-text">开启</span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">红包分配方式</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input type="radio" name="distribute_type"
                               value="0" <?= $model->distribute_type == 0 ? 'checked' : null ?>>
                        <span class="label-icon"></span>
                        <span class="label-text">随机</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="distribute_type"
                               value="1" <?= $model->distribute_type == 1 ? 'checked' : null ?>>
                        <span class="label-icon"></span>
                        <span class="label-text">平均</span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">红包人数</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control" name="user_num" value="<?= $model->user_num ?>">
                        <span class="input-group-addon">人</span>
                    </div>
                    <div class="text-muted fs-sm">规定时间内达到这个人数后，红包成功拆分，最少2人，建议值4或8。</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">红包总金额</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control" name="coupon_total_money" value="<?= $model->coupon_total_money ?>">
                        <span class="input-group-addon">元</span>
                    </div>
                    <div class="text-muted fs-sm">红包赠送的代金券总金额，最低=红包人数*0.01元</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">代金券门槛</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control" name="coupon_use_minimum" value="<?= $model->coupon_use_minimum ?>">
                        <span class="input-group-addon">元</span>
                    </div>
                    <div class="text-muted fs-sm">代金券使用的最低消费金额，最低0元</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">代金券有效时间</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control" name="coupon_expire" value="<?= $model->coupon_expire ?>">
                        <span class="input-group-addon">天</span>
                    </div>
                    <div class="text-muted fs-sm">代金券有效时间，从发放时间算起，默认30天</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">拆红包有效时间</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control" name="game_time" value="<?= $model->game_time ?>">
                        <span class="input-group-addon">小时</span>
                    </div>
                    <div class="text-muted fs-sm">有效时间内用户可以邀请好友一起拆红包，过期则此红包作废，建议填24小时</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">活动规则</label>
                </div>
                <div class="col-sm-6">
                    <textarea class="form-control" name="rule" rows="8"><?= $model->rule ?></textarea>
                    <div class="text-muted fs-sm"><a href="javascript:" data-toggle="modal"
                                                     data-target="#rule_demo">规则示例</a></div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">分享标题</label>
                </div>
                <div class="col-sm-6">
                    <textarea class="form-control" name="share_title" rows="3"><?= $model->share_title ?></textarea>
                    <div class="text-muted fs-sm">多个标题请换行，多个标题随机选一个标题显示</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">分享图片</label>
                </div>
                <div class="col-sm-6">
                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input" name="share_pic" value="<?= $model->share_pic ?>">
                            <span class="input-group-btn">
                                <a class="btn btn-secondary upload-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="上传文件">
                                    <span class="iconfont icon-cloudupload"></span>
                                </a>
                            </span>
                            <span class="input-group-btn">
                                <a class="btn btn-secondary select-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="从文件库选择">
                                    <span class="iconfont icon-viewmodule"></span>
                                </a>
                            </span>
                            <span class="input-group-btn">
                                <a class="btn btn-secondary delete-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="删除文件">
                                    <span class="iconfont icon-close"></span>
                                </a>
                            </span>
                        </div>
                        <div class="upload-preview text-center upload-preview">
                            <span class="upload-preview-tip">500&times;400</span>
                            <img class="upload-preview-img" src="<?= $model->share_pic ?>">
                        </div>
                    </div>
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

<!-- Modal -->
<div class="modal fade" id="rule_demo">
    <div class="modal-dialog" role="document">
        <div class="panel">
            <div class="panel-body">
                <div class="alert alert-warning mb-3 rounded-0">本规则仅供参考</div>
                <ol style="list-style: none;padding: 0">
                    <li>1.用户可邀请好友共同拆红包，满N人则拆红包现金红包成功，共同瓜分总金额为N元的红包，每人获得红包金额随机（或平均）; 其中随机一人将获得“手气最佳红包”。</li>
                    <li>2.每个红包发起后24小时未组满N人即失败，无红包奖励。</li>
                    <li>3.活动期间，不能帮同一好友拆多次，但发起拆红包次数不限。</li>
                    <li>4.发起拆红包的用户需在该红包满N人拆成功或逾期失败后，才可再发起拆下一个红包。</li>
                    <li>5.一起拆红包活动的红包均为满减现金券。</li>
                    <li>6.本公司对该活动规则保留最终解释权。</li>
                </ol>
                <div class="text-center">
                    <a class="btn btn-primary" data-dismiss="modal" href="javascript:">确认</a>
                </div>
            </div>
        </div>
    </div>
</div>