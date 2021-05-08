<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

function _tpl_form_field_date($name, $values = '', $withtime = false) {
	$html = '';
	$html .= '<input class="mui-calendar-picker" type="text" placeholder="请选择日期" readonly value="' . $values . '" name="' . $name . '" />';
	$html .= '<input type="hidden" value="' . $values . '" name="' . $name . '"/>';
	if (!defined('TPL_INIT_CALENDAR')) {
		$html .= '
			<script type="text/javascript">
				$(document).on("tap", ".mui-calendar-picker", function(){
					var $this = $(this);
					util.datepicker({type: "date", beginYear: 1960, endYear: 2050}, function(rs){
						$this.val(rs.value)
						.next().val(rs.value)
					});
				});
			</script>';
		define('TPL_INIT_CALENDAR', true);
	}
	return $html;
}

function tpl_app_fans_form($field, $value = '', $placeholder = '') {
	$placeholders[$field] = '请填写' . $placeholder;
	if(in_array($field, array('birth', 'reside', 'gender', 'education', 'constellation', 'zodiac', 'bloodtype'))) {
		$placeholders[$field] = '请选择' . $placeholder;
	}
	if($field == 'height') {
		$placeholders[$field] = '请填写' . $placeholder . '(单位:cm)';
	} elseif ($field == 'weight') {
		$placeholders[$field] = '请填写' . $placeholder . '(单位:kg)';
	}
	switch ($field) {
		case 'avatar':
			$html = tpl_app_form_field_avatar('avatar', $value);
			break;
		case 'birth':
		case 'birthyear':
		case 'birthmonth':
		case 'birthday':
			$html = tpl_app_form_field_calendar('birth', $value);
			break;
		case 'reside':
		case 'resideprovince':
		case 'residecity':
		case 'residedist':
			$html = tpl_app_form_field_district('reside', $value);
			break;
		case 'bio':
		case 'interest':
			$html = '<textarea name="' . $field . '" rows="3" placeholder="' . $placeholders[$field] . '">' . $value . '</textarea>';
			break;
		case 'gender':
		case 'education':
		case 'constellation':
		case 'zodiac':
		case 'bloodtype':
			if($field == 'gender') {
				$options = array(
					'0' => '保密',
					'1' => '男',
					'2' => '女',
				);
				$text_value = $options[$value];
			} else {
				if ($field == 'bloodtype') {
					$options = array('A', 'B', 'AB', 'O', '其它');
				} elseif ($field == 'zodiac') {
					$options = array('鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪');
				} elseif ($field == 'constellation') {
					$options = array('水瓶座', '双鱼座', '白羊座', '金牛座', '双子座', '巨蟹座', '狮子座', '处女座', '天秤座', '天蝎座', '射手座', '摩羯座');
				} elseif ($field == 'education') {
					$options = array('博士', '硕士', '本科', '专科', '中学', '小学', '其它');
				}
				$text_value = $value;
			}
			$data = array();
			foreach($options as $key => $option) {
				if(!$option) {
					continue;
				}
				if($field == 'gender') {
					$data[] = array(
						'text' => $option,
						'value' => $key
					);
				} else {
					$data[] = array(
						'text' => $option,
						'value' => $option
					);
				}
			}
			if($field != 'gender') {
				$text_value = $value;
				unset($options);
			}
			$html = '
				<input class="mui-'. $field .'-picker" type="text" value="'. $text_value .'" readonly placeholder="' . $placeholders[$field] . '"/>
				<input type="hidden" name="'. $field .'" value="'. $value .'"/>
				<script type="text/javascript">
					$(".mui-'. $field .'-picker").on("tap", function(){
						var $this = $(this);
						util.poppicker({data: '. json_encode($data) .'}, function(items){
							$this.val(items[0].text).next().val(items[0].value);
						});
					});
				</script>';
			break;
		case 'nickname':
		case 'realname':
		case 'address':
		case 'mobile':
		case 'qq':
		case 'msn':
		case 'email':
		case 'telephone':
		case 'taobao':
		case 'alipay':
		case 'studentid':
		case 'grade':
		case 'graduateschool':
		case 'idcard':
		case 'zipcode':
		case 'site':
		case 'affectivestatus':
		case 'lookingfor':
		case 'nationality':
		case 'height':
		case 'weight':
		case 'company':
		case 'occupation':
		case 'position':
		case 'revenue':
		default:
			$html = '<input type="text" name="' . $field . '" value="' . $value . '"  placeholder="' . $placeholders[$field] . '"/>';
			break;
	}
	return $html;
}

function tpl_app_form_field_calendar($name, $values = array()) {
	$value = (empty($values['year']) || empty($values['month']) || empty($values['day'])) ? '' : implode('-', $values);
	$html = '';
	$html .= '<input class="mui-calendar-picker" type="text" placeholder="请选择日期" readonly value="' . $value . '" name="' . $name . '" />';
	$html .= '<input type="hidden" value="' . $values['year'] . '" name="' . $name . '[year]"/>';
	$html .= '<input type="hidden" value="' . $values['month'] . '" name="' . $name . '[month]"/>';
	$html .= '<input type="hidden" value="' . $values['day'] . '" name="' . $name . '[day]"/>';
	if (!defined('TPL_INIT_CALENDAR')) {
		$html .= '
			<script type="text/javascript">
				$(document).on("tap", ".mui-calendar-picker", function(){
					var $this = $(this);
					util.datepicker({
						type: "date", 
						beginYear: 1910, 
						endYear: 2060, 
						selected : {
							year : "' . $values['year'] . '", month : "' . $values['month'] . '", day : "' . $values['day'] . '"}
						}, function(rs){
							$this.val(rs.value)
							.next().val(rs.y.text)
							.next().val(rs.m.text)
							.next().val(rs.d.text)
					});
				});
			</script>';
		define('TPL_INIT_CALENDAR', true);
	}
	return $html;
}

function tpl_app_form_field_district($name, $values = array()) {
	$value = (empty($values['province']) || empty($values['city'])) ? '' : implode(' ', $values);
	$html = '';
	$html .= '<input class="mui-district-picker-' . $name .'" placeholder="请选择地区" type="text" readonly value="' . $value . '"/>';
	$html .= '<input type="hidden" value="' . $values['province'] . '" name="' . $name . '[province]"/>';
	$html .= '<input type="hidden" value="' . $values['city'] . '" name="' . $name . '[city]"/>';
	$html .= '<input type="hidden" value="' . $values['district'] . '" name="' . $name . '[district]"/>';
	$html .= '
		<script type="text/javascript">
			$(document).on("tap", ".mui-district-picker-' . $name . '", function(){
				var $this = $(this);
				util.districtpicker(function(item){
					item[2].text = item[2].text || "";
					$this.val(item[0].text+" "+item[1].text+" "+item[2].text)
					.next().val(item[0].text)
					.next().val(item[1].text)
					.next().val(item[2].text);
				}, {province : "' . $values['province'] . '", city : "' . $values['city'] . '", district : "' . $values['district'] . '"});
			});
		</script>';
	return $html;
}

function tpl_app_form_field_avatar($name, $value = '', $type = 0) {
	$val = './resource/images/nopic.jpg';
	if (!empty($value)) {
		$val = tomedia($value);
	}
	$html = '<ul class="mui-table-view mui-table-view-chevron">
		<li class="mui-table-view-cell avatar js-avatar-'.$name.'">
			<a href="javascript:;" class="mui-navigate-right">头像
				<div class="mui-pull-right mui-navigate-right">
					<img class="mui-avatar-select mui-pull-left" src="' . $val. '" width="40" height="40">
				</div>
			</a>
		</li>
	</ul>
	';
	if ($type) {
		$html = '<div class="mui-pull-right mui-navigate-right js-avatar-'.$name.'" style="padding-right: 50px;">
					<img class="mui-avatar-select mui-pull-left" src="' . $val. '" width="40" height="40">
				</div>
		';
	}
	$href = url('mc/profile/avatar');
	$html .= "<script>
		util.image($('.js-avatar-{$name}'), function(url){
			$('.js-avatar-{$name} img').attr('src', url.url);
			$.post('" . $href . "', {'avatar' : url.attachment}, function(data) {
				data = $.parseJSON(data);
				if (data.type == 'success') {
					util.toast(data.message);
				} else {
					util.toast('更新失败');
				}
			})
		}, {
			crop : true
		});
	</script>";
	return $html;
}


function tpl_app_form_field_image($name, $value = '') {
	$value = safe_gpc_string($value);
	$html = <<<EOF
	<div class="mui-table-view-chevron">
		<div class="mui-image-uploader">
			<a href="javascript:;" class="mui-upload-btn mui-pull-right js-image-{$name}"></a>
			<div class="mui-image-preview js-image-preview mui-pull-right">
EOF;
	if (!empty($value)) {
		$thumb = tomedia($value);
		$html .= <<<EOF
				<input type="hidden" value="{$value}" name="{$name}[]" /><img src="{$thumb}" data-preview-src="" data-preview-group="__IMG_UPLOAD_{$name}" />
EOF;
	}
	$html .= <<<EOF
			</div>
		</div>
	</div>
	<script>
		util.image($('.js-image-{$name}'), function(url){
			$('.js-image-{$name}').parent().find('.js-image-preview').append('<input type="hidden" value="'+url.attachment+'" name="{$name}[]" /><img src="'+url.url+'" data-id="'+url.id+'" data-preview-src="" data-preview-group="__IMG_UPLOAD_{$name}" />');
		}, {
			crop : false,
			multiple : true,
			preview : '__IMG_UPLOAD_{$name}'
		});
	</script>
EOF;
	return $html;
}

function tpl_app_coupon_item($item) {
	load()->model('activity');
	$type_names = activity_coupon_type_label();
	if ($item['type'] == COUPON_TYPE_DISCOUNT) {
		$icon = '<div class="price">'.$item['extra']['discount'] * 0.1.'<span>折</span></div>';
	} elseif ($item['type'] == COUPON_TYPE_CASH) {
		$icon = '<div class="price">' . $item['extra']['reduce_cost'] * 0.01 . '<span>元</span></div><div class="condition">满' . $item['extra']['least_cost'] * 0.01 . '元可用</div>';
	} elseif ($item['type'] == COUPON_TYPE_GROUPON) {
		$icon = '<img src="resource/images/groupon.png" alt="" />';
	} elseif ($item['type'] == COUPON_TYPE_GIFT) {
		$icon = '<img src="resource/images/wx_gift.png" alt="" />';
	} elseif ($item['type'] == COUPON_TYPE_GENERAL) {
		$icon = '<img src="resource/images/general_coupon.png" alt="" />';
	}
	$extra_func = '<div class="mui-col-xs-5 mui-text-info integral-info"><img src="' . $item['extra_func']['pic'] .'" alt=""/><span>' . $item['extra_func']['credit'] . '</span></div>';
	$html .= '
		<div class="coupon-panel-info mui-mb10">
			<div class="mui-row">
				<div class="mui-col-xs-4 mui-text-center">
					<div class="coupon-panel-left">'
			. $icon .
			'</div>
				</div>
				<div class="mui-col-xs-8">
					<div class="mui-row">
						<div class="mui-col-xs-7 store-title mui-ellipsis">' . $item['title']. '</div>
						' . $extra_func . '
					</div>
					<div class="date">' . $item['extra_date_info'] . '</div>
					<div class="coupon-rules mui-text-muted js-scan-rules">' . $type_names[$item['type']][0] . '使用规则<span class="fa fa-angle-down"></span></div>
					<a class="use-token js-coupon-exchange" data-id="' . $item['id'] . '" data-source="' . $item['source'] . '" data-href="' . $item['extra_href'] . '">
						<span class="mui-block icon-use-token">⇌</span>
						<span class="mui-block">兑换</span>
					</a>
				</div>
			</div>
			<div class="coupon-rules-con js-rules-show" style="display:none;">
				<div>' . $item['description'] . '</div>
			</div>
		</div>
	';
	return $html;
}

function tpl_form_field_image($name, $value) {
	$thumb = empty($value) ? 'images/global/nopic.jpg' : $value;
	$thumb = tomedia($thumb);
	$html = <<<EOF
<style>
.webuploader-pick {color:#333;}
</style>
<div class="input-group">
	<input type="hidden" name="$name" value="$value" class="form-control" autocomplete="off" readonly="readonly">
	<a class="btn btn-default js-image-{$name}">上传图片</a>
</div>
<span class="help-block">
	<img src="$thumb" >
</span>

<script>
	util.image($('.js-image-{$name}'), function(url){
		$('.js-image-{$name}').prev().val(url.attachment);
		$('.js-image-{$name}').parent().next().find('img').attr('src',url.url);
	}, {
		crop : false,
		multiple : false
	});
</script>
EOF;
	return $html;
}