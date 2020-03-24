<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/3
 * Time: 16:48
 */
$urlManager = Yii::$app->urlManager;
?>
<div class="modal fade" data-backdrop="static" id="removal_modal">
    <div class="modal-dialog modal-sm" role="document" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <b class="modal-title">选择账户</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div>需要迁移的商城：<span class="text-danger">{{store_name}}</span></div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control keyword">
                        <a class="input-group-addon select-user" href="javascript:">搜索</a>
                    </div>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <td>账户ID</td>
                        <td>账户</td>
                        <td>操作</td>
                    </tr>
                    <tr v-if="list.length>0" v-for="(item,index) in list">
                        <td>{{item.id}}</td>
                        <td>{{item.username}}</td>
                        <td>
                            <a href="javascript:" class="removal" :data-id="item.id">迁移</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var removalModal = new Vue({
        el: "#removal_modal",
        data: {
            list: [],
            store_id: '',
            store_name: ''
        }
    });

    $(document).on('click', '.removal-btn', function () {
        removalModal.store_id = $(this).data('id');
        removalModal.store_name = $(this).data('name');
        $('#removal_modal').modal('show');
        selectUser();
    });

    $(document).on('click', '.select-user', function () {
        var keyword = $('.keyword').val();
        selectUser(keyword);
    });

    function selectUser(keyword) {
        $.ajax({
            url: '<?=$urlManager->createUrl(['admin/app/other-user'])?>',
            dataType: 'json',
            type: 'get',
            data: {
                keyword: keyword
            },
            success: function (res) {
                if (res.code == 0) {
                    removalModal.list = res.data.list;
                }else{
                    $.myAlert({
                        content:res.msg,
                        confirm:function(){
                            location.href = "<?=$urlManager->createUrl(['admin/default/index'])?>";
                        }
                    });
                }
            }
        });
    }

    $(document).on('click', '.removal', function () {
        var store_id = removalModal.store_id;
        var user_id = $(this).data('id');
        $.ajax({
            url: "<?=$urlManager->createUrl(['admin/app/removal'])?>",
            type: 'get',
            dataType: 'json',
            data: {
                store_id: store_id,
                user_id: user_id
            },
            success: function (res) {
                if(res.code == 0){
                    $.myAlert({
                        content:'迁移成功',
                        confirm:function(){
                            location.reload();
                        }
                    });
                }else{
                    $.myAlert({
                        content:res.msg
                    });
                }
            }
        });
    });
</script>