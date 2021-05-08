<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('cloud');
load()->func('communication');

class CloudApi {
	
	private $url = 'http://127.0.0.1/index.php?c=%s&a=%s&access_token=%s&';
	private $development = false;
	private $module = null;
	private $sys_call = false;
	private $default_token = '91ec1f9324753048c0096d036a694f86';

	const ACCESS_TOKEN_EXPIRE_IN = 7200;
	
	/**
	 * 开发模式
	 * @param boolean $development 是否开发模式, 默认为非开发模式
	 */
	
	public function __construct($development = false) {
		if (!defined('MODULE_ROOT')) {
			$this->sys_call = true;
			$this->module = 'core';
		} else {
			$this->sys_call = false;
			$this->module = pathinfo(MODULE_ROOT, PATHINFO_BASENAME);
		}
		$this->development = !is_error($this->developerCerContent());
	}

	private function getCerContent($file) {
		$cer_filepath = $this->cer_filepath($file);
		if (is_file($cer_filepath)) {
			$cer = file_get_contents($cer_filepath);
			if (!empty($cer)) {
				return $cer;
			}
		}

		return error(1, '获取访问云API的授权数字证书失败.');
	}

	private function developerCerContent() {
		$cer = $this->getCerContent('developer.cer');
		if (is_error($cer)) {
			return error(1, '访问云API获取授权失败,模块中没有开发者数字证书!');
		}

		return $cer;
	}

	private function cer_filepath($file) {
		if (defined('MODULE_ROOT')) {
			return MODULE_ROOT . '/' . $file;
		}

		return $file;
	}

	private function moduleCerContent() {
		$cer_filename = 'module.cer';
		$cer_filepath = $this->cer_filepath($cer_filename);

		if (is_file($cer_filepath)) {
			$expire_time = filemtime($cer_filepath) + self::ACCESS_TOKEN_EXPIRE_IN - 200;
			if (TIMESTAMP > $expire_time) {
				unlink($cer_filepath);
			}
		}

		if (!is_file($cer_filepath)) {
			$pars = _cloud_build_params();
			$pars['method'] = 'api.oauth';
			$pars['module'] = $this->module;
			$data = cloud_request('http://127.0.0.1/gateway.php', $pars);
			if (is_error($data)) {
				return $data;
			}
			$data = json_decode($data['content'], true);
			if (is_error($data)) {
				return $data;
			}
		}

		$cer = $this->getCerContent($cer_filename);
		if (is_error($cer)) {
			return error(1, '访问云API获取授权失败,模块中未发现数字证书(module.cer).');
		}

		return $cer;
	}

	private function systemCerContent() {
		global $_W;
		if (empty($_W['setting']['site'])) {
			return $this->default_token;
		}

		$cer_filename = 'module.cer';
		$cer_filepath = IA_ROOT . '/framework/builtin/core/module.cer';

		load()->func('file');
		$we7_team_dir = dirname($cer_filepath);
		if (!is_dir($we7_team_dir)) {
			mkdirs($we7_team_dir);
		}

		if (is_file($cer_filepath)) {
			$expire_time = filemtime($cer_filepath) + self::ACCESS_TOKEN_EXPIRE_IN - 200;
			if (TIMESTAMP > $expire_time) {
				unlink($cer_filepath);
			}
		}

		if (!is_file($cer_filepath)) {
			$pars = _cloud_build_params();
			$pars['method'] = 'api.oauth';
			$pars['module'] = $this->module;
			$data = cloud_request('http://127.0.0.1/gateway.php', $pars);
			if (is_error($data)) {
				return $data;
			}
			$data = json_decode($data['content'], true);
			if (is_error($data)) {
				return $data;
			}
		}
		if (is_file($cer_filepath)) {
			$cer = file_get_contents($cer_filepath);
			if (is_error($cer)) {
				return error(1, '访问云API获取授权失败,模块中未发现数字证书(module.cer).');
			}

			return $cer;
		} else {
			return $this->default_token;
		}
	}

	private function deleteModuleCer() {
		$cer_filename = 'module.cer';
		$cer_filepath = $this->cer_filepath($cer_filename);
		if (is_file($cer_filepath)) {
			unlink($cer_filepath);
		}
	}

	private function getAccessToken() {
		global $_W;
		if ($this->sys_call) {
			$token = $this->systemCerContent();
		} else {
			if ($this->development) {
				$token = $this->developerCerContent();
			} else {
				$token = $this->moduleCerContent();
			}
		}
		if (empty($token)) {
			return error(1, '错误的数字证书内容.');
		}
		if (is_error($token)) {
			return $token;
		}

		$access_token = array(
			'token' => $token,
			'module' => $this->module,
		);

		return base64_encode(json_encode($access_token));
	}

	public function url($api, $method, $params = array(), $dataType = 'json') {
	    global $_W;
		$access_token = $this->getAccessToken();
		if (is_error($access_token)) {
			return $access_token;
		}
		if (empty($params) || !is_array($params)) {
			$params = array();
		}

		$url = sprintf($this->url, $api, $method, $access_token);
		if (!empty($dataType)) {
			$url .= "&dataType={$dataType}";
		}
		$params['siteurl'] = $_W['siteroot'];
		if (!empty($params)) {
			$querystring = base64_encode(json_encode($params));
			$url .= "&api_qs={$querystring}";
		}

		if (strlen($url) > 2800) {
			return error(1, 'url query string too long');
		}

		return $url;
	}

	private function actionResult($result, $dataType = 'json') {
		if ('html' == $dataType) {
			return $result;
		}

		if ('json' == $dataType) {
			$result = strval($result);
			$json_result = json_decode($result, true);
			if (is_null($json_result)) {
				$json_result = error(1, '返回结果不是有效的JSON');
			}
			if (is_error($json_result)) {
				if (10000 == $json_result['errno']) {
					$this->deleteCer();
					$this->deleteModuleCer();
				}

				return $json_result;
			}

			return $json_result;
		}

		return $result;
	}

	public function get($api, $method, $url_params = array(), $dataType = 'json', $with_cookie = true) {
		$url = $this->url($api, $method, $url_params, $dataType);
		if (is_error($url)) {
			return $url;
		}

		$response = ihttp_get($url);

		if (is_error($response)) {
						return $response;
		}

		if ($with_cookie) {
			$ihttp_options = array();
			if ($response['headers'] && $response['headers']['Set-Cookie']) {
				$cookiejar = $response['headers']['Set-Cookie'];
			}
			if (!empty($cookiejar)) {
				if (is_array($cookiejar)) {
					$ihttp_options['CURLOPT_COOKIE'] = implode('; ', $cookiejar);
				} else {
					$ihttp_options['CURLOPT_COOKIE'] = $cookiejar;
				}
			}

			$response = ihttp_request($url, array(), $ihttp_options);
			if (is_error($response)) {
				return $response;
			}
		}
		$result = $this->actionResult($response['content'], $dataType);

		return $result;
	}

	public function post($api, $method, $post_params = array(), $dataType = 'json', $with_cookie = true) {
		$url = $this->url($api, $method, array(), $dataType);
		if (is_error($url)) {
			return $url;
		}
		$ihttp_options = array();

		if ($with_cookie) {
			$response = ihttp_get($url);
			if (is_error($response)) {
				return $response;
			}
			$ihttp_options = array();
			if ($response['headers'] && $response['headers']['Set-Cookie']) {
				$cookiejar = $response['headers']['Set-Cookie'];
			}
			if (!empty($cookiejar)) {
				if (is_array($cookiejar)) {
					$ihttp_options['CURLOPT_COOKIE'] = implode('; ', $cookiejar);
				} else {
					$ihttp_options['CURLOPT_COOKIE'] = $cookiejar;
				}
			}
		}
		$response = ihttp_request($url, $post_params, $ihttp_options);
		if (is_error($response)) {
			return $response;
		}
		if ('binary' == $dataType) {
			return $response;
		}

		return $this->actionResult($response['content'], $dataType);
	}

	public function deleteCer() {
		if ($this->sys_call) {
			$cer_filepath = IA_ROOT . '/framework/builtin/core/module.cer';
			if (is_file($cer_filepath)) {
				unlink($cer_filepath);
			}
		}
	}
}
