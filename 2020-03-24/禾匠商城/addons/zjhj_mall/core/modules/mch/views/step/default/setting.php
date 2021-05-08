<?php
defined('YII_ENV') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '基础设置';
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off" return="<?= $urlManager->createUrl(['mch/step/default/setting']) ?>">
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label">小程序标题</label>
                </div>
                <div class="col-sm-5">
                    <div class="input-group">
                        <input class="form-control" name="title" 
                           value="<?= $setting->title ?>">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">转发标题</label>
                </div>
                <div class="col-sm-5">
                    <textarea class="form-control" name="share_title" rows="3"><?= $setting->share_title ?></textarea>
                    <div class="text-muted fs-sm">多个标题请换行，多个标题随机选一个标题显示</div>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label">活力币别名</label>
                </div>

                <div class="col-sm-5">
                    <div class="input-group">
                        <input class="form-control" name="currency_name" 
                           value="<?= $option['step']['currency_name'] ?>">
                    </div>
                </div>
            </div> 

            <div class="form-group row"> 
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label">每日最高兑换数</label>
                </div>
                <div class="col-sm-5">
                    <div class="input-group short-row">
                        <input type="number" step="1" class="form-control"
                               name="convert_max" min="1" max="100000000"
                               value="<?= $setting->convert_max;?>">
                        <span class="input-group-addon">步</span>
                    </div>
                    <div class="fs-sm text-muted">设置0则为 无限制</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label">邀请用户加成比率</label>
                </div>
                <div class="col-sm-5">
                    <div class="input-group short-row">
                        <span class="input-group-addon">千分之</span>
                        <input type="number" step="1" class="form-control"
                               name="invite_ratio" min="1" max="100000000"
                               value="<?= $setting->invite_ratio;?>">
                    </div>
                    <div class="fs-sm text-danger">注：邀请概率为无限制永久增加</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label required">步数兑换比率</label>
                </div>
                <div class="col-sm-5">
                    <div class="input-group short-row">
                        <input type="number" step="1" class="form-control"
                               name="convert_ratio" min="1" max="100000000"
                               value="<?= $setting->convert_ratio;?>">
                        <span class="input-group-addon">兑换1活力币</span>
                    </div>
         
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label required">兑换提醒时间</label>
                </div>
                <div class="col-sm-5">
                    <div class="input-group short-row">
                        <input class="form-control"
                               id="remind_time"
                               name="remind_time"
                               value="<?= $setting->remind_time  ?>">
                    </div>
                    <div class="fs-sm text-danger">注：消息一天只发送一次</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label">全国纪录最多显示</label>
                </div>
                <div class="col-sm-5">
                    <input type="number" step="1" class="form-control"
                        name="ranking_num" min="1" max="100000000"
                        value="<?= $setting->ranking_num;?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label">海报文字</label>
                </div>
                <div class="col-sm-5">
                    <div class="input-group">
                        <input class="form-control" name="qrcode_title" 
                           value="<?= $setting->qrcode_title;?>">
                    </div>
                    <div class="fs-sm text-muted">最多显示十二个字</div>
                </div>
            </div> 
            
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label">海报模板</label>
                </div>
                <div class="col-sm-5">
                    <?php if ($qrcode_pic) :
                        foreach ($qrcode_pic as $pic) : ?>
                            <?php $pic_list[] = $pic->pic_url ?>
                        <?php endforeach;
                    else :
                        $pic_list = [];
                    endif; ?>
                    <div class="upload-group multiple short-row">
                        <div class="input-group">
                            <input class="form-control file-input" readonly>
                            <span class="input-group-btn">
                                <a class="btn btn-secondary upload-file" href="javascript:"
                                   data-toggle="tooltip"
                                   data-placement="bottom" title="上传文件">
                                    <span class="iconfont icon-cloudupload"></span>
                                </a>
                            </span>
                            <span class="input-group-btn">
                                <a class="btn btn-secondary select-file" href="javascript:"
                                   data-toggle="tooltip"
                                   data-placement="bottom" title="从文件库选择">
                                    <span class="iconfont icon-viewmodule"></span>
                                </a>
                            </span>
                        </div>
                        <div class="upload-preview-list" id="sortList">
                            <?php if (count($pic_list) > 0) : ?>
                                <?php foreach ($pic_list as $item) : ?>
                                    <div class="upload-preview text-center" flex="cross:center">
                                        <input type="hidden" class="file-item-input"
                                               name="pic_list[]"
                                               value="<?= $item ?>">
                                        <span class="file-item-delete">&times;</span>
                                        <span class="upload-preview-tip">750&times;900</span>
                                        <img class="upload-preview-img" src="<?= $item ?>">
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="upload-preview text-center">
                                    <input type="hidden" class="file-item-input"
                                           name="pic_list[]">
                                    <span class="file-item-delete">&times;</span>
                                    <span class="upload-preview-tip">750&times;900</span>
                                    <img class="upload-preview-img" src=""> 
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">排行榜背景</label>
                </div>
                <div class="col-sm-6">
                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input" name="ranking_pic"
                                   value="<?= $option['step']['ranking_pic'] ?>"> 
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
                            <span class="upload-preview-tip">750&times;400</span>
                            <img class="upload-preview-img"
                                 src="<?= $option['step']['ranking_pic'] ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">步数挑战背景</label>
                </div>
                <div class="col-sm-6">
                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input" name="activity_pic"
                                   value="<?= $option['step']['activity_pic'] ?>">
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
                            <span class="upload-preview-tip">750&times;560</span>
                            <img class="upload-preview-img"
                                 src="<?= $option['step']['activity_pic'] ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label">步数兑换规则说明</label>
                </div>
                <div class="col-sm-5">
                    <textarea class="form-control" rows="8" name="rule"><?= $setting->rule ?></textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label">步数挑战规则说明</label>
                </div>
                <div class="col-sm-5">
                    <textarea class="form-control" rows="8" name="activity_rule"><?= $setting->activity_rule ?></textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right"></div>
                <div class="col-9">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        $.datetimepicker.setLocale('zh');
        $('#remind_time').datetimepicker({
            format: 'H:i',
            onShow: function (ct) {
                this.setOptions({
                    maxDate: $('#remind_time').val() ? $('#remind_time').val() : false
                })
            },
            timepicker: true,
            datepicker: false,
        });
    })();
</script>
