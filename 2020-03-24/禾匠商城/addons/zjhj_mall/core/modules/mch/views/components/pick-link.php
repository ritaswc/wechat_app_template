<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/13
 * Time: 9:44
 */

$urlManager = Yii::$app->urlManager;

?>
<style>
    .label-help {
        display: inline-block;
        font-size: .65rem;
        background: #555;
        color: #fff;
        border-radius: 999px;
        width: 1rem;
        height: 1rem;
        line-height: 1rem;
        text-align: center;
        text-decoration: none;
        margin-left: .25rem;
    }

    .label-help:hover,
    .label-help:visited {
        color: #fff;
        text-decoration: none;
    }
</style>
<div id="pick_link_modal">
    <div class="modal fade pick-link-modal" data-backdrop="static" style="z-index: 99999">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">选择链接</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <span class="input-group-addon">可选链接</span>
                        <select class="form-control link-list-select">
                            <option value="">点击选择链接</option>
                            <option v-for="(link,i) in link_list"
                                    v-if="in_array(link.open_type,open_type) != -1"
                                    v-bind:value="i">
                                {{link.name}}
                            </option>
                        </select>
                    </div>
                    <template v-if="selected_link">
                        <template v-if="selected_link.params && selected_link.params.length>0">
                            <div class="form-group row" v-for="(param,i) in selected_link.params">
                                <label class="col-sm-2 text-right col-form-label" :class="param.required">{{param.key}}</label>
                                <div class="col-sm-10">
                                    <input class="form-control paramVal" v-model="param.value">
                                    <div class="fs-sm text-muted" v-if="param.desc">{{param.desc}}</div>
                                </div>
                            </div>
                            <div class="fs-sm text-muted" v-if="is_required"><p style="color: red; text-align: center;">请填写标记 * 参数</p></div>
                        </template>
                        <div v-else class="text-center text-muted">此页面无需配置参数</div>
                    </template>
                    <div v-else class="text-center text-muted">请选择链接</div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary pick-link-confirm" href="javascript:">确定</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    String.prototype._trim = function (char, type) {
        if (char) {
            if (type == 'left') {
                return this.replace(new RegExp('^\\' + char + '+', 'g'), '');
            } else if (type == 'right') {
                return this.replace(new RegExp('\\' + char + '+$', 'g'), '');
            }
            return this.replace(new RegExp('^\\' + char + '+|\\' + char + '+$', 'g'), '');
        }
        return this.replace(/^\s+|\s+$/g, '');
    };

    var pick_link_modal;
    $(document).ready(function () {
        var pick_link_btn;

        pick_link_modal = new Vue({
            el: "#pick_link_modal",
            data: {
                in_array: function (val, arr) {
                    return $.inArray(val, arr);
                },
                open_type: [],
                selected_link: null,
                link_list: null,
                is_required: false
            }
        });

        $(document).on("change", ".link-list-select", function () {
            var i = $(this).val();
            var arr = pick_link_modal.link_list[i].params
            if(arr.length) {
                if (arr[0].required == 'required') {
                    pick_link_modal.is_required = true
                }else  {
                    pick_link_modal.is_required = false
                }
            }

            if (i == "") {
                pick_link_modal.selected_link = null;
                return;
            }
            pick_link_modal.selected_link = pick_link_modal.link_list[i];
        });

        $(document).on("click", ".pick-link-btn", function () {
            pick_link_btn = $(this);
            var open_type = $(this).attr("open-type");
            if (open_type && open_type != "") {
                open_type = open_type.split(",");
            } else {
                open_type = ["navigate", "switchTab", "wxapp"];
            }
            $(".link-list-select option:first").prop("selected",'selected');
            pick_link_modal.selected_link = null;
            pick_link_modal.open_type = open_type;
            $.ajax({
                url: '<?= $urlManager->createUrl('mch/store/pick-link') ?>',
                method: 'get',
                dataType: 'json',
                success: function (res) {
                    pick_link_modal.link_list = res;
                    $(".pick-link-modal").modal("show");
                }
            })
        });

        $(document).on("click", ".pick-link-confirm", function () {
            if ($('.paramVal').val() == '' && pick_link_modal.is_required) {
                console.log($('.paramVal').val())
                return;
            }

            var selected_link = pick_link_modal.selected_link;
            if (!selected_link) {
                $(".pick-link-modal").modal("hide");
                return;
            }
            var link_input = pick_link_btn.parents(".page-link-input").find(".link-input");
            var open_type_input = pick_link_btn.parents(".page-link-input").find(".link-open-type");
            var open_type_name = pick_link_btn.parents(".page-link-input").find(".link-name");
            var params = "";
            if (selected_link.params && selected_link.params.length > 0) {
                for (var i in selected_link.params) {
                    params += selected_link.params[i].key + "=" + encodeURIComponent(selected_link.params[i].value) + "&";
                }
            }
            var link = selected_link.link;
            link += "?" + params;
            link = link._trim("&");
            link = link._trim("?");
            link_input.val(link).change();
            open_type_input.val(selected_link.open_type).change();
            open_type_name.val(selected_link.name).change();
            $(".pick-link-modal").modal("hide");


        });

    });
</script>