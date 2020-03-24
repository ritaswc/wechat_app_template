<?php
defined('YII_ENV') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '客户评价编辑';
$this->params['active_nav_group'] = 8;

use yii\widgets\ActiveForm;
use \app\models\Option;
?> 
<!-- 
<link href="<?= Yii::$app->request->baseUrl ?>/statics/mch/css/bootstrap-combined.min.css" rel="stylesheet">
<link href="<?= Yii::$app->request->baseUrl ?>/statics/mch/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/bootstrap-datetimepicker.js"></script>     -->

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" return="<?= $urlManager->createUrl(['mch/comment/index']) ?>">
            <div class="form-group row" style="<?php if (!$model['id']) {
                echo 'display:none';
                                               } else {
    echo 'display:display';
} ?>"" >
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">ID</label>
                </div>
                <div class="col-sm-6">
                 <div class="col-form-label required"><?= $model['id'] ?></div>
             </div>
        </div> 

        <div class="form-group row">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label ">用户名</label>
            </div>
            <div class="col-sm-6">
               <input class="form-control" name="virtual_user" value="<?= $model['virtual_user'] ?>">
            </div>
        </div>

        <div class="form-group row">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label required">评价时间</label>
            </div>
            <div class="col-sm-6">
            <div>
              <!-- <div id="datetimepicker1" class="input-append date"> -->
               <!--  <input style="height:25px" name="addtime" value="<?= $model['addtime'] ?>" data-format="yyyy/MM/dd hh:mm:ss" type="text"></input> -->

                            <input class="form-control"
                                   id="addtime"
                                   name="addtime"
                                   value="<?= $model['addtime'] ?>">


<!--                 <span class="add-on" style="height:25px">
                  <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                  </i>
                </span> -->
                <div class="text-muted fs-sm">不输入,则显示当前时间</div>
              </div>
            </div>
        </div> 
        <div class="form-group row">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label">用户头像</label>
            </div>
            <div class="col-sm-6">
                <div class="upload-group">
                    <div class="input-group">
                        <input class="form-control file-input" name="virtual_avatar"
                        value="<?= $model['virtual_avatar'] ?>">
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
                        <img class="upload-preview-img" src="<?= $model['virtual_avatar'] ?>">
                    </div>
                </div>
            </div>
        </div>
 
        <div class="form-group row">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label required">商品</label>
            </div>
            <div class="col-sm-6">
                <div class="input-group">             
                    <input class="form-control search-goods-name" value="<?= $model['name']?>" readonly>
                    <input class="search-goods-id" type="hidden" value="<?= $model['goods_id']?>" name="goods_id">
                    <span class="input-group-btn">
                        <a href="javascript:" class="btn btn-secondary search-goods" data-toggle="modal" data-target="#searchGoodsModal">选择商品</a>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label required">评价</label>
            </div>
            <div class="col-sm-6">
                <textarea class="form-control file-input" name="content" rows="3"><?= $model['content'] ?></textarea>
            </div>
        </div> 

        <div class="form-group row">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label">评价图片</label>
            </div>
            <div class="col-sm-6">

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
                    <div class="upload-preview-list">
                        <?php if (count(json_decode($model['pic_list'])) > 0) : ?>
                            <?php foreach (json_decode($model['pic_list']) as $item) : ?>
                                <div class="upload-preview text-center">
                                    <input type="hidden" class="file-item-input"
                                    name="pic_list[]"
                                    value="<?= $item ?>">
                                    <span class="file-item-delete">&times;</span>
                                    <span class="upload-preview-tip">750&times;750</span>
                                    <img class="upload-preview-img" src="<?= $item ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="upload-preview text-center">
                                <input type="hidden" class="file-item-input" name="pic_list[]">
                                <span class="file-item-delete">&times;</span>
                                <span class="upload-preview-tip">750&times;750</span>
                                <img class="upload-preview-img" src="">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>


    <div class="form-group row">
        <div class="form-group-label col-sm-2 text-right">
            <label class="col-form-label required">评分</label>
        </div>
        <div class="col-sm-6">
            <label class="radio-label">
                <input <?= $model['score'] == 1 ? 'checked' : null ?>
                value="1"
                name="score" type="radio" class="custom-control-input">
                <span class="label-icon"></span>
                <span class="label-text">差评</span>
            </label>
            <label class="radio-label">
                <input <?= $model['score'] == 2 ? 'checked' : null ?>
                value="2"
                name="score" type="radio" class="custom-control-input">
                <span class="label-icon"></span>
                <span class="label-text">中评</span>
            </label>
            <label class="radio-label">
                <input <?= $model['score'] == 3 ? 'checked' : null ?>
                value="3"
                name="score" type="radio" class="custom-control-input">
                <span class="label-icon"></span>
                <span class="label-text">好评</span>
            </label>
        </div>
    </div>

    <div class="form-group row">
        <div class="form-group-label col-sm-2 text-right">
            <label class="col-form-label required">是否隐藏</label>
        </div>
        <div class="col-sm-6">

            <label class="radio-label">
                <input <?= $model['is_hide'] == 0 ? 'checked' : null ?>
                value="0"
                name="is_hide" type="radio" class="custom-control-input">
                <span class="label-icon"></span>
                <span class="label-text">显示</span>
            </label>
            <label class="radio-label">
                <input <?= $model['is_hide'] == 1 ? 'checked' : null ?>
                value="1"
                name="is_hide" type="radio" class="custom-control-input">
                <span class="label-icon"></span>
                <span class="label-text">隐藏</span>
            </label>
        </div>
    </div>

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



        <!-- Modal -->
    <div class="modal fade" data-backdrop="static" id="searchGoodsModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div id="app" class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">查找商品</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= $urlManager->createUrl(['mch/comment/search-goods']) ?>"
                          class="input-group  goods-search-form" method="get">
                        <input name="keyword" class="form-control" placeholder="商品名称">
                        <span class="input-group-btn">
                    <button class="btn btn-secondary submit-btn">查找</button>
                </span>
                    </form>
                    <div v-if="goodsList==null" class="text-muted text-center p-5">请输入商品名称查找商品</div>
                    <template v-else>
                        <div v-if="goodsList.length==0" class="text-muted text-center p-5">未查找到相关商品</div>
                        <template v-else>
                            <div class="goods-item row mt-3 mb-3" v-for="(item,index) in goodsList">
                                <div class="col-8">
                                    <div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis">
                                        {{item.name}}
                                    </div>
                                </div>
                                <div class="col-2 text-right">￥{{item.price}}</div>
                                <div class="col-2 text-right">
                                    <a href="javascript:" class="goods-select" v-bind:index="index">选择</a>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            goodsList: null,
        }
    });

    $(document).on("click", ".goods-select", function () {
        var index = $(this).attr("index");
        var goods = app.goodsList[index];
        $("#searchGoodsModal").modal("hide");
        $(".search-goods-name").val(goods.name);
        $(".search-goods-id").val(goods.id);
        for (var i in goods.attr) {
            goods.attr[i].miaosha_price = parseFloat(goods.attr[i].price == 0 ? goods.price : goods.attr[i].price);
            goods.attr[i].miaosha_num = goods.attr[i].num;
            goods.attr[i].sell_num = 0;
        }
        app.goods = goods;
    });

    $(document).on("submit", ".goods-search-form", function () {
        var form = $(this);
        var btn = form.find(".submit-btn");
        btn.btnLoading("正在查找");
        $.ajax({
            url: form.attr("action"),
            type: "get",
            dataType: "json",
            data: form.serialize(),
            success: function (res) {
                btn.btnReset();
                if (res.code == 0) {
                    app.goodsList = res.data.list;
                }
            }
        });
        return false;
    }); 
</script>
<script type="text/javascript">
  $(function() {
    $('#datetimepicker1').datetimepicker({
      collapse: false
    });
  });

      (function () {
        $.datetimepicker.setLocale('zh');
        $('#addtime').datetimepicker({
            format: 'Y-m-d H:i:s',
            timepicker:true,
            onShow: function (ct) {
                this.setOptions({
                    maxDate: $('#addtime').val() ? $('#addtime').val() : false
                })
            },
        });
    })();
</script>