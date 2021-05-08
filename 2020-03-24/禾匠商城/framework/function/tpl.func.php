<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

if (defined('IN_MOBILE')) {
	load()->app('tpl');
} else {
	load()->web('tpl');
}

function tpl_form_field_date($name, $value = '', $withtime = false) {
	return _tpl_form_field_date($name, $value, $withtime);
}

function tpl_form_field_clock($name, $value = '') {
	$s = '';
	if (!defined('TPL_INIT_CLOCK_TIME')) {
		$s .= '
		<script type="text/javascript">
			require(["clockpicker"], function($){
				$(function(){
					$(".clockpicker").clockpicker({
						autoclose: true
					});
				});
			});
		</script>
		';
		define('TPL_INIT_CLOCK_TIME', 1);
	}
	$time = date('H:i');
	if (!empty($value)) {
		if (!strexists($value, ':')) {
			$time = date('H:i', $value);
		} else {
			$time = $value;
		}
	}
	$s .= '	<div class="input-group clockpicker">
				<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
				<input type="text" name="' . $name . '" value="' . $time . '" class="form-control">
			</div>';

	return $s;
}


function tpl_form_field_daterange($name, $value = array(), $time = false, $clear = true) {
	$s = '';

	if (empty($time) && !defined('TPL_INIT_DATERANGE_DATE')) {
		$s = '
<script type="text/javascript">
	require(["daterangepicker"], function(){
		$(function(){
			$(".daterange.daterange-date").each(function(){
				var elm = this;
				$(this).daterangepicker({
					startDate: $(elm).prev().prev().val() || moment("不限", "Y"),
					endDate: $(elm).prev().val() || moment("不限", "Y"),
					format: "YYYY-MM-DD",
					clear: '. $clear .'
				}, function(start, end){
					start = start.toDateStr().indexOf("0000-01-01") != -1 ? "" : start.toDateStr();
					end = end.toDateStr().indexOf("0000-01-01") != -1 ? "" : end.toDateStr();
					var html = (start == "" ? "不限时间" : start) + (start == "" && end === "" ? "" : (" 至" + end))
					$(elm).find(".date-title").html(html);
					$(elm).prev().prev().val(start);
					$(elm).prev().val(end);
				});
			});
		});
	});
</script>
';
		define('TPL_INIT_DATERANGE_DATE', true);
	}

	if (!empty($time) && !defined('TPL_INIT_DATERANGE_TIME')) {
		$s = '
<script type="text/javascript">
	require(["daterangepicker"], function(){
		$(function(){
			$(".daterange.daterange-time").each(function(){
				var elm = this;
				$(this).daterangepicker({
					startDate: $(elm).prev().prev().val() || moment("不限", "Y"),
					endDate: $(elm).prev().val() || moment("不限", "Y"),
					format: "YYYY-MM-DD HH:mm",
					timePicker: true,
					timePicker12Hour : false,
					timePickerIncrement: 1,
					minuteStep: 1,
					clear: '. $clear .'
				}, function(start, end){
					start = start.toDateStr().indexOf("0000-01-01") != -1 ? "" : start.toDateStr();
					end = end.toDateStr().indexOf("0000-01-01") != -1 ? "" : end.toDateStr();
					var html = (start == "" ? "不限时间" : start) + (start == "" && end === "" ? "" : (" 至" + end))
					$(elm).find(".date-title").html(html);
					$(elm).prev().prev().val(start);
					$(elm).prev().val(end);
				});
			});
		});
	});
</script>
';
		define('TPL_INIT_DATERANGE_TIME', true);
	}
	if (!empty($value['starttime']) || !empty($value['start'])) {
		if ($value['start'] && strtotime($value['start'])) {
			$value['starttime'] = empty($time) ? date('Y-m-d', strtotime($value['start'])) : date('Y-m-d H:i', strtotime($value['start']));
		}
		$value['starttime'] = empty($value['starttime']) ? '' : $value['starttime'];
	} else {
		$value['starttime'] = '';
	}

	if (!empty($value['endtime']) || !empty($value['end'])) {
		if ($value['end'] && strtotime($value['end'])) {
			$value['endtime'] = empty($time) ? date('Y-m-d', strtotime($value['end'])) : date('Y-m-d H:i', strtotime($value['end']));
		}
		$value['endtime'] = empty($value['endtime']) ? $value['starttime'] : $value['endtime'];
	} else {
		$value['endtime'] = '';
	}
	$s .= '
	<input name="' . $name . '[start]' . '" type="hidden" value="' . $value['starttime'] . '" />
	<input name="' . $name . '[end]' . '" type="hidden" value="' . $value['endtime'] . '" />
	<button class="btn btn-default daterange ' . (!empty($time) ? 'daterange-time' : 'daterange-date') . '" type="button"><span class="date-title">' . 
	($value['starttime'] == "" ? "不限时间" : $value['starttime']) . ($value['starttime'] == "" && $value['endtime'] === "" ? "" : (" 至" . $value['endtime'])) . '</span> <i class="fa fa-calendar"></i></button>
	';

	return $s;
}


function tpl_form_field_calendar($name, $values = array()) {
	$html = '';
	if (!defined('TPL_INIT_CALENDAR')) {
		$html .= '
		<script type="text/javascript">
			function handlerCalendar(elm) {
				require(["moment"], function(moment){
					var tpl = $(elm).parent().parent();
					var year = tpl.find("select.tpl-year").val();
					var month = tpl.find("select.tpl-month").val();
					var day = tpl.find("select.tpl-day");
					day[0].options.length = 1;
					if(year && month) {
						var date = moment(year + "-" + month, "YYYY-M");
						var days = date.daysInMonth();
						for(var i = 1; i <= days; i++) {
							var opt = new Option(i, i);
							day[0].options.add(opt);
						}
						if(day.attr("data-value")!=""){
							day.val(day.attr("data-value"));
						} else {
							day[0].options[0].selected = "selected";
						}
					}
					if($("select").niceSelect) {
						$("select").niceSelect("update");
					}
				});
			}
			require([""], function(){
				$(".tpl-calendar").each(function(){
					handlerCalendar($(this).find("select.tpl-year")[0]);
				});
			});
		</script>';
		define('TPL_INIT_CALENDAR', true);
	}

	if (empty($values) || !is_array($values)) {
		$values = array(0, 0, 0);
	}
	$values['year'] = intval($values['year']);
	$values['month'] = intval($values['month']);
	$values['day'] = intval($values['day']);

	if (empty($values['year'])) {
		$values['year'] = '1980';
	}
	$year = array(date('Y'), '1914');
	$html .= '<div class="row row-fix tpl-calendar">
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
			<select name="' . $name . '[year]" onchange="handlerCalendar(this)" class="form-control tpl-year">
				<option value="">年</option>';
	for ($i = $year[1]; $i <= $year[0]; ++$i) {
		$html .= '<option value="' . $i . '"' . ($i == $values['year'] ? ' selected="selected"' : '') . '>' . $i . '</option>';
	}
	$html .= '	</select>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
			<select name="' . $name . '[month]" onchange="handlerCalendar(this)" class="form-control tpl-month">
				<option value="">月</option>';
	for ($i = 1; $i <= 12; ++$i) {
		$html .= '<option value="' . $i . '"' . ($i == $values['month'] ? ' selected="selected"' : '') . '>' . $i . '</option>';
	}
	$html .= '	</select>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
			<select name="' . $name . '[day]" data-value="' . $values['day'] . '" class="form-control tpl-day">
				<option value="0">日</option>
			</select>
		</div>
	</div>';

	return $html;
}


function tpl_form_field_district($name, $values = array()) {
	$html = '';
	if (!defined('TPL_INIT_DISTRICT')) {
		$html .= '
		<script type="text/javascript">
			require(["district"], function(dis){
				$(".tpl-district-container").each(function(){
					var elms = {};
					elms.province = $(this).find(".tpl-province")[0];
					elms.city = $(this).find(".tpl-city")[0];
					elms.district = $(this).find(".tpl-district")[0];
					var vals = {};
					vals.province = $(elms.province).attr("data-value");
					vals.city = $(elms.city).attr("data-value");
					vals.district = $(elms.district).attr("data-value");
					dis.render(elms, vals, {withTitle: true});
				});
			});
		</script>';
		define('TPL_INIT_DISTRICT', true);
	}
	if (empty($values) || !is_array($values)) {
		$values = array('province' => '', 'city' => '', 'district' => '');
	}
	if (empty($values['province'])) {
		$values['province'] = '';
	}
	if (empty($values['city'])) {
		$values['city'] = '';
	}
	if (empty($values['district'])) {
		$values['district'] = '';
	}
	$html .= '
		<div class="row row-fix tpl-district-container">
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
				<select name="' . $name . '[province]" data-value="' . $values['province'] . '" class="form-control tpl-province">
				</select>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
				<select name="' . $name . '[city]" data-value="' . $values['city'] . '" class="form-control tpl-city">
				</select>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
				<select name="' . $name . '[district]" data-value="' . $values['district'] . '" class="form-control tpl-district">
				</select>
			</div>
		</div>';

	return $html;
}


function tpl_form_field_category_2level($name, $parents, $children, $parentid, $childid) {
	$html = '
		<script type="text/javascript">
			window._' . $name . ' = ' . json_encode($children) . ';
		</script>';
	if (!defined('TPL_INIT_CATEGORY')) {
		$html .= '
		<script type="text/javascript">
			function renderCategory(obj, name){
				var index = obj.options[obj.selectedIndex].value;
				require([\'jquery\', \'util\'], function($, u){
					$selectChild = $(\'#\'+name+\'_child\');
					var html = \'<option value="0">请选择二级分类</option>\';
					if (!window[\'_\'+name] || !window[\'_\'+name][index]) {
						$selectChild.html(html);
						return false;
					}
					for(var i=0; i< window[\'_\'+name][index].length; i++){
						html += \'<option value="\'+window[\'_\'+name][index][i][\'id\']+\'">\'+window[\'_\'+name][index][i][\'name\']+\'</option>\';
					}
					$selectChild.html(html);
					if($("select").niceSelect) {
						$("select").niceSelect("update");
					}
				});
			}
		</script>
					';
		define('TPL_INIT_CATEGORY', true);
	}

	$html .=
				'<div class="row row-fix tpl-category-container">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<select class="form-control tpl-category-parent we7-select" id="' . $name . '_parent" name="' . $name . '[parentid]" onchange="renderCategory(this,\'' . $name . '\')">
					<option value="0">请选择一级分类</option>';
	$ops = '';
	if (!empty($parents)) {
		foreach ($parents as $row) {
			$html .= '
						<option value="' . $row['id'] . '" ' . (($row['id'] == $parentid) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
		}
	}

	$html .= '
				</select>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<select class="form-control tpl-category-child we7-select" id="' . $name . '_child" name="' . $name . '[childid]">
					<option value="0">请选择二级分类</option>';
	if (!empty($parentid) && !empty($children[$parentid])) {
		foreach ($children[$parentid] as $row) {
			$html .= '
					<option value="' . $row['id'] . '"' . (($row['id'] == $childid) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
		}
	}
	$html .= '
				</select>
			</div>
		</div>
	';

	return $html;
}


function tpl_form_field_industry($name, $pvalue = '', $cvalue = '', $parentid = 'industry_1', $childid = 'industry_2') {
	$html = '
	<div class="row row-fix">
		<div class="col-sm-4">
			<select name="' . $name . '[parent]" id="' . $parentid . '" class="form-control" value="' . $pvalue . '"></select>
		</div>
		<div class="col-sm-4">
			<select name="' . $name . '[child]" id="' . $childid . '" class="form-control" value="' . $cvalue . '"></select>
		</div>
		<script type="text/javascript">
			require([\'industry\'], function(industry){
				industry.init("' . $parentid . '","' . $childid . '");
			});
		</script>
	</div>';

	return $html;
}


function tpl_form_field_coordinate($field, $value = array()) {
	$s = '';
	if (!defined('TPL_INIT_COORDINATE')) {
		$s .= '<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=F51571495f717ff1194de02366bb8da9&s=1"></script><script type="text/javascript">
				function showCoordinate(elm) {
					require(["util"], function(util){
						var val = {};
						val.lng = parseFloat($(elm).parent().prev().prev().find(":text").val());
						val.lat = parseFloat($(elm).parent().prev().find(":text").val());
						util.map(val, function(r){
							$(elm).parent().prev().prev().find(":text").val(r.lng);
							$(elm).parent().prev().find(":text").val(r.lat);
						});

					});
				}

			</script>';
		define('TPL_INIT_COORDINATE', true);
	}
	$s .= '
		<div class="row row-fix">
			<div class="col-xs-4 col-sm-4">
				<input type="text" name="' . $field . '[lng]" value="' . $value['lng'] . '" placeholder="地理经度"  class="form-control" />
			</div>
			<div class="col-xs-4 col-sm-4">
				<input type="text" name="' . $field . '[lat]" value="' . $value['lat'] . '" placeholder="地理纬度"  class="form-control" />
			</div>
			<div class="col-xs-4 col-sm-4">
				<button onclick="showCoordinate(this);" class="btn btn-default" type="button">选择坐标</button>
			</div>
		</div>';

	return $s;
}


function tpl_fans_form($field, $value = '') {
	switch ($field) {
	case 'avatar':
		$avatar_url = '../attachment/images/global/avatars/';
		$html = '';
		if (!defined('TPL_INIT_AVATAR')) {
			$html .= '
			<script type="text/javascript">
				function showAvatarDialog(elm, opts) {
					require(["util"], function(util){
						var btn = $(elm);
						var ipt = btn.parent().prev();
						var img = ipt.parent().next().children();
						var content = \'<div class="avatar-browser clearfix">\';
						for(var i = 1; i <= 12; i++) {
							content +=
								\'<div title="头像\' + i + \'" class="thumbnail">\' +
									\'<em><img src="' . $avatar_url . 'avatar_\' + i + \'.jpg" class="img-responsive"></em>\' +
								\'</div>\';
						}
						content += "</div>";
						var dialog = util.dialog("请选择头像", content);
						dialog.modal("show");
						dialog.find(".thumbnail").on("click", function(){
							var url = $(this).find("img").attr("src");
							img.get(0).src = url;
							ipt.val(url.replace(/^\.\.\/attachment\//, ""));
							dialog.modal("hide");
						});
					});
				}
			</script>';
			define('TPL_INIT_AVATAR', true);
		}
		if (!defined('TPL_INIT_IMAGE')) {
			global $_W;
			if (defined('IN_MOBILE')) {
				$html .= <<<EOF
				<script type="text/javascript">
					// in mobile
					function showImageDialog(elm) {
						require(["jquery", "util"], function($, util){
							var btn = $(elm);
							var ipt = btn.parent().prev();
							var val = ipt.val();
							var img = ipt.parent().next().children();
							util.image(elm, function(url){
								img.get(0).src = url.url;
								ipt.val(url.attachment);
							});
						});
					}
				</script>
EOF;
			} else {
				$html .= <<<EOF
				<script type="text/javascript">
					// in web
					function showImageDialog(elm, opts) {
						require(["util"], function(util){
							var btn = $(elm);
							var ipt = btn.parent().prev();
							var val = ipt.val();
							var img = ipt.parent().next().find('img');
							util.image(val, function(url){
								img.get(0).src = url.url;
								ipt.val(url.attachment);
							}, {multiple:false,type:"image",direct:true}, opts);
						});
					}
				</script>
EOF;
			}
			define('TPL_INIT_IMAGE', true);
		}
		$val = './resource/images/nopic.jpg';
		if (!empty($value)) {
			$val = tomedia($value);
		}
		$options = array();
		$options['width'] = '200';
		$options['height'] = '200';

		if (defined('IN_MOBILE')) {
			$html .= <<<EOF
			<div class="input-group">
				<input type="text" value="{$value}" name="{$field}" class="form-control" autocomplete="off">
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" onclick="showImageDialog(this);">选择图片</button>
					<button class="btn btn-default" type="button" onclick="showAvatarDialog(this);">系统头像</button>
				</span>
			</div>
			<div class="input-group" style="margin-top:.5em;">
				<img src="{$val}" class="img-responsive img-thumbnail" width="150" style="max-height: 150px;"/>
			</div>
EOF;
		} else {
			$html .= '
			<div class="input-group">
				<input type="text" value="' . $value . '" name="' . $field . '" class="form-control" autocomplete="off">
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" onclick="showImageDialog(this, \'' . base64_encode(iserializer($options)) . '\');">选择图片</button>
					<button class="btn btn-default" type="button" onclick="showAvatarDialog(this);">系统头像</button>
				</span>
			</div>
			<div class="input-group" style="margin-top:.5em;">
				<img src="' . $val . '" class="img-responsive img-thumbnail" width="150" />
			</div>';
		}

		break;
	case 'birth':
	case 'birthyear':
	case 'birthmonth':
	case 'birthday':
		$html = tpl_form_field_calendar('birth', $value);
		break;
	case 'reside':
	case 'resideprovince':
	case 'residecity':
	case 'residedist':
		$html = tpl_form_field_district('reside', $value);
		break;
	case 'bio':
	case 'interest':
		$html = '<textarea name="' . $field . '" class="form-control">' . $value . '</textarea>';
		break;
	case 'gender':
		$html = '
				<select name="gender" class="form-control">
					<option value="0" ' . (0 == $value ? 'selected ' : '') . '>保密</option>
					<option value="1" ' . (1 == $value ? 'selected ' : '') . '>男</option>
					<option value="2" ' . (2 == $value ? 'selected ' : '') . '>女</option>
				</select>';
		break;
	case 'education':
	case 'constellation':
	case 'zodiac':
	case 'bloodtype':
		if ('bloodtype' == $field) {
			$options = array('A', 'B', 'AB', 'O', '其它');
		} elseif ('zodiac' == $field) {
			$options = array('鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪');
		} elseif ('constellation' == $field) {
			$options = array('水瓶座', '双鱼座', '白羊座', '金牛座', '双子座', '巨蟹座', '狮子座', '处女座', '天秤座', '天蝎座', '射手座', '摩羯座');
		} elseif ('education' == $field) {
			$options = array('博士', '硕士', '本科', '专科', '中学', '小学', '其它');
		}
		$html = '<select name="' . $field . '" class="form-control">';
		foreach ($options as $item) {
			$html .= '<option value="' . $item . '" ' . ($value == $item ? 'selected ' : '') . '>' . $item . '</option>';
		}
		$html .= '</select>';
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
		$html = '<input type="text" class="form-control" name="' . $field . '" value="' . $value . '" />';
		break;
	}

	return $html;
}