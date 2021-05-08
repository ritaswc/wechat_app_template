<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */
$this->title = '入驻商管理';
$url_manager = Yii::$app->urlManager;
?>
<style>
    .user-item {
        border-bottom: 1px solid #e3e3e3;
        padding: .5rem 0;
    }

    .user-item:first-child {
        border-top: 1px solid #e3e3e3;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
    </div>
    <div class="panel-body" id="app">
        <form class="auto-form" method="post"
              return="<?= Yii::$app->request->referrer ? Yii::$app->request->referrer : '' ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">小程序用户</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="user-id" type="hidden" name="user_id" readonly>
                        <input class="form-control user-nickname" value="<?= $model->user->nickname ?>" readonly>
                        <span class="input-group-btn">
                        <a href="javascript:" class="btn btn-secondary" data-toggle="modal"
                           data-target="#searchUserModal">查找</a>
                        </span>
                    </div>
                    <div class="text-danger text-muted">注：修改小程序用户将会导致原商户无法登录</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">联系人</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="realname" value="<?= $model->realname ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">联系电话</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="tel" value="<?= $model->tel ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">微信号</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="wechat_name" value="<?= $model->wechat_name ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">店铺名称</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="name" value="<?= $model->name ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">所在地区</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input type="hidden" name="province_id" value="<?= $model->province_id ?>">
                        <input type="hidden" name="city_id" value="<?= $model->city_id ?>">
                        <input type="hidden" name="district_id" value="<?= $model->district_id ?>">
                        <input class="form-control district-text"
                               value="<?= $province->name ?>-<?= $city->name ?>-<?= $district->name ?>" readonly>
                        <span class="input-group-btn">
                            <a class="btn btn-secondary picker-district" href="javascript:">选择地区</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">详细地址</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="address" value="<?= $model->address ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">所售类目</label>
                </div>
                <div class="col-sm-6">
                    <select class="form-control" name="mch_common_cat_id">
                        <?php foreach ($mch_common_cat_list as $item) : ?>
                            <option value="<?= $item->id ?>"
                                <?= $item->id == $model->mch_common_cat_id ? 'selected' : null ?>><?= $item->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">客服电话</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="service_tel" value="<?= $model->service_tel ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">店铺Logo</label>
                </div>
                <div class="col-sm-6">
                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input" name="logo" value="<?= $model->logo ?>">
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
                            <span class="upload-preview-tip">100&times;100</span>
                            <img class="upload-preview-img" src="<?= $model->logo ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">店铺背景（顶部）</label>
                </div>
                <div class="col-sm-6">
                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input" name="header_bg" value="<?= $model->header_bg ?>">
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
                            <span class="upload-preview-tip">750&times;300</span>
                            <img class="upload-preview-img" src="<?= $model->header_bg ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">手续费(千分之)</label>
                </div>
                <div class="col-sm-6">
                    <input type="number" min="0" max="1000" step="1" class="form-control" name="transfer_rate"
                           value="<?= $model->transfer_rate ?>">
                    <div>商户每笔订单交易金额扣除的手续费，请填写0~1000范围的整数</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">排序</label>
                </div>
                <div class="col-sm-6">
                    <input type="number" min="0" max="10000000" step="1" class="form-control" name="sort"
                           value="<?= $model->sort ?>">
                    <div>升序，数字越小排的越靠前</div>
                </div>
            </div>
            <?php if ($model->review_status == 0) : ?>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">审核状态</label>
                    </div>
                    <div class="col-sm-6">
                        <label class="radio-label">
                            <input type="radio" name="review_status" value="1">
                            <span class="label-icon"></span>
                            <span class="label-text">审核通过</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="review_status" value="2">
                            <span class="label-icon"></span>
                            <span class="label-text">审核不通过</span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">审核结果</label>
                    </div>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="review_result"><?= $model->review_result ?></textarea>
                    </div>
                </div>
            <?php else : ?>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">审核状态</label>
                    </div>
                    <div class="col-sm-6 form-group-text">
                        <?php if ($model->review_status == 0) : ?>
                            <span class="text-muted">待审核</span>
                        <?php elseif ($model->review_status == 1) : ?>
                            <span class="text-success">审核通过</span>
                        <?php elseif ($model->review_status == 2) : ?>
                            <span class="text-danger">审核未通过</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">审核结果</label>
                    </div>
                    <div class="col-sm-6 form-group-text"><?= $model->review_result ?></div>
                </div>
            <?php endif; ?>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    <input type="button" class="btn btn-default ml-4" 
                           name="Submit" onclick="javascript:history.back(-1);" value="返回">
                </div>
            </div>
        </form>

        <!-- Search User Modal -->
        <div class="modal fade" id="searchUserModal" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">查找用户</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="search-user-form">
                            <div class="input-group mb-3">
                                <input class="form-control">
                                <span class="input-group-btn">
                                    <button class="btn btn-secondary">查找</button>
                                </span>
                            </div>
                            <div>
                                <template v-if="user_list && user_list.length">
                                    <div v-for="(u,i) in user_list" class="user-item"
                                         flex="dir:left box:last cross:center">
                                        <div>
                                            <img :src="u.avatar_url"
                                                 style="width: 1.5rem;height: 1.5rem;border-radius: .15rem;">
                                            <span>{{u.nickname}}</span>
                                        </div>
                                        <div>
                                            <a class="btn btn-sm btn-secondary select-user" href="javascript:"
                                               :data-index="i">选择</a>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            user_list: [],
        },
    });
    $(document).on('click', '.picker-district', function () {
        $.districtPicker({
            success: function (res) {
                $('input[name=province_id]').val(res.province_id);
                $('input[name=city_id]').val(res.city_id);
                $('input[name=district_id]').val(res.district_id);
                $('.district-text').val(res.province_name + "-" + res.city_name + "-" + res.district_name);
            },
            error: function (e) {
                console.log(e);
            }
        });
    });

    $(document).on('submit', '.search-user-form', function () {
        var form = $(this);
        var btn = form.find('button');

        btn.btnLoading();
        $.ajax({
            url: '<?=Yii::$app->urlManager->createUrl(['mch/mch/index/add'])?>',
            dataType: 'json',
            data: {
                keyword: form.find('input').val(),
            },
            success: function (res) {
                if (res.code == 0) {
                    app.user_list = res.data;
                }
            },
            complete: function () {
                btn.btnReset();
            }
        });
        return false;
    });

    $(document).on('click', '.select-user', function () {
        var index = $(this).attr('data-index');
        var user = app.user_list[index];
        $('.user-id').val(user.id);
        $('.user-nickname').val(user.nickname);
        $('#searchUserModal').modal('hide');
    });

</script>