<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

function template_compat($filename) {
	static $mapping = array(
		'home/home' => 'index',
		'header' => 'common/header',
		'footer' => 'common/footer',
		'slide' => 'common/slide',
	);
	if(!empty($mapping[$filename])) {
		return $mapping[$filename];
	}
	return '';
}

function template_design($html) {
	if (empty($html)) {
		return '';
	}
	$html = str_replace(array('<?', '<%', '<?php', '{php'), '_', $html);
	$html = preg_replace('/<\s*?script.*[\n\f\r\t\v]*.*(src|language)+/i', '_', $html);

	$script_start = '<sc<x>ript type="text/ja<x>vasc<x>ript">';
	$script_end = '</sc<x>ript>';

	$count_down_script = <<<EOF
$(document).ready(function(){\r\n\t\t\t\t\tsetInterval(function(){\r\n\t\t\t\t\t\tvar timer = $('.timer');\r\n\t\t\t\t\t\tfor (var i = 0; i < timer.length; i++) {\r\n\t\t\t\t\t\t\tvar dead = $(timer.get(i)).attr('data');\r\n\t\t\t\t\t\t\tvar deadtime = dead.replace(/-/g,'/');\r\n\t\t\t\t\t\t\tdeadtime = new Date(deadtime).getTime();\r\n\t\t\t\t\t\t\tvar nowtime = Date.parse(Date());\r\n\t\t\t\t\t\t\tvar diff = deadtime - nowtime > 0 ? deadtime - nowtime : 0;\r\n\t\t\t\t\t\t\tvar res = {};\r\n\t\t\t\t\t\t\tres.day = parseInt(diff / (24 * 60 * 60 * 1000));\r\n\t\t\t\t\t\t\tres.hour = parseInt(diff / (60 * 60 * 1000) % 24);\r\n\t\t\t\t\t\t\tres.min = parseInt(diff / (60 * 1000) % 60);\r\n\t\t\t\t\t\t\tres.sec = parseInt(diff / 1000 % 60);\r\n\t\t\t\t\t\t\t$('.timer[data="'+dead+'"] .day').text(res.day);\r\n\t\t\t\t\t\t\t$('.timer[data="'+dead+'"] .hours').text(res.hour);\r\n\t\t\t\t\t\t\t$('.timer[data="'+dead+'"] .minutes').text(res.min);\r\n\t\t\t\t\t\t\t$('.timer[data="'+dead+'"] .seconds').text(res.sec);\r\n\t\t\t\t\t\t};\r\n\t\t\t\t\t}, 1000);\r\n\t\t\t\t});
EOF;
	$add_num_acript = <<<EOF
$(document).ready(function() {\r\n\t\t\t\t\tvar patt = new RegExp('c=home&a=page');\r\n\t\t\t\t\tif (patt.exec(window.location.href)) {\r\n\t\t\t\t\t\t$.post(window.location.href, {'do' : 'getnum'}, function(data) {\r\n\t\t\t\t\t\t\tif (data.message.errno == 0) {\r\n\t\t\t\t\t\t\t\t$('.counter-num').text(data.message.message.goodnum);\r\n\t\t\t\t\t\t\t}\r\n\t\t\t\t\t\t}, 'json');\r\n\t\t\t\t\t\t$(".app-good .element").click(function() {\r\n\t\t\t\t\t\t\tvar id=GetQueryString("id");\r\n\t\t\t\t\t\t\tif(id !=null && id.toString().length>=1 && localStorage.havegood != id){\r\n\t\t\t\t\t\t\t\t$.post(window.location.href, {'do': 'addnum'}, function(data) {\r\n\t\t\t\t\t\t\t\t\tif (data.message.errno == 0) {\r\n\t\t\t\t\t\t\t\t\t\tvar now = $('.counter-num').text();\r\n\t\t\t\t\t\t\t\t\t\tnow = parseInt(now)+1;\r\n\t\t\t\t\t\t\t\t\t\t$('.counter-num').text(now);\r\n\t\t\t\t\t\t\t\t\t\tlocalStorage.havegood = id;\r\n\t\t\t\t\t\t\t\t\t}\r\n\t\t\t\t\t\t\t\t}, 'json');\r\n\t\t\t\t\t\t\t}\r\n\t\t\t\t\t\t});\r\n\t\t\t\t\t\tfunction GetQueryString(name){\r\n\t\t\t\t\t\t\tvar reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");\r\n\t\t\t\t\t\t\tvar r = window.location.search.substr(1).match(reg);\r\n\t\t\t\t\t\t\tif(r!=null)return  unescape(r[2]); return null;\r\n\t\t\t\t\t\t}\t\t\t\t\t\t\r\n\t\t\t\t\t};\r\n\t\t\t\t});
EOF;
	if (strexists($html, $script_start . $add_num_acript . $script_end)) {
		$html = str_replace($script_start . $add_num_acript . $script_end, '<script type="text/javascript">' . $add_num_acript . '</script>', $html);
	}

	if (strexists($html, $script_start . $count_down_script . $script_end)) {
		$html = str_replace($script_start . $count_down_script . $script_end, '<script type="text/javascript">' . $count_down_script . '</script>', $html);
	}

	$link_error = '<li<x>nk href="./resource/components/swiper/swiper.min.css" rel="stylesheet">';
	if (strexists($html, $link_error)) {
		$html = str_replace($link_error, '<link href="./resource/components/swiper/swiper.min.css" rel="stylesheet">', $html);
	}
	$svg_start = 'xm<x>lns="http://www.w3.org/2000/svg" xm<x>lns:xli<x>nk="http://www.w3.org/1999/xli<x>nk"';
	$svg_end = 'xm<x>l:space="preserve" preserveAspectRatio="none">';
	if (strexists($html, $svg_start)) {
		$html = str_replace($svg_start, 'xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"', $html);
	}
	if (strexists($html, $svg_end)) {
		$html = str_replace($svg_end, 'xml:space="preserve" preserveAspectRatio="none">', $html);
	}

	return $html;
}

function template_page($id, $flag = TEMPLATE_DISPLAY) {
	global $_W;
	$page = table('site_page')->getById($id);
	if (empty($page)) {
		return error(1, 'Error: Page is not found');
	}
	if (empty($page['html'])) {
		return '';
	}

	$page['params'] = json_decode($page['params'], true);
	$GLOBALS['title'] = htmlentities($page['title'], ENT_QUOTES, 'UTF-8');
	$GLOBALS['_share'] = array('desc' => $page['description'], 'title' => $page['title'], 'imgUrl' => tomedia($page['params']['0']['params']['thumb']));

	$compile = IA_ROOT . "/data/tpl/app/{$id}.{$_W['template']}.tpl.php";
	$path = dirname($compile);
	if (!is_dir($path)) {
		load()->func('file');
		mkdirs($path);
	}
	$content = template_design($page['html']);

	$GLOBALS['bottom_menu'] = $page['params'][0]['property'][0]['params']['bottom_menu'] ? true : false;
	file_put_contents($compile, $content);
	switch ($flag) {
		case TEMPLATE_DISPLAY:
		default:
			extract($GLOBALS, EXTR_SKIP);
			template('common/header');
			include $compile;
			template('common/footer');
			break;
		case TEMPLATE_FETCH:
			extract($GLOBALS, EXTR_SKIP);
			ob_clean();
			ob_start();
			include $compile;
			$contents = ob_get_contents();
			ob_clean();
			return $contents;
			break;
		case TEMPLATE_INCLUDEPATH:
			return $compile;
			break;
	}
}

function template($filename, $flag = TEMPLATE_DISPLAY) {
	global $_W, $_GPC;
	$source = IA_ROOT . "/app/themes/{$_W['template']}/{$filename}.html";
	$compile = IA_ROOT . "/data/tpl/app/{$_W['template']}/{$filename}.tpl.php";
	if(!is_file($source)) {
		$compatFilename = template_compat($filename);
		if(!empty($compatFilename)) {
			return template($compatFilename, $flag);
		}
	}
	if(!is_file($source)) {
		$source = IA_ROOT . "/app/themes/default/{$filename}.html";
		$compile = IA_ROOT . "/data/tpl/app/default/{$filename}.tpl.php";
	}

	if(!is_file($source)) {
		exit("Error: template source '{$filename}' is not exist!");
	}
	$paths = pathinfo($compile);
	$compile = str_replace($paths['filename'], $_W['uniacid'] . '_' . intval($_GPC['t']) . '_' . $paths['filename'], $compile);

	if(DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
		template_compile($source, $compile);
	}
	switch ($flag) {
		case TEMPLATE_DISPLAY:
		default:
			extract($GLOBALS, EXTR_SKIP);
			include $compile;
			break;
		case TEMPLATE_FETCH:
			extract($GLOBALS, EXTR_SKIP);
			ob_clean();
			ob_start();
			include $compile;
			$contents = ob_get_contents();
			ob_clean();
			return $contents;
			break;
		case TEMPLATE_INCLUDEPATH:
			return $compile;
			break;
	}
}

function template_compile($from, $to) {
	global $_W;
	$path = dirname($to);
	if (!is_dir($path)) {
		load()->func('file');
		mkdirs($path);
	}
	$content = template_parse(file_get_contents($from));
	if (defined('IN_MODULE') &&
		$_W['os'] != 'mobile' &&
		module_get_direct_enter_status($_W['current_module']['name']) == STATUS_ON &&
		!preg_match('/\<script\>var we7CommonForModule.*document\.body\.appendChild\(we7CommonForModule\)\<\/script\>/', $content) &&
		!preg_match('/(footer|header|account\/welcome|module\/welcome)+/', $from)) {
		$extra_code = "<script>var we7CommonForModule = document.createElement(\"script\");we7CommonForModule.src = '//cdn.w7.cc/we7/w7windowside.js?v=" . IMS_RELEASE_DATE . "';document.body.appendChild(we7CommonForModule)
</script>";
		$content .= $extra_code;
	}
	file_put_contents($to, $content);
}

function template_parse($str) {
	load()->model('mc');
	$check_repeat_template = array(
		"'common\\/header'",
		"'common\\/footer'",
	);
	foreach ($check_repeat_template as $template) {
		if (preg_match_all('/{template\s+'.$template.'}/', $str, $match) > 1) {
			$replace = stripslashes($template);
			$str = preg_replace('/{template\s+'.$template.'}/i', '<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('.$replace.', TEMPLATE_INCLUDEPATH)) : (include template('.$replace.', TEMPLATE_INCLUDEPATH));?>', $str, 1);
			$str = preg_replace('/{template\s+'.$template.'}/i', '', $str);
		}
	}
	$str = preg_replace('/<!--{(.+?)}-->/s', '{$1}', $str);
	$str = preg_replace('/{template\s+(.+?)}/', '<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template($1, TEMPLATE_INCLUDEPATH)) : (include template($1, TEMPLATE_INCLUDEPATH));?>', $str);
	$str = preg_replace('/{php\s+(.+?)}/', '<?php $1?>', $str);
	$str = preg_replace('/{if\s+(.+?)}/', '<?php if($1) { ?>', $str);
	$str = preg_replace('/{else}/', '<?php } else { ?>', $str);
	$str = preg_replace('/{else ?if\s+(.+?)}/', '<?php } else if($1) { ?>', $str);
	$str = preg_replace('/{\/if}/', '<?php } ?>', $str);
	$str = preg_replace('/{loop\s+(\S+)\s+(\S+)}/', '<?php if(is_array($1)) { foreach($1 as $2) { ?>', $str);
	$str = preg_replace('/{loop\s+(\S+)\s+(\S+)\s+(\S+)}/', '<?php if(is_array($1)) { foreach($1 as $2 => $3) { ?>', $str);
	$str = preg_replace('/{\/loop}/', '<?php } } ?>', $str);
	$str = preg_replace('/{(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)}/', '<?php echo $1;?>', $str);
	$str = preg_replace('/{(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff\[\]\'\"\$]*)}/', '<?php echo $1;?>', $str);
	$str = preg_replace('/{url\s+(\S+)}/', '<?php echo url($1);?>', $str);
	$str = preg_replace('/{url\s+(\S+)\s+(array\(.+?\))}/', '<?php echo url($1, $2);?>', $str);
	$str = preg_replace('/{media\s+(\S+)}/', '<?php echo tomedia($1);?>', $str);
	$str = preg_replace_callback('/{data\s+(.+?)}/s', "moduledata", $str);
	$str = preg_replace_callback('/{hook\s+(.+?)}/s', "template_modulehook_parser", $str);
	$str = preg_replace('/{\/data}/', '<?php } } ?>', $str);
	$str = preg_replace('/{\/hook}/', '<?php ; ?>', $str);
	$str = preg_replace_callback('/<\?php([^\?]+)\?>/s', "template_addquote", $str);
	$str = preg_replace('/{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)}/s', '<?php echo $1;?>', $str);
	$str = str_replace('{##', '{', $str);
	$str = str_replace('##}', '}', $str);

	$business_stat_script = "</script><script type=\"text/javascript\" src=\"{$GLOBALS['_W']['siteroot']}app/index.php?i=" . mc_current_real_uniacid() . "&c=utility&a=visit&do=showjs&m={$GLOBALS['_W']['current_module']['name']}\">";
	if (!empty($GLOBALS['_W']['setting']['remote']['type'])) {
		$str = str_replace('</body>', "<script>var imgs = document.getElementsByTagName('img');for(var i=0, len=imgs.length; i < len; i++){imgs[i].onerror = function() {if (!this.getAttribute('check-src') && (this.src.indexOf('http://') > -1 || this.src.indexOf('https://') > -1)) {this.src = this.src.indexOf('{$GLOBALS['_W']['attachurl_local']}') == -1 ? this.src.replace('{$GLOBALS['_W']['attachurl_remote']}', '{$GLOBALS['_W']['attachurl_local']}') : this.src.replace('{$GLOBALS['_W']['attachurl_local']}', '{$GLOBALS['_W']['attachurl_remote']}');this.setAttribute('check-src', true);}}};{$business_stat_script}</script></body>", $str);
	} else {
		$str = str_replace('</body>', "<script>;{$business_stat_script}</script></body>", $str);
	}
	$str = "<?php defined('IN_IA') or exit('Access Denied');?>" . $str;
	return $str;
}

function template_addquote($matchs) {
	$code = "<?php {$matchs[1]}?>";
	$code = preg_replace('/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\](?![a-zA-Z0-9_\-\.\x7f-\xff\[\]]*[\'"])/s', "['$1']", $code);
	return str_replace('\\\"', '\"', $code);
}


function moduledata($params = '') {
	if (empty($params[1])) {
		return '';
	}
	$params = explode(' ', $params[1]);
	if (empty($params)) {
		return '';
	}
	$data = array();
	foreach ($params as $row) {
		$row = explode('=', $row);
		$data[$row[0]] = str_replace(array("'", '"'), '', $row[1]);
		$row[1] = urldecode($row[1]);
	}
	$funcname = $data['func'];
	$assign = !empty($data['assign']) ? $data['assign'] : $funcname;
	$item = !empty($data['item']) ? $data['item'] : 'row';
	$data['limit'] = !empty($data['limit']) ? $data['limit'] : 10;
	if (empty($data['return']) || $data['return'] == 'false') {
		$return = false;
	} else {
		$return = true;
	}
	$data['index'] = !empty($data['index']) ? $data['index'] : 'iteration';
	if (!empty($data['module'])) {
		$modulename = $data['module'];
	} else {
		list($modulename) = explode('_', $data['func']);
	}
	$data['multiid'] = intval($_GET['t']);
	$data['uniacid'] = intval($_GET['i']);
	$data['acid'] = intval($_GET['j']);

	if (empty($modulename) || empty($funcname)) {
		return '';
	}
	$variable = var_export($data, true);
	$variable = preg_replace("/'(\\$[a-zA-Z_\x7f-\xff\[\]\']*?)'/", '$1', $variable);
	$php = "<?php \${$assign} = modulefunc('$modulename', '{$funcname}', {$variable}); ";
	if (empty($return)) {
		$php .= "if(is_array(\${$assign})) { \$i=0; foreach(\${$assign} as \$i => \${$item}) { \$i++; \${$item}['{$data['index']}'] = \$i; ";
	}
	$php .= "?>";
	return $php;
}

function modulefunc($modulename, $funcname, $params) {
	static $includes;

	$includefile = '';
	if (!function_exists($funcname)) {
		if (!isset($includes[$modulename])) {
			if (!file_exists(IA_ROOT . '/addons/'.$modulename.'/model.php')) {
				return '';
			} else {
				$includes[$modulename] = true;
				include_once IA_ROOT . '/addons/'.$modulename.'/model.php';
			}
		}
	}

	if (function_exists($funcname)) {
		return call_user_func_array($funcname, array($params));
	} else {
		return array();
	}
}


function site_navs($params = array()) {
	global $_W, $multi, $cid, $ishomepage;
	$condition = array();
	if(!$cid || !$ishomepage) {
		if (!empty($params['section'])) {
			$condition['section'] = intval($params['section']);
		}
		if(empty($params['multiid'])) {
			load()->model('account');
			$setting = uni_setting($_W['uniacid']);
			$multiid = $setting['default_site'];
		} else{
			$multiid = intval($params['multiid']);
		}
		$condition['position'] = 1;
		$condition['status'] = 1;
		$condition['uniacid'] = $_W['uniacid'];
		$condition['multiid'] = $multiid;
		$fields = array('id', 'name', 'description', 'url', 'icon', 'css', 'position', 'module');
		$navs = table('site_nav')
			->where($condition)
			->select($fields)
			->orderby(array(
				'section' => 'ASC',
				'displayorder' => 'DESC',
				'id' => 'DESC'
			))
			->getall();
	} else {
		$condition = array(
			'parentid' => $cid,
			'enabled' => 1,
			'uniacid' => $_W['uniacid']
		);
		$navs = table('site_category')
			->where($condition)
			->orderby(array(
				'displayorder' => 'DESC',
				'id' => 'DESC'
			))
			->getall();
	}
	if(!empty($navs)) {
		foreach ($navs as &$row) {
			if(!$cid || !$ishomepage) {
				if (!strexists($row['url'], 'tel:') && !strexists($row['url'], '://') && !strexists($row['url'], 'www') && !strexists($row['url'], 'i=')) {
					$row['url'] .= strexists($row['url'], '?') ?  '&i='.$_W['uniacid'] : '?i='.$_W['uniacid'];
				}
			} else {
				if(empty($row['linkurl']) || (!strexists($row['linkurl'], 'http://') && !strexists($row['linkurl'], 'https://'))) {
					$row['url'] = murl('site/site/list', array('cid' => $row['id']));
				} else {
					$row['url'] = $row['linkurl'];
				}
			}
			$row['css'] = iunserializer($row['css']);
			if(empty($row['css']['icon']['icon'])){
				$row['css']['icon']['icon'] = 'fa fa-external-link';
			}
			$row['css']['icon']['style'] = "color:{$row['css']['icon']['color']};font-size:{$row['css']['icon']['font-size']}px;";
			$row['css']['name'] = "color:{$row['css']['name']['color']};";
			$row['html'] = '<a href="'.$row['url'].'" class="box-item">';
			$row['html'] .= '<i '.(!empty($row['icon']) ? "style=\"background:url('".tomedia($row['icon'])."') no-repeat;background-size:cover;\" class=\"icon\"" : "class=\"fa {$row['css']['icon']['icon']} \" style=\"{$row['css']['icon']['style']}\"").'></i>';
			$row['html'] .= "<span style=\"{$row['css']['name']}\" title=\"{$row['name']}\">{$row['name']}</span></a>";
		}
		unset($row);
	}
	return $navs;
}

function site_article($params = array()) {
	global $_GPC, $_W;
	extract($params);
	$pindex = max(1, intval($_GPC['page']));
	if (!isset($limit)) {
		$psize = 10;
	} else {
		$psize = intval($limit);
		$psize = max(1, $limit);
	}
	$result = array();
	$condition = " WHERE uniacid = :uniacid ";
	$pars = array(':uniacid' => $_W['uniacid']);
	if (!empty($cid)) {
		$category = table('site_category')->where(array('id' => $cid, 'enabled' => 1))->getcolumn('parentid');
		if (!empty($category)) {
			$condition .= " AND ccate = :ccate ";
			$pars[':ccate'] = $cid;
		} else {
			$condition .= " AND pcate = :pcate AND (ccate = :ccate OR iscommend = '1')";
			$pars[':pcate'] = $cid;
			$pars[':ccate'] = ARTICLE_CCATE;
		}
	} else {
		$category_list = table('site_category')
			->where(array(
				'uniacid' => $_W['uniacid'],
				'multiid' => $multiid
			))
			->getall('id');
		$category_list = implode(',', array_keys($category_list));
		if (!empty($category_list)) {
			$condition .= " AND (pcate IN (". $category_list .") OR ccate IN (". $category_list .") OR pcate = 0 AND ccate = 0)";
		}
	}
	if ($iscommend == 'true') {
		$condition .= " AND iscommend = '1'";
	}
	if ($ishot == 'true') {
		$condition .= " AND ishot = '1'";
	}
	$sql = "SELECT * FROM ".tablename('site_article'). $condition. ' ORDER BY displayorder DESC, id DESC LIMIT ' . ($pindex - 1) * $psize .',' .$psize;
	$result['list'] = pdo_fetchall($sql, $pars);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('site_article') . $condition, $pars);
	$result['pager'] = pagination($total, $pindex, $psize);
	if (!empty($result['list'])) {
		foreach ($result['list'] as &$row) {
			if(empty($row['linkurl'])) {
				$row['linkurl'] = murl('site/site/detail', array('id' => $row['id'], 'uniacid' => $_W['uniacid']));
			}
			$row['thumb'] = tomedia($row['thumb']);
		}
	}
	return $result;
}

function site_article_comment($params = array()) {
	global $_GPC, $_W;
	load()->model('article');
	extract($params);
	$pindex = max(1, intval($_GPC['page']));
	if (!isset($limit)) {
		$psize = 10;
	} else {
		$psize = intval($limit);
		$psize = max(1, $limit);
	}
	if(empty($article_id)){
		return array();
	}
	$comment_table = table('site_article_comment');
	$comment_table->searchWithArticleid($article_id);
	$comment_table->searchWithParentid(ARTICLE_COMMENT_DEFAULT);
	$comment_table->searchWithPage($pindex, $psize);
	$article_lists = $comment_table->getAllByUniacid();
	$article_lists = article_comment_detail($article_lists);
	$total = $comment_table->getLastQueryTotal();
	$result['list'] = $article_lists;
	$result['pager'] = pagination($total, $pindex, $psize);
	return $result;
}

function site_category($params = array()) {
	global $_GPC, $_W;
	extract($params);
	$where['uniacid'] = $_W['uniacid'];
	$where['enabled'] = 1;
	if (isset($parentid)) {
		$parentid = intval($parentid);
		$where['parentid'] = $parentid;
	}
	$category = array();

	$result = table('site_category')
		->where($where)
		->orderby(array(
			'parentid' => 'ASC',
			'displayorder' => 'ASC',
			'id' => 'ASC'
		))
		->getall();

	if (!empty($result)) {
		foreach ($result as $row) {
			if(empty($row['linkurl'])) {
				$row['linkurl'] = url('site/site/list', array('cid' =>$row['id']));
			}
			$row['icon'] = tomedia($row['icon']);
			$row['css'] = iunserializer($row['css']);
			if(empty($row['css']['icon']['icon'])){
				$row['css']['icon']['icon'] = 'fa fa-external-link';
			}
			$row['css']['icon']['style'] = "color:{$row['css']['icon']['color']};font-size:{$row['css']['icon']['font-size']}px;";
			$row['css']['name'] = "color:{$row['css']['name']['color']};";
			if (!isset($parentid)) {
				if (empty($row['parentid'])) {
					$category[$row['id']] = $row;
				} else {
					$category[$row['parentid']]['children'][$row['id']] = $row;
				}
			} else {
				$category[] = $row;
			}
		}
	}
	return $category;
}

function site_slide_search($params = array()) {
	global $_W;
	if(empty($params['limit'])) {
		$params['limit'] = 8;
	}
	if(empty($params['multiid'])) {
		$multiid = table('uni_settings')->where(array('uniacid' => $_W['uniacid']))->getcolumn('default_site');
	} else{
		$multiid = intval($params['multiid']);
	}

	$list = table('site_slide')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'multiid' => $multiid
		))
		->orderby(array(
			'displayorder' => 'DESC',
			'id' => 'DESC'
		))
		->getall();

	if(!empty($list)) {
		foreach($list as &$row) {
			if (!strexists($row['url'], './')) {
				if (!strexists($row['url'], 'http')) {
					$row['url'] = '//' . $row['url'];
				}
			}
			$row['thumb'] = tomedia($row['thumb']);
		}
	}
	return $list;
}

function app_slide($params = array()) {
	return site_slide_search($params);
}

function site_widget_link($params = array()) {
	$params['widgetdata'] = urldecode($params['widgetdata']);
	$widget = json_decode($params['widgetdata'], true);
	$widgetparams = !empty($widget['params']) ? $widget['params'] : array();

	$table = table('site_article');
	$where['uniacid'] = $widget['uniacid'];
	if (!empty($widgetparams['selectCate']['pid'])) {
		$where['pcate'] = $widgetparams['selectCate']['pid'];
	}
	if (!empty($widgetparams['selectCate']['cid'])) {
		$where['ccate'] = $widgetparams['selectCate']['cid'];
	}
	if (!empty($widgetparams['iscommend'])) {
		$where['iscommend'] = 1;
	}
	if (!empty($widgetparams['ishot'])) {
		$where['ishot'] = 1;
	}
	$table->where($where);
	if (!empty($widgetparams['isnew'])) {
		$table->orderby(array('id' => 'DESC'));
	}
	if (!empty($widgetparams['pageSize'])) {
		$limit = intval($widgetparams['pageSize']);
		$table->limit($limit);
	}
	$list = $table->getall();
	if (!empty($list)) {
		foreach ($list as $i => &$row) {
			$row['title'] = cutstr($row['title'], 20, true);
			$row['thumb_url'] = tomedia($row['thumb']);
			$row['url'] = url('site/site/detail', array('id' => $row['id']));
		}
		unset($row);
	}
	return (array)$list;
}

function site_quickmenu() {
	global $_W, $_GPC;

	if ($_GPC['c'] == 'mc' || $_GPC['c'] == 'activity') {
		$quickmenu = table('site_page')
			->select(array('html', 'params'))
			->where(array(
				'uniacid' => $_W['uniacid'],
				'type' => 4,
				'status' => 1
			))
			->get();

	} elseif ($_GPC['c'] == 'auth') {
		return false;
	} else {
		$multiid = intval($_GPC['t']);
		if (empty($multiid) && !empty($_GPC['__multiid'])) {
			$id = intval($_GPC['__multiid']);
			$site_multi_info = table('site_multi')->getById($id, $_W['uniacid']);
			$multiid = empty($site_multi_info) ? '' : $id;
		} else {
			if(!($_GPC['c'] == 'home' && $_GPC['a'] == 'page')){
				@isetcookie('__multiid', '');
			}
		}
		if (empty($multiid)) {
			$setting = uni_setting($_W['uniacid'], array('default_site'));
			$multiid = $setting['default_site'];
		}
		$quickmenu = table('site_page')
			->select(array('html', 'params'))
			->where(array(
				'multiid' => $multiid,
				'type' => 2,
				'status' => 1
			))
			->get();
	}
	if (empty($quickmenu)) {
		return false;
	}
	$quickmenu['params'] = json_decode($quickmenu['params'], true);
	if ($_GPC['c'] == 'home' && $_GPC['a'] != 'page' && empty($quickmenu['params']['position']['homepage'])) {
		return false;
	}
	if ($_GPC['c'] == 'home' && $_GPC['a'] == 'page' && empty($quickmenu['params']['position']['page'])) {
		return false;
	}
	if ($_GPC['c'] == 'site' && empty($quickmenu['params']['position']['article'])) {
		return false;
	}
	if (!empty($_GPC['m']) && !empty($quickmenu['params']['ignoreModules'][$_GPC['m']])) {
		return false;
	}

	echo template_design($quickmenu['html']);
	echo "<script type=\"text/javascript\">
	$('.js-quickmenu').find('a').each(function(){
		if ($(this).attr('href')) {
			var url = $(this).attr('href').replace('./', '');
			if (location.href.indexOf(url) > -1) {
				var onclass = $(this).find('i').attr('js-onclass-name');
				if (onclass) {
					$(this).find('i').attr('class', onclass);
					$(this).find('i').css('color', $(this).find('i').attr('js-onclass-color'));
				}
			}
		}
	});
</script>";
}

function template_modulehook_parser($params = array()) {
	load()->model('module');
	if (empty($params[1])) {
		return '';
	}
	$params = explode(' ', $params[1]);
	if (empty($params)) {
		return '';
	}
	$plugin = array();
	foreach ($params as $row) {
		$row = explode('=', $row);
		$plugin[$row[0]] = str_replace(array("'", '"'), '', $row[1]);
		$row[1] = urldecode($row[1]);
	}
	$plugin_info = module_fetch($plugin['module']);
	if (empty($plugin_info)) {
		return false;
	}

	if (empty($plugin['return']) || $plugin['return'] == 'false') {
			} else {
			}
	if (empty($plugin['func']) || empty($plugin['module'])) {
		return false;
	}

	if (defined('IN_SYS')) {
		$plugin['func'] = "hookWeb{$plugin['func']}";
	} else {
		$plugin['func'] = "hookMobile{$plugin['func']}";
	}

	$plugin_module = WeUtility::createModuleHook($plugin_info['name']);
	if (method_exists($plugin_module, $plugin['func']) && $plugin_module instanceof WeModuleHook) {
		$hookparams = var_export($plugin, true);
		if (!empty($hookparams)) {
			$hookparams = preg_replace("/'(\\$[a-zA-Z_\x7f-\xff\[\]\']*?)'/", '$1', $hookparams);
		} else {
			$hookparams = 'array()';
		}
		$php = "<?php \$plugin_module = WeUtility::createModuleHook('{$plugin_info['name']}');call_user_func_array(array(\$plugin_module, '{$plugin['func']}'), array('params' => {$hookparams})); ?>";
		return $php;
	} else {
		$php = "<!--模块 {$plugin_info['name']} 不存在嵌入点 {$plugin['func']}-->";
		return $php;
	}
}