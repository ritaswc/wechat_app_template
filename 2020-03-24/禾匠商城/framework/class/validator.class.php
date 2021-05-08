<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */





class Validator {
	const IMG = 'jpg, jepg, png, gif, bmp'; 	const IMG_MIMETYPE = 'image/jpeg,image/jpeg,image/png,image/gif,image/bmp';

	private $defaults = array(
		'required' => ':attribute 必须填写',
		'integer' => ':attribute必须是整数',
		'int' => ':attribute必须是整数',
		'numeric' => ':attribute必须是数字',
		'string' => ':attribute必须是字符串',
		'json' => ':attribute 必须是json',
		'array' => ':attribute必须是数组',
		'min' => ':attribute不能小于%s',
		'max' => ':attribute不能大于%s',
		'between' => ':attribute 必须在 %s %s 范围内',
		'size' => ':attribute 大小必须是 %s',
		'url' => ':attribute不是有效的url', 		'email' => ':attribute不是有效的邮箱',
		'mobile' => ':attribute不是有效的手机号',
		'file' => ':attribute必须是一个文件',
		'image' => ':attribute必须是一个图片',
		'ip' => ':attribute不是有效的ip',
		'in' => ':attribute 必须在 %s 内',
		'notin' => ':attribute 不在 %s 内',
		'date' => ':attribute 必须是有效的日期',
		'after' => ':attribute 日期不能小于 %s',
		'before' => ':attribute 日期不能大于 %s',
		'regex' => ':attribute 不是有效的数据', 		'same' => ':attribute 和 %s 不一致', 		'bool' => ':attribute 必须是bool值',
		'path' => ':attribute 不是有效的路径',
	);
	
	private $custom = array();
	
	private $rules = array();
	
	private $messages = array();
	
	private $data = array();

	
	private $errors = array();

	public function __construct($data, $rules = array(), $messages = array()) {
		$this->data = $data;
		$this->rules = $this->parseRule($rules);
		$this->messages = $messages;
	}

	public static function create($data, $rules, array $messages = array()) {
		return new self($data, $rules, $messages);
	}

	
	public function addRule($name, callable $callable) {
		if (!$name) {
			throw new InvalidArgumentException('无效的参数');
		}
		if (!is_callable($callable)) {
			throw new InvalidArgumentException('无效的callable 对象');
		}
		$this->custom[$name] = $callable;
	}

	
	public function isError() {
		return 0 !== count($this->errors);
	}

	
	public function error() {
		return $this->errors;
	}

	
	public function message() {
		$init = array();
		$errmsg = array_reduce($this->error(), function ($result, $value) {
			return array_merge($result, array_values($value));
		}, $init);

		return implode(',', array_values($errmsg));
	}

	public function getData() {
		return $this->data;
	}

	
	protected function parseRule(array $rules) {
		$result = array();
		if (0 == count($rules)) {
			throw new InvalidArgumentException('无效的rules');
		}
		foreach ($rules as $key => $rule) {
			$result[$key] = $this->parseSingleRule($rule);
		}

		return $result;
	}

	
	protected function parseSingleRule($value) {
		if (is_string($value)) {
			$rules = explode('|', $value);
			$result = array();
			foreach ($rules as $dataKey => $rule) {
				$kv = explode(':', $rule);
				$params = array();
				if (count($kv) > 1) {
					$params = explode(',', $kv[1]);
				}
				$result[] = array('name' => $kv[0], 'params' => $params);
			}

			return $result;
		}
		if (is_array($value)) {
						$value = array_map(function ($item) {
				if (is_string($item)) {
					$name_params = explode(':', $item);
					$params = array();
					if (count($name_params) > 1) {
						$params = explode(',', $name_params[1]);
					}

					return array('name' => $name_params[0], 'params' => $params);
				}
				if (!is_array($item)) {
					throw new InvalidArgumentException('无效的rule参数');
				}
				$newitem = $item;
				if (!isset($item['name'])) {
					$newitem = array();
					$newitem['name'] = $newitem[0];
					$newitem['params'] = count($item) > 1 ? $item[1] : array();
				}

				return $newitem;
			}, $value);

			return $value;
		}
		throw new InvalidArgumentException('无效的rule配置项');
	}

	private function getRules($key) {
		return isset($this->rules[$key]) ? $this->rules[$key] : array();
	}

	public function valid() {
		$this->errors = array();
		foreach ($this->data as $key => $value) {
			$rules = $this->getRules($key);
			foreach ($rules as $rule) {
				$this->doValid($key, $value, $rule);
			}
		}

		return $this->isError() ? error(1, $this->message()) : error(0);
	}

	
	private function doSingle($callback, $dataKey, $value, $rule) {
		$valid = call_user_func($callback, $dataKey, $value, $rule['params']);
		if (!$valid) {
			$this->errors[$dataKey][$rule['name']] = $this->getMessage($dataKey, $rule);

			return false;
		}

		return true;
	}

	
	private function doCustom($callback, $dataKey, $value, $rule) {
		$valid = call_user_func($callback, $dataKey, $value, $rule['params'], $this);
		if (!$valid) {
			$this->errors[$dataKey][$rule['name']] = $this->getMessage($dataKey, $rule);

			return false;
		}

		return true;
	}

	
	private function doValid($dataKey, $value, $rule) {
		$ruleName = $rule['name'];
		if (isset($this->defaults[$ruleName])) {
			$callback = array($this, 'valid' . ucfirst($ruleName));

			return $this->doSingle($callback, $dataKey, $value, $rule);
		}
		if (isset($this->custom[$ruleName])) {
			$callback = $this->custom[$ruleName];

			return $this->doCustom($callback, $dataKey, $value, $rule, $this);
		}
		throw new InvalidArgumentException('valid' . $rule['name'] . ' 方法未定义');
	}

	
	private function getValue($key) {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}

	protected function getMessage($dataKey, $rule) {
		$message = $this->getErrorMessage($dataKey, $rule['name']);
		if ($message) {
			$message = str_replace(':attribute', $dataKey, $message);
			$message = vsprintf($message, $rule['params']); 		}

		return $message;
	}

	protected function getErrorMessage($dataKey, $ruleName) {
		$dr = $dataKey . '.' . $ruleName;
		if ($this->messages[$dr]) {
			return $this->messages[$dr];
		}
		if (isset($this->messages[$dataKey])) {
			return $this->messages[$dataKey];
		}

		return isset($this->defaults[$ruleName]) ? $this->defaults[$ruleName] : '错误';
	}

	
	public function validRequired($key, $value, $params) {
		if (is_null($value)) {
			return false;
		}

		if (is_array($value)) {
			return 0 != count($value);
		}

		if (is_string($value)) {
			return '' !== $value;
		}

		return true;
	}

	public function validInteger($key, $value, $params) {
		return false !== filter_var($value, FILTER_VALIDATE_INT);
	}

	public function validInt($key, $value, $params) {
		return $this->validInteger($key, $value, $params);
	}

	public function validNumeric($key, $value, $params) {
		return is_numeric($value);
	}

	public function validString($key, $value, $params) {
		return is_string($value);
	}

	public function validJson($key, $value, $params) {
		if (!is_scalar($value) && !method_exists($value, '__toString')) {
			return false;
		}

		json_decode($value);

		return JSON_ERROR_NONE === json_last_error();
	}

	
	public function validArray($key, $value, $params) {
		return is_array($value);
	}

	
	public function validFile($key, $value, $params) {
		return is_file($value);
	}

	public function validImage($key, $value, $params) {
		return $this->isImage($value);
	}

	public function validEmail($key, $value, $params) {
		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}

	public function validMobile($key, $value, $params) {
		return $this->validRegex($key, $value, array('/^1[34578]\d{9}$/'));
	}

	
	public function validRegex($key, $value, $params) {
		$this->checkParams(1, $params, 'regex');

		return preg_match($params[0], $value);
	}

	
	public function validIp($key, $value, $params) {
		if (!is_null($value)) {
			return filter_var($value, FILTER_VALIDATE_IP);
		}

		return false;
	}

	
	public function validSize($key, $value, $params) {
		$this->checkParams(1, $params, 'size');

		return $this->getSize($key, $value) == $params[0];
	}

	
	public function validMax($key, $value, $params) {
		$this->checkParams(1, $params, 'max');
		$size = $this->getSize($key, $value);

		return $size <= $params[0];
	}

	
	public function validMin($key, $value, $params) {
		$this->checkParams(1, $params, 'min');
		$size = $this->getSize($key, $value);

		return $size >= $params[0];
	}

	public function validUrl($key, $value, $params) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			return false;
		}
		
		$parseData = parse_url($value);
		$scheme = $parseData['scheme'];
		$allowSchemes = array('http', 'https');
		if (!in_array($scheme, $allowSchemes)) { 			return false;
		}
		if (!isset($parseData['host'])) {
			return false;
		}
		$host = $parseData['host'];
		if (strexists($host, '@')) {
			return false;
		}
		$pattern = '/^(10|172|192|127)/'; 		if (preg_match($pattern, $host)) {
			return false;
		}

		return parse_path($value);
	}

	public function validDate($key, $value, $params) {
		return $this->checkDate($value);
	}

	public function validIn($key, $value, $params) {
		if (is_array($params)) {
			return in_array($value, $params, true);
		}

		return false;
	}

	public function validNotin($key, $value, $params) {
		return !$this->validIn($key, $value, $params);
	}

	
	public function validSame($key, $value, $params) {
		$this->checkParams(1, $params, 'same');
		$otherField = $params[0];
		$otherValue = isset($this->data[$otherField]) ? $this->data[$otherField] : null;

		return (is_string($value) || is_numeric($value)) && $value === $otherValue;
	}

	public function validBetween($key, $value, $params) {
		$this->checkParams(2, $params, 'between');
		$size = $this->getSize($key, $value);

		return $size >= $params[0] && $size <= $params[1];
	}

	
	public function validAfter($key, $value, $params) {
		$this->checkParams(1, $params, 'afterdate');
		$date = $params[0]; 		return $this->compareDate($value, $date, '>');
	}

	
	public function validBefore($key, $value, $params) {
		$this->checkParams(1, $params, 'beforedate');
		$date = $params[0]; 		return $this->compareDate($value, $date, '<');
	}

	private function compareDate($value, $param, $operator = '=') {
		if (!$this->checkDate($param)) {
			$param = $this->getValue($param);
		}
		if ($this->checkDate($value) && $this->checkDate($param)) {
			$currentTime = $this->getDateTimestamp($value);
			$paramTime = $this->getDateTimestamp($param);

			return $this->compare($currentTime, $paramTime, $operator);
		}

		return false;
	}

	
	public function validBool($key, $value, $params) {
		$acceptable = array(true, false, 0, 1, '0', '1');

		return in_array($value, $acceptable, true);
	}

	
	public function validPath($key, $value, $params) {
		return parse_path($value);
	}

	protected function getSize($key, $value) {
		if (is_numeric($value)) {
			return $value;
		} elseif (is_array($value)) {
			return count($value);
		} elseif (is_file($value)) {
			return filesize($value) / 1024;
		} elseif ($value instanceof SplFileInfo) {
			return $value->getSize() / 1024;
		} elseif (is_string($value)) {
			return mb_strlen($value);
		}

		return false;
	}

	private function isImage($value) {
		if (is_file($value)) {
			$filename = $value;
			if ($value instanceof SplFileInfo) {
				$filename = $value->getFilename();
			}
			if (is_string($filename)) {
				$pathinfo = pathinfo($filename);
				$extension = strtolower($pathinfo['extension']);

				return !empty($extension) && in_array($extension, array('jpg', 'jpeg', 'gif', 'png'));
			}
		}

		return false;
	}

	private function mimeTypeIsImage($mimeType) {
		$imgMimeType = explode(',', static::IMG_MIMETYPE);

		return in_array($mimeType, $imgMimeType);
	}

	
	private function checkDate($value) {
		if ($value instanceof DateTimeInterface) {
			return true;
		}
		if ((!is_string($value) && !is_numeric($value)) || false === strtotime($value)) {
			return false;
		}
		$date = date_parse($value);

		return checkdate($date['month'], $date['day'], $date['year']);
	}

	private function checkParams($count, $params, $ruleName) {
		if (count($params) != $count) {
			throw new InvalidArgumentException("$ruleName 参数个数必须为 $count 个");
		}
	}

	private function getDateTimestamp($date) {
		return $date instanceof DateTimeInterface ? $date->getTimestamp() : strtotime($date);
	}

	
	protected function compare($first, $second, $operator) {
		switch ($operator) {
			case '<':
				return $first < $second;
			case '>':
				return $first > $second;
			case '<=':
				return $first <= $second;
			case '>=':
				return $first >= $second;
			case '=':
				return $first == $second;
			default:
				throw new InvalidArgumentException();
		}
	}
}
