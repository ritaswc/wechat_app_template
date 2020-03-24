<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/28
 * Time: 10:18
 */

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '地区管理';

$provinceid = \Yii::$app->request->get('province');

?>
<style>
    .visible-hide{
        display: none;
    }
    #province{
        width: 200px;
    }
</style>
<nav class="breadcrumb rounded-0">
    <span class="breadcrumb-item active"><?= $this->title ?></span>
</nav>
<div class="p-3">
    <a href="<?= $urlManager->createUrl(['mch/express/express']) ?>">收费地区管理</a>
    <hr>
    <div class="p-3">
        <form class="form-inline formser" method="get">
            <div class="form-group mr-4">
                <div class="input-group">
                    <span class="input-group-addon">省份</span>
                    <select name="province" id="province">
                        <option value="0">选择省</option>
                        <?php foreach ($province as $c => $pro) : ?>
                            <option <?=$pro['id']==$provinceid?"selected":"" ?> value="<?= $pro['id'] ?>"><?= $pro['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary">搜索</button>
            </div>
        </form>
        <hr>
        <a href="<?= $urlManager->createUrl(['mch/express/express-add']) ?>" class="btn btn-primary">添加收费地区</a>
    </div>
    <div class="card">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>省</th>
                    <th>市</th>
                    <th>收费金额</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $index => $value) : ?>
                    <tr>
                        <td><?= $value['province_name'] ?></td>
                        <td><?= $value['name'] ?></td>
                        <td><?= $value['postage'] ?></td>
                        <td>
                            <a data-toggle="modal" data-target="#replyModal-<?=$value['id']?>" href="<?= $urlManager->createUrl(['mch/good/good-class-edit', 'id' => $value['id']]) ?>">[设置邮费]</a>
                            <a class="del" href="<?= $urlManager->createUrl(['mch/express/express-del', 'id' => $value['id']]) ?>">[取消收费]</a>

                            <div class="modal fade" id="replyModal-<?=$value['id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form method="post" class="saveForm">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div clas="form-group">
                                                    <label class="col-form-label">设置 &nbsp;&nbsp;&nbsp;&nbsp;<?=$value['province_name']?>/<?= $value['name'] ?>&nbsp;&nbsp;&nbsp;&nbsp;金额</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" name="postage" min="0" value="<?= $value['postage'] ?>">
                                                        <span class="input-group-addon">元</span>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="province" value="<?=$value['parent_id']?>">
                                                <input type="hidden" name="city" value="<?=$value['id']?>">

                                                <div class="form-error  text-danger text-left visible-hide">错误信息</div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">返回</button>
                                                <a class="btn btn-success reply-btn" data="" href="javascript:;">修改</a>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </td>
                    </tr>
                <?php endforeach; ?>
           </tbody>


        </table>
        <nav aria-label="Page navigation example">
            <?php echo LinkPager::widget([
                'pagination'=>$pagination,
                'prevPageLabel'=>'上一页',
                'nextPageLabel'=>'下一页',
                'firstPageLabel'=>'首页',
                'lastPageLabel'=>'尾页',
                'maxButtonCount' => 5,
                'options' =>[
                    'class'=>'pagination',
                ],
                'prevPageCssClass'=>'page-item',
                'pageCssClass'=>"page-item",
                'nextPageCssClass'=>'page-item',
                'firstPageCssClass'=>'page-item',
                'lastPageCssClass'=>'page-item',
                'linkOptions'=>[
                    'class'=>'page-link',
                ],
                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
            ])
?>
        </nav>
    </div>
</div>

<script>
    // 设置收费金额
    $(document).on('click','.reply-btn',function(){
        var btn  = $(this);
        var form  =btn.parents('.saveForm');
        $.ajax({
            method:'post',
            dataType:'json',
            url:'<?= $urlManager->createUrl(['mch/express/express-add']) ?>',
            data:form.serialize()+'&_csrf='+_csrf,
            success:function(res){
                if (res.code==0) {
                    window.location.href="<?= $urlManager->createUrl(['mch/express/express']) ?>";
                }else{
                    $('.form-error').text(res.msg).removeClass('visible-hide');
                }
            }
        });
    });
//取消收费
    $(document).on('click', '.del', function () {
        if (confirm("是否取消收费？")) {
            $.ajax({
                url: $(this).attr('href'),
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    alert(res.msg);
                    if (res.code == 0) {
                        window.location.reload();
                    }
                }
            });
        }
        return false;
    });
</script>