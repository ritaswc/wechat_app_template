<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

?>

<style type="text/css">
    #one_menu_bar {
        width: 100%;
        position: relative;
    }

    #one_menu_bar #tab_bar {
        width: 100%;
        height: 50px;
        float: left;
        position: fixed;
        top: 50px;
        z-index: 1000;
        background-color: #F5F7F9;
        padding-top: 1rem;
        cursor: pointer;
    }

    #one_menu_bar #tab_bar ul {
        padding: 0px;
        margin: 0px;
        height: 40px;
        text-align: center;
    }

    #one_menu_bar #tab_bar li {
        list-style-type: none;
        float: left;
        width: 133.3px;
        height: 40px;
        line-height: 40px;
        background-color: #F5F6F7;
    }

    #one_menu_bar .tab_css {
        width: 100%;
        display: none;
        padding-top: 40px;
    }

</style>

<style>
    .attr_share_price {
        background-color: #fff;
        padding: 20px;
        margin-top: 15px;
        margin-bottom: 15px;
    }
</style>

<script>
    var pageType = '<?= $page_type ?>';
    var isGoods = '<?= isset($goods) ?>';
    var initData = {
        one: '',
        two: '',
        three: '',
        use_attr: isGoods ? <?= $goods['use_attr'] ? $goods['use_attr'] : 0 ?> : 0,
        attr_group_list: isGoods ? JSON.parse('<?= isset($goods) ? json_encode($goods->getAttrData()) : [] ?>', true) : [],//可选规格数据
        checked_attr_list: isGoods ? JSON.parse('<?= isset($goods) ? json_encode($goods->getCheckedAttrData()) : [] ?>', true) : [],//已选规格数据
        level_list: <?= isset($level_list) ? json_encode($level_list) : [] ?>,
    };

    if (pageType === 'STORE') {
        initData.sub_cat_list = [];
        // TODO attr_group_count 这里只要定义为0就好
        initData.attr_group_count = JSON.parse('<?= isset($goods) ? json_encode($goods->getAttrData()) : [] ?>', true).length;
        initData.old_checked_attr_list = [];
        initData.goods_card_list = <?= isset($goods_card_list) ? $goods_card_list : []?>;
//        initData.card_list = <?//= isset($goods_card_list) ? $goods_card_list : []?>//;
        initData.card_list = <?= isset($card_list) ? $card_list : [] ?>;
        initData.goods_cat_list = <?= isset($goods_cat_list) ? $goods_cat_list : [] ?>;
        initData.select_i = '';
    } else if (pageType === 'MIAOSHA') {
        initData.goodsList = null;
        initData.goods = null;
        initData.open_time = [];
        initData.open_date = [];
//        initData.attr_group_list = [];
//        initData.checked_attr_list = [];
//        initData.use_attr = 0;
    } else if (pageType === 'PINTUAN') {
        initData.sub_cat_list = [];
    } else if (pageType === 'PINTUAN_STANDARD') {
        // TODO 待后端数据统一加入
    } else if (pageType === 'BOOK') {
        initData.form_list = <?=isset($form_list) ? $form_list : [] ?>;
        initData.shop_list = <?= isset($shop_list) ? $shop_list : [] ?>;
    } else if (pageType === 'INTEGRALMALL') {
        // TODO 积分商城没有分销
    }


    if (pageType !== 'PINTUAN_STANDARD' && pageType !== 'INTEGRALMALL') {
        var page = new Vue({
            el: "#page",
            data: initData,
            methods: {
                change: function (item, index) {
                    this.checked_attr_list[index] = item;
                },
            }
        });
    }
</script>


<script type="text/javascript">
    var myclick = function (v) {
        var llis = document.getElementsByClassName('tab_bar_item');
        for (var i = 0; i < llis.length; i++) {
            var lli = llis[i];
            if (lli == document.getElementById("tab" + v)) {
                lli.style.backgroundColor = "#eeeeee";
            } else {
                lli.style.backgroundColor = "#F5F6F7";
            }
        }

        var divs = document.getElementsByClassName("tab_css");
        for (var i = 0; i < divs.length; i++) {

            var divv = divs[i];
            if (divv == document.getElementById("tab" + v + "_content")) {
                divv.style.display = "block";
            } else {
                divv.style.display = "none";
            }
        }

    }
</script>

<!--分销设置-->
<script>
    $(document).on("change", "input[name='model[individual_share]']", function () {
        setShareCommission();
    });

    $(document).on("change", "input[name='model[attr_setting_type]']", function () {
        setShareSetting();
    });

    setShareSetting();
    setShareCommission();

    function setShareCommission() {

        if ($("input[name='model[individual_share]']:checked").val() == 1) {
            $(".share_box1").show();
            setShareSetting();
        } else {
            $(".share_box1").hide();
        }
    }

    function setShareSetting() {

        if ($("input[name='model[attr_setting_type]']:checked").val() == 1) {
            $(".detail_share_setting").show();
            $(".share-commission").hide();
        } else {
            $(".detail_share_setting").hide();
            $(".share-commission").show();
        }
        $(".share-type_setting").show();
    }


    //分销佣金选择
    $(document).on('click', '.share-type', function () {
        var price_type = $(this).children('input');
        if ($(price_type).val() == 1) {
            $('.percent').html('元');
            $('.yuan').show()
            $('.bfb').hide()
        } else {
            $('.percent').html('%');
            $('.yuan').hide()
            $('.bfb').show()
        }
    })
</script>

<script>
    // 是否使用规格
    function checkUseAttr() {
        if ($('.use-attr').length == 0)
            return;
        if ($('.use-attr').prop('checked')) {
            $('input[name="model[goods_num]"]').val(0).prop('readonly', true);
            $('input[name="model[goods_no]"]').val(0).prop('readonly', true);
            $('.attr-edit-block').show();
            Vue.set(page, 'use_attr', true);
        } else {
            $('input[name="model[goods_num]"]').prop('readonly', false);
            $('input[name="model[goods_no]"]').prop('readonly', false);
            $('.attr-edit-block').hide();
            Vue.set(page, 'use_attr', false);
        }
    }

    $(document).on('change', '.use-attr', function () {
        checkUseAttr();
    });

    checkUseAttr();

</script>

<script>
    // 是否开启会员折扣
    $(document).on("change", "input[name='model[is_level]']", function () {
        setMemberDiscount();
    });

    setMemberDiscount();

    function setMemberDiscount() {

        if ($("input[name='model[is_level]']:checked").val() == 1) {
            $(".member_price_box").show();
        } else {
            $(".member_price_box").hide();
        }
    }


    // 批量设置 多规格会员价
    $(document).on('click', '.set-member-price', function () {
        var level = page.level_list;
        var attr = page.checked_attr_list;
        for (var i = 0; i < level.length; i++) {
            var className = 'member' + level[i]['level'];
            var value = $('.' + className).val()

            if (value >= 0.01) {
                for (var j = 0; j < attr.length; j++) {
                    Vue.set(attr[j], className, (parseFloat(value)).toFixed(2))
                }
            }

            if (value > 0 && value < 0.01) {
                for (var j = 0; j < attr.length; j++) {
                    Vue.set(attr[j], className, (parseFloat(0.01)).toFixed(2))
                }
            }

        }
    });
    // 清空所有会员价
    $(document).on('click', '.delete-member-price', function () {
        var level = page.level_list;
        var attr = page.checked_attr_list;
        for (var i = 0; i < level.length; i++) {
            var className = 'member' + level[i]['level'];

            for (var j = 0; j < attr.length; j++) {
                Vue.set(attr[j], className, '')
            }

        }
    });

    // 批量设置 多规格分销价
    $(document).on('click', '.set-share-price', function () {
        var oneValue = $('.share_price_one').val();
        var twoValue = $('.share_price_two').val();
        var threeValue = $('.share_price_three').val();
        var attr = page.checked_attr_list;

        for (var i = 0; i < attr.length; i++) {
            oneValue >= 0.01 ? Vue.set(attr[i], 'share_commission_first', (parseFloat(oneValue)).toFixed(2)) : '';
            twoValue >= 0.01 ? Vue.set(attr[i], 'share_commission_second', (parseFloat(twoValue)).toFixed(2)) : '';
            threeValue >= 0.01 ? Vue.set(attr[i], 'share_commission_third', (parseFloat(threeValue)).toFixed(2)) : '';
        }
    })

    // 清空所有分销价
    $(document).on('click', '.delete-share-price', function () {
        var attr = page.checked_attr_list;

        for (var i = 0; i < attr.length; i++) {
            Vue.set(attr[i], 'share_commission_first', '');
            Vue.set(attr[i], 'share_commission_second', '');
            Vue.set(attr[i], 'share_commission_third', '');
        }
    })
</script>