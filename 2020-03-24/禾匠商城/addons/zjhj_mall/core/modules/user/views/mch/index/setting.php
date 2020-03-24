<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Cje
 */
defined('YII_ENV') or exit('Access Denied');
$this->title = '店铺设置';
/** @var \app\models\Mch $model
 * @var \app\models\MchCommonCat[] $mch_common_cat_list
 */
$urlManager = Yii::$app->urlManager;
?>
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=OV7BZ-ZT3HP-6W3DE-LKHM3-RSYRV-ULFZV"></script>

<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
    </div>
    <div class="panel-body">
        <form class="auto-form" method="post" style="display: inline-block;width: 50%;">
            <div class="form-group row">
                <div class="col-sm-2 form-group-label text-right">
                    <label class="col-form-label">基本信息</label>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">联系人</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="model[realname]" value="<?= $model->realname ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">联系电话</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="model[tel]" value="<?= $model->tel ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">微信号</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="model[wechat_name]" value="<?= $model->wechat_name ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2 form-group-label text-right">
                    <label class="col-form-label">店铺信息</label>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">店铺名称</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="model[name]" value="<?= $model->name ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">所在地区</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input type="hidden" name="model[province_id]" value="<?= $model->province_id ?>">
                        <input type="hidden" name="model[city_id]" value="<?= $model->city_id ?>">
                        <input type="hidden" name="model[district_id]" value="<?= $model->district_id ?>">
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
                    <input class="form-control" name="model[address]" value="<?= $model->address ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">主营内容</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="model[main_content]" value="<?= $model->main_content ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">店铺简介</label>
                </div>
                <div class="col-sm-6">
                    <textarea class="form-control" name="model[summary]"><?= $model->summary ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">经度</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="model[longitude]" value="<?= $model->longitude ?>">
                    <div class="fs-sm">经纬度可以在地图上选择，也可以自己添加</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">纬度</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="model[latitude]" value="<?= $model->latitude ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">所售类目</label>
                </div>
                <div class="col-sm-6">
                    <select class="form-control" name="model[mch_common_cat_id]">
                        <?php foreach ($mch_common_cat_list as $item) : ?>
                            <option value="<?= $item->id ?>"
                                <?= $item->id == $model->mch_common_cat_id ? 'selected' : null ?>><?= $item->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">客服电话</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="model[service_tel]" value="<?= $model->service_tel ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">店铺头像</label>
                </div>
                <div class="col-sm-6">
                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input" name="model[logo]" value="<?= $model->logo ?>">
                            <span class="input-group-btn">
                                <a class="btn btn-secondary upload-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="上传文件">
                                    <span class="iconfont icon-cloudupload"></span>
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
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">店铺背景（顶部）</label>
                </div>
                <div class="col-sm-6">
                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input" name="model[header_bg]"
                                   value="<?= $model->header_bg ?>">
                            <span class="input-group-btn">
                                <a class="btn btn-secondary upload-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="上传文件">
                                    <span class="iconfont icon-cloudupload"></span>
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
                    <label class="col-form-label">手续费(千分之)</label>
                </div>
                <div class="col-sm-6">
                    <input type="number" min="0" max="1000" step="1" class="form-control" readonly
                           value="<?= $model->transfer_rate ?>">
                    <div>商户每笔订单交易金额扣除的手续费</div>
                </div>
            </div>
            <?php if ($plugin): ?>
                <?php if ($plugin->is_share == 1): ?>
                    <div class="form-group row">
                        <div class="form-group-label col-3 text-right">
                            <label class=" col-form-label">是否开启分销</label>
                        </div>
                        <div class="col-9 col-form-label">
                            <label class="radio-label">
                                <input <?= $setting->is_share == 0 ? 'checked' : null ?>
                                    value="0" name="model[is_share]" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">关闭</span>
                            </label>
                            <label class="radio-label">
                                <input <?= $setting->is_share == 1 ? 'checked' : null ?>
                                    value="1" name="model[is_share]" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">开启</span>
                            </label>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
        <div style="display: inline-block;vertical-align: top;width: 45%">
            <div class="form-group row map">
                <div class="offset-2 col-9">
                    <div class="input-group" style="margin-top: 20px;">
                        <input class="form-control region" type="text" placeholder="城市">
                        <span class="input-group-addon ">和</span>
                        <input class="form-control keyword" type="text" placeholder="关键字">
                        <a class="input-group-addon search" href="javascript:">搜索</a>
                    </div>
                    <div class="text-info">搜索时城市和关键字必填</div>
                    <div class="text-info">点击地图上的蓝色点，获取经纬度</div>
                    <div class="text-danger map-error mb-3" style="display: none">错误信息</div>
                    <div id="container" style="min-width:600px;min-height:600px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
</script>
<script>

    var searchService, map, markers = [];
    //        window.onload = function(){
    //直接加载地图
    //初始化地图函数  自定义函数名init
    function init() {
        //定义map变量 调用 qq.maps.Map() 构造函数   获取地图显示容器
        var map = new qq.maps.Map(document.getElementById("container"), {
            center: new qq.maps.LatLng(39.916527, 116.397128),      // 地图的中心地理坐标。
            zoom: 15                                                 // 地图的中心地理坐标。
        });
        var latlngBounds = new qq.maps.LatLngBounds();
        //调用Poi检索类
        searchService = new qq.maps.SearchService({
            complete: function (results) {
                var pois = results.detail.pois;
                $('.map-error').hide();
                if (!pois) {
                    $('.map-error').show().html('关键字搜索不到，请重新输入');
                    return;
                }
                for (var i = 0, l = pois.length; i < l; i++) {
                    (function (n) {
                        var poi = pois[n];
                        latlngBounds.extend(poi.latLng);
                        var marker = new qq.maps.Marker({
                            map: map,
                            position: poi.latLng,
                        });

                        marker.setTitle(n + 1);

                        markers.push(marker);
                        //添加监听事件
                        qq.maps.event.addListener(marker, 'click', function (e) {
                            var address = poi.address;
                            console.log(e)
                            $("input[name='model[longitude]']").val(e.latLng.lng);
                            $("input[name='model[latitude]']").val(e.latLng.lat);
                        });
                    })(i);
                }
                map.fitBounds(latlngBounds);
            }
        });
    }
    //清除地图上的marker
    function clearOverlays(overlays) {
        var overlay;
        while (overlay = overlays.pop()) {
            overlay.setMap(null);
        }
    }
    function searchKeyword() {
        var keyword = $(".keyword").val();
        var region = $(".region").val();
        clearOverlays(markers);
        searchService.setLocation(region);
        searchService.search(keyword);
    }

    //调用初始化函数地图
    init();


    //        }
</script>
<script>
    $(document).on('click', '.search', function () {
        searchKeyword();
    })
</script>