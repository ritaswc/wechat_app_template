<template>
    <span>
        <div class="form-group row">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label">缩略图</label>
            </div>
            <div class="col-sm-6">
                <div class="upload-group">
                    <div class="input-group">

                        <input class="form-control file-input" name="image_url" v-model="form[tabIndex].image_url" value="123">
                        <span class="input-group-btn">
                            <a class="btn btn-secondary pond-select-file" href="javascript:" data-toggle="tooltip"
                            data-placement="bottom" title="上传文件">
                                <span class="iconfont icon-cloudupload"></span>
                            </a>
                        </span>
                        <span class="input-group-btn">
                            <a class="btn btn-secondary pond-upload-file" href="javascript:" data-toggle="tooltip"
                            data-placement="bottom" title="从文件库选择">
                                <span class="iconfont icon-viewmodule"></span>
                            </a>
                        </span>
                        <span class="input-group-btn">
                            <a class="btn btn-secondary pond-delete-file" href="javascript:" data-toggle="tooltip"
                            data-placement="bottom" title="删除文件">
                                <span class="iconfont icon-close"></span>
                            </a>
                        </span>
                    </div>
                    <div class="upload-preview text-center upload-preview">
                        <span class="upload-preview-tip">150&times;80</span>
                        <img class="upload-preview-img" :src="form[tabIndex].image_url">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row num" v-if="form[tabIndex].type==2">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label required">优惠卷</label>
            </div>
            <div class="col-sm-6">
                <select class="form-control parent" v-model="form[tabIndex].coupon_id">
                    <?php foreach ($coupon as $value) : ?>
                        <option
                        value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <a class="nav-link" target="_blank" href="<?= $urlManager->createUrl(['mch/coupon/edit']) ?>">新建</a>
        </div>
 
        <div class="form-group row" v-if="form[tabIndex].type==4">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label required">赠品</label>
            </div>
            <div class="col-sm-6">
                <div class="input-group">             
                    <input class="form-control search-goods-name" v-model="form[tabIndex].gift" readonly>
                    <span class="input-group-btn">
                        <a href="javascript:" class="btn btn-secondary search-goods" data-toggle="modal" data-target="#searchGoodsModal">选择商品</a>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row num" v-if="form[tabIndex].type==4">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label required">规格</label>
            </div>
            <div class="col-sm-6">
                <select class="form-control parent" v-model="form[tabIndex].attr">
                    <option v-for="(item,index) in attrs[tabIndex]" :value="item['attr_list']">
                        <template v-for="items in item['attr_list']" >
                            {{items['attr_group_name']}}:{{items['attr_name']}}  
                        </template>
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row num" v-if="form[tabIndex].type==1 || form[tabIndex].type==3">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label">名称</label>
            </div>
            <div class="col-sm-6">
                <input class="form-control" v-model="form[tabIndex].name" value="">
                   <div class="fs-sm text-muted">不填 则表示默认</div>
            </div>
        </div>

        <div class="form-group row num" v-if="form[tabIndex].type==3">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label required">积分数量</label>
            </div>
            <div class="col-sm-6">
                <input type="number" min="0" max="10000000" step="1" class="form-control" v-model="form[tabIndex].num" value="">
            </div>
        </div>

        <div class="form-group row num" v-if="form[tabIndex].type==1">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label required">金额</label>
            </div>
            <div class="col-sm-6">
                <input type="number" min="0" max="10000000" step="0.01" class="form-control" v-model="form[tabIndex].price" value="">
            </div>
        </div>

        <div class="form-group row stock" v-if="form[tabIndex].type==1 || form[tabIndex].type==2 || form[tabIndex].type==3 || form[tabIndex].type==4">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label required">库存</label>
            </div>
            <div class="col-sm-6">
                <input type="number" min="0" max="10000000" step="1" class="form-control" v-model="form[tabIndex].stock" value="">
            </div>
        </div>

    </span>
</template>
