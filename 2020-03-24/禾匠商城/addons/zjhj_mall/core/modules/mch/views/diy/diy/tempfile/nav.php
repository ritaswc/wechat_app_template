<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/10/11
 * Time: 21:11
 */
?>
<div class="mt-2 d-flex align-items-center">
    <div>共{{modal_list.count}}条，每页8条</div>
    <div class="text-center ml-4" v-if="modal_list.count > 2">
        <nav aria-label="Page navigation example">
            <ul class="pagination vue-pagination" style="margin: 0;">
                <template v-if="modal_list.page > 1">
                    <li class="page-item">
                        <a class="page-link" href="javascript:"
                           :data-url="modal_list.page_url + '&page=1'">首页</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="javascript:"
                           :data-url="modal_list.page_url + '&page=' + (modal_list.page-1)">上一页</a>
                    </li>
                </template>
                <template v-else>
                    <li class="page-item disabled">
                        <span class="page-link">首页</span>
                    </li>
                    <li class="page-item disabled">
                        <span class="page-link">上一页</span>
                    </li>
                </template>
                <template v-for="i in modal_list.page_count" v-if="i >= (modal_list.page > 5 ? (modal_list.page_count >= modal_list.page+5 ? modal_list.page-4 : modal_list.page_count-9) : 1) && i <= (modal_list.page > 5 ? (modal_list.page_count >= modal_list.page+5 ? modal_list.page+5 : modal_list.page_count) : 10)">
                    <li :class="'page-item ' + (modal_list.page == i ? 'active' : '')">
                        <a class="page-link" href="javascript:"
                           :data-url="modal_list.page_url + '&page=' + i">{{i}}</a>
                    </li>
                </template>
                <template v-if="modal_list.page < modal_list.page_count">
                    <li class="page-item">
                        <a class="page-link" href="javascript:"
                           :data-url="modal_list.page_url + '&page=' + (parseInt(modal_list.page)+1)">下一页</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="javascript:"
                           :data-url="modal_list.page_url + '&page=' + modal_list.page_count">尾页</a>
                    </li>
                </template>
                <template v-else>
                    <li class="page-item disabled">
                        <span class="page-link">下一页</span>
                    </li>
                    <li class="page-item disabled">
                        <span class="page-link">尾页</span>
                    </li>
                </template>
            </ul>
        </nav>
    </div>
</div>
