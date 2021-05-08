<?php
// 每页条数
$config['per_page'] = 10; 
// 左右侧显示页数
$config['num_links'] = 5;
// 页码模式
$config['use_page_numbers'] = true; 
// enable_query_strings风格
$config['page_query_string'] = true;
// 整个分页控件
$config['full_tag_open'] = '<ul class="pagination">';
$config['full_tag_close'] = '</ul>';
// 第一页
$config['first_link'] = '&lt;&lt;';
$config['first_tag_open'] = '<li>';
$config['first_tag_close'] = '</li>';
// 最后一页
$config['last_link'] = '&gt;&gt;';
$config['last_tag_open'] = '<li>';
$config['last_tag_close'] = '</li>';
// 上一页
$config['prev_link'] = '&lt;';
$config['prev_tag_open'] = '<li>';
$config['prev_tag_close'] = '</li>';
// 下一页
$config['next_link'] = '&gt;';
$config['next_tag_open'] = '<li>';
$config['next_tag_close'] = '</li>';
// 页码
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
// 当前页
$config['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
$config['cur_tag_close'] = '</li></a>';