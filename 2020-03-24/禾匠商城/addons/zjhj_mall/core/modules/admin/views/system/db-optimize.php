<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */
defined('YII_ENV') or exit('Access Denied');
$this->title = '数据库优化';
?>

<style>
    .optimize-item {
        border-bottom: 1px solid #e3e3e3;
        margin-bottom: 1rem;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="optimize-item">
            <a href="javascript:" class="btn btn-primary mb-3 optimize-btn" data-action="optimize_engine">数据表引擎优化</a>
            <p>优化数据表引擎为InnoDB</p>
        </div>
        <div class="optimize-item">
            <a href="javascript:" class="btn btn-primary mb-3 optimize-btn" data-action="optimize_charset">字符集优化</a>
            <p>将数据表字符集转成utf8mb4</p>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.optimize-btn', function () {
        var btn = $(this);
        var action = btn.attr('data-action');
        btn.btnLoading();
        $.ajax({
            type: 'post',
            data: {
                _csrf: _csrf,
                action: action,
            },
            dataType: 'json',
            success: function (res) {
                $.alert({
                    content: res.msg,
                });
            },
            complete: function () {
                btn.btnReset();
            },
        });
    });
</script>