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
$this->title = '微信配置';
?>
<div class="panel mb-3">
    <div class="panel-header">基础面板</div>
    <div class="panel-body">
        <div>基础面板</div>
        <div>据通告，含“马来酸氯苯那敏”的药品批文有2112个。而且，都是用量非常大的药品，如小儿氨酚黄那敏、美敏伪麻口服溶液、维C银翘片、咳特灵胶囊、鼻炎片等。</div>
    </div>
    <div class="panel-footer">结尾</div>
</div>

<div class="panel mb-3">
    <div class="panel-header">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="#">Active</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <div>带导航的面板1</div>
        <div>据通告，含“马来酸氯苯那敏”的药品批文有2112个。而且，都是用量非常大的药品，如小儿氨酚黄那敏、美敏伪麻口服溶液、维C银翘片、咳特灵胶囊、鼻炎片等。</div>
    </div>
</div>

<div class="panel mb-3">
    <div class="panel-header">
        <span>带导航的面板2</span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link active" href="#">Active</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">带导航的面板2</div>
</div>


<div class="panel mb-3">
    <div class="panel-header">表单<?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">表单项(必填)</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">表单项</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">表单项(单选)</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input type="radio" name="radio1">
                        <span class="label-icon"></span>
                        <span class="label-text">选项A</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="radio1">
                        <span class="label-icon"></span>
                        <span class="label-text">选项B</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">表单项(多选)</label>
                </div>
                <div class="col-sm-6">
                    <label class="checkbox-label">
                        <input type="checkbox" name="checkbox1[]">
                        <span class="label-icon"></span>
                        <span class="label-text">选项A</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="checkbox1[]">
                        <span class="label-icon"></span>
                        <span class="label-text">选项A</span>
                    </label>
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

<div class="panel mb-3">
    <div class="panel-header">表单2</div>
    <div class="panel-body">

        <form class="auto-form" method="get">
            <p>提示：自动表单<code>form</code>标签支持<code>return</code>属性，表单返回结果为成功时自动跳转到return的网址</p>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">表单项(必填)</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">表单项</label>
                </div>
                <div class="col-sm-6 form-inline">
                    <input class="form-control mr-3">
                    <a href="javascript:" class="btn btn-secondary">查找</a>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">表单项</label>
                </div>
                <div class="col-sm-6 form-inline">
                    <div class="input-group">
                        <input class="form-control">
                        <span class="input-group-btn">
                            <a href="javascript:" class="btn btn-secondary">查找</a>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">表单项(单选)</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input type="radio" name="radio1">
                        <span class="label-icon"></span>
                        <span class="label-text">选项A</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="radio1">
                        <span class="label-icon"></span>
                        <span class="label-text">选项B</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="radio1">
                        <span class="label-icon"></span>
                        <span class="label-text">选项C</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">表单项(多选)</label>
                </div>
                <div class="col-sm-6">
                    <label class="checkbox-label">
                        <input type="checkbox" name="checkbox1[]">
                        <span class="label-icon"></span>
                        <span class="label-text">选项A</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="checkbox1[]">
                        <span class="label-icon"></span>
                        <span class="label-text">选项A</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="checkbox1[]">
                        <span class="label-icon"></span>
                        <span class="label-text">选项A</span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">开关</label>
                </div>
                <div class="col-sm-6">
                    <label class="switch-label">
                        <input type="checkbox" name="checkbox2">
                        <span class="label-icon"></span>
                        <span class="label-text">checkbox开关</span>
                    </label>
                    <label class="switch-label">
                        <input type="radio" name="radio2">
                        <span class="label-icon"></span>
                        <span class="label-text">radio开关</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">下拉选择</label>
                </div>
                <div class="col-sm-6">
                    <select class="form-control">
                        <optgroup label="ga">
                            <option>A</option>
                            <option>B</option>
                        </optgroup>
                        <optgroup label="ga">
                            <option>1</option>
                            <option>2</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">复选</label>
                </div>
                <div class="col-sm-6">
                    <select class="form-control" multiple>
                        <optgroup label="ga">
                            <option>A</option>
                            <option>B</option>
                        </optgroup>
                        <optgroup label="ga">
                            <option>1</option>
                            <option>2</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">日期时间1</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control" id="datepicker1">
                        <span class="input-group-btn">
                            <a class="btn btn-secondary" href="javascript:">
                                <span class="iconfont icon-daterange"></span>
                            </a>
                        </span>
                    </div>
                    <div class="text-muted fs-sm">具体使用方式详见：https://xdsoft.net/jqplugins/datetimepicker/</div>
                    <script>
                        jQuery.datetimepicker.setLocale('zh');
                        jQuery('#datepicker1').datetimepicker({
                            datepicker: true,
                            timepicker: false,
                            format: 'Y-m-d',
                            dayOfWeekStart: 1,
                            scrollMonth: false,
                            scrollTime: false,
                            scrollInput: false,
                        });
                    </script>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">日期时间2</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control" id="datepicker2">
                        <span class="input-group-btn">
                            <a class="btn btn-secondary" id="show_datepicker2" href="javascript:">
                                <span class="iconfont icon-accesstime"></span>
                            </a>
                        </span>
                    </div>
                    <div class="text-muted fs-sm">具体使用方式详见：https://xdsoft.net/jqplugins/datetimepicker/</div>
                    <script>
                        jQuery.datetimepicker.setLocale('zh');
                        jQuery('#datepicker2').datetimepicker({
                            datepicker: false,
                            timepicker: true,
                            format: 'H:i',
                            step: 10,
                            scrollMonth: false,
                            scrollTime: false,
                            scrollInput: false,
                        });
                        $(document).on('click', '#show_datepicker2', function () {
                            $('#datepicker2').datetimepicker('show');
                        });
                    </script>
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">上传1(基础演示)</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control" id="file_input">
                        <span class="input-group-btn">
                            <a class="btn btn-secondary" id="upload_file" href="javascript:" data-toggle="tooltip"
                               data-placement="bottom" title="上传文件">
                                <span class="iconfont icon-cloudupload"></span>
                            </a>
                        </span>
                        <span class="input-group-btn">
                            <a class="btn btn-secondary" id="select_file" href="javascript:" data-toggle="tooltip"
                               data-placement="bottom" title="从文件库选择">
                                <span class="iconfont icon-viewmodule"></span>
                            </a>
                        </span>
                        <span class="input-group-btn">
                            <a class="btn btn-secondary" id="delete_file" href="javascript:" data-toggle="tooltip"
                               data-placement="bottom" title="删除文件">
                                <span class="iconfont icon-close"></span>
                            </a>
                        </span>
                    </div>
                    <div class="upload-preview text-center" id="upload_preview">
                        <span class="upload-preview-tip">100&times;100</span>
                        <img class="upload-preview-img" src="">
                    </div>
                    <script>
                        $(document).on('click', '#upload_file', function () {
                            var res_input = $(this).parents('.input-group').find('#file_input');
                            //var res_preview = $(this).parents('.input-group').find('.form-control');
                            $.upload_file({
                                accept: 'image/*',
                                start: function () {
                                },
                                progress: function (e) {
                                },
                                success: function (res) {
                                    btn.btnReset();
                                    if (res.code === 1) {
                                        $.alert({
                                            content: res.msg
                                        });
                                        return;
                                    }
                                    res_input.val(res.data.url).trigger('change');
                                },
                                error: function (res) {
                                    console.log('----upload error----');
                                    console.log(res);
                                },
                                complete: function (res) {
                                    console.log('----upload complete----');
                                },
                            });
                        });
                        $(document).on('click', '#select_file', function () {
                            var res_input = $(this).parents('.input-group').find('#file_input');
                            $.select_file({
                                success: function (res) {
                                    res_input.val(res.url).trigger('change');
                                }
                            });
                        });
                        $(document).on('click', '#delete_file', function () {
                            var res_input = $(this).parents('.input-group').find('#file_input');
                            res_input.val('').trigger('change');
                        });
                        $(document).on('change', '#file_input', function () {
                            $('#upload_preview .upload-preview-img').attr('src', $(this).val());
                        });
                    </script>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">上传2(上传单图)</label>
                </div>
                <div class="col-sm-6">

                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input">
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
                            <img class="upload-preview-img" src="">
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">上传3(多图)</label>
                </div>
                <div class="col-sm-6">

                    <div class="upload-group multiple">
                        <div class="input-group">
                            <input class="form-control file-input" readonly>
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
                        </div>
                        <div class="upload-preview-list">
                            <div class="upload-preview text-center">
                                <input type="hidden" class="file-item-input">
                                <span class="file-item-delete">&times;</span>
                                <span class="upload-preview-tip">100&times;100</span>
                                <img class="upload-preview-img" src="">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">地区选择</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control" readonly>
                        <span class="input-group-btn"><a class="btn btn-secondary" id="pickdiatrict" href="javascript:">选择</a></span>
                    </div>
                </div>
            </div>
            <script>
                $(document).on('click', '#pickdiatrict', function () {
                    $.districtPicker({
                        success: function (res) {
                            console.log(res);
                        },
                        error: function (e) {
                            console.log(e);
                        },
                    });
                });
            </script>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary submit-btn" href="javascript:">保存</a>
                </div>
            </div>

        </form>

    </div>
</div>
