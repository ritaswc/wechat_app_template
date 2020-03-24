<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function template($filename, $flag = TEMPLATE_DISPLAY) {
	global $_W;
	$source = IA_ROOT . "/web/themes/{$_W['template']}/{$filename}.html";
	$compile = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$filename}.tpl.php";
	if (!is_file($source)) {
		$source = IA_ROOT . "/web/themes/default/{$filename}.html";
		$compile = IA_ROOT . "/data/tpl/web/default/{$filename}.tpl.php";
	}

	if (!is_file($source)) {
		echo "template source '{$filename}' is not exist!";

		return '';
	}
	if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
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
			ob_flush();
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


function template_compile($from, $to, $inmodule = false) {
	global $_W;
	$path = dirname($to);
	if (!is_dir($path)) {
		load()->func('file');
		mkdirs($path);
	}
	$content = template_parse(file_get_contents($from), $inmodule);
	if (IMS_FAMILY == 'x' && !preg_match('/(footer|header|account\/welcome|login|register|home\/welcome|cloud\/upgrade|cloud\/sms)+/', $from)) {
		$content = str_replace('微擎', '系统', $content);
	}
	if (defined('IN_MODULE') &&
		STATUS_ON == module_get_direct_enter_status($_W['current_module']['name']) &&
		!preg_match('/\<script\>var we7CommonForModule.*document\.body\.appendChild\(we7CommonForModule\)\<\/script\>/', $content) &&
		!preg_match('/(footer|header|account\/welcome|module\/welcome)+/', $from)) {
		$extra_code = "<script>var we7CommonForModule = document.createElement(\"script\");we7CommonForModule.src = '//cdn.w7.cc/we7/w7windowside.js?v=" . IMS_RELEASE_DATE . "';document.body.appendChild(we7CommonForModule)
</script>";
		$content .= $extra_code;
	}
	file_put_contents($to, $content);
}


function template_parse($str, $inmodule = false) {
	$str = preg_replace('/<!--{(.+?)}-->/s', '{$1}', $str);
	$str = preg_replace('/{template\s+(.+?)}/', '<?php (!empty($this) && $this instanceof WeModuleSite || ' . intval($inmodule) . ') ? (include $this->template($1, TEMPLATE_INCLUDEPATH)) : (include template($1, TEMPLATE_INCLUDEPATH));?>', $str);
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
	$str = preg_replace_callback('/<\?php([^\?]+)\?>/s', 'template_addquote', $str);
	$str = preg_replace_callback('/{hook\s+(.+?)}/s', 'template_modulehook_parser', $str);
	$str = preg_replace('/{\/hook}/', '<?php ; ?>', $str);
	$str = preg_replace('/{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)}/s', '<?php echo $1;?>', $str);
	$str = str_replace('{##', '{', $str);
	$str = str_replace('##}', '}', $str);
	if (!empty($GLOBALS['_W']['setting']['remote']['type'])) {
		$str = str_replace('</body>', "<script>$(function(){\$('img').attr('onerror', '').on('error', function(){if (!\$(this).data('check-src') && (this.src.indexOf('http://') > -1 || this.src.indexOf('https://') > -1)) {this.src = this.src.indexOf('{$GLOBALS['_W']['attachurl_local']}') == -1 ? this.src.replace('{$GLOBALS['_W']['attachurl_remote']}', '{$GLOBALS['_W']['attachurl_local']}') : this.src.replace('{$GLOBALS['_W']['attachurl_local']}', '{$GLOBALS['_W']['attachurl_remote']}');\$(this).data('check-src', true);}});});</script></body>", $str);
	}
	$str = "<?php defined('IN_IA') or exit('Access Denied');?>" . $str;

	return $str;
}

function template_addquote($matchs) {
	$code = "<?php {$matchs[1]}?>";
	$code = preg_replace('/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\](?![a-zA-Z0-9_\-\.\x7f-\xff\[\]]*[\'"])/s', "['$1']", $code);

	return str_replace('\\\"', '\"', $code);
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

	if (empty($plugin['return']) || 'false' == $plugin['return']) {
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