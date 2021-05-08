<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

define('LOGGING_ERROR', 'error');
define('LOGGING_TRACE', 'trace');
define('LOGGING_WARNING', 'warning');
define('LOGGING_INFO', 'info');

function logging_run($log, $type = 'trace', $filename = 'run') {
	global $_W;
	$filename = IA_ROOT . '/data/logs/' . $filename . '_' . date('Ymd') . '.log';

	load()->func('file');
	mkdirs(dirname($filename));

	$logFormat = '%date %type %user %url %context';

	if (!empty($GLOBALS['_POST'])) {
		$context[] = logging_implode($GLOBALS['_POST']);
	}

	if (is_array($log)) {
		$context[] = logging_implode($log);
	} else {
		$context[] = preg_replace('/[ \t\r\n]+/', ' ', $log);
	}

	$log = str_replace(explode(' ', $logFormat), array(
		'[' . date('Y-m-d H:i:s', $_W['timestamp']) . ']',
		$type,
		$_W['username'],
		$_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'],
		implode("\n", $context),
	), $logFormat);

	file_put_contents($filename, $log . "\r\n", FILE_APPEND);

	return true;
}

function logging_implode($array, $skip = array()) {
	$return = '';
	if (is_array($array) && !empty($array)) {
		foreach ($array as $key => $value) {
			if (empty($skip) || !in_array($key, $skip, true)) {
				if (is_array($value)) {
					$return .= $key . '={' . logging_implode($value, $skip) . '}; ';
				} else {
					$return .= "$key=$value; ";
				}
			}
		}
	}

	return $return;
}