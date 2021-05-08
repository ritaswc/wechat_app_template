<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/28
 * Time: 11:26
 */

$urlManager = Yii::$app->urlManager;
$this->title = '收费地区编辑';
?>
<nav class="breadcrumb rounded-0">
    <span class="breadcrumb-item active"><?= $this->title ?></span>
</nav>
<div class="p-3">
    <a href="<?= $urlManager->createUrl(['mch/express/express']) ?>">收费地区</a>

    <hr>
    <div class="p-3">
        <a href="<?= $urlManager->createUrl(['mch/express/express']) ?>" class="btn btn-primary">返回</a>
    </div>
    <form class="card auto-submit-form" method="post" autocomplete="off" style="max-width: 50rem">
        <div class="card-block">
            <div class="container-fluid">
                <div class="form-group row">
<!--                    <div class="input-group">-->
                        <label class="col-2 col-form-label">收费区域</label>
                        <span class="input-group-addon">省份</span>
                        <select name="province" id="province" class="form-control col-sm-3">
                            <?php foreach ($province as $c => $pro) : ?>
                                <option value="<?= $pro['id'] ?>"><?= $pro['name'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        <div class="col-sm-1"></div>

                        <span class="input-group-addon">市</span>
                        <select name="city" id="city" class="form-control col-sm-3">
                            <?php foreach ($city as $c => $ci) : ?>
                                <option value="<?= $ci['id'] ?>"><?= $ci['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
<!--                    </div>-->
                </div>

                <div class="form-group row">
                    <div class="input-group">
                        <label class="col-2 col-form-label">邮费</label>
                        <input type="number" class="form-control" name="postage" min="0" value="">
                        <span class="input-group-addon">元</span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-10 offset-sm-2">
                        <div class="text-danger form-error mb-3" style="display: none">错误信息</div>
                        <div class="text-success form-success mb-3" style="display: none">成功信息</div>
                        <a class="btn btn-primary submit-btn" href="javascript:">保存</a>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
<script>
    $(function(){
        $("#province").change(function (){
            var proid = $("#province").val();
            var url = "<?=$urlManager->createUrl(['mch/express/express-city'])?>";
            $.ajax({
                type:'get',
                url: url,
                dataType:'json',
                data:{proid:proid},
                success:function(res){
                    if(res.code == 0){
                        console.log(res.list);
                        console.log('aaaa');
                        // var data = eval("["+res['list']+"]");
                        var data = res.list;
                        var op = '';
                        for(i in data){
                            op+='<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
                            // console.log(data[i]['name']);
                        }
                        $('#city').html(op);
                    }else{
                        error.html(res.msg).prop('hidden',false);
                    }
                }
            });
        });
    })
</script>