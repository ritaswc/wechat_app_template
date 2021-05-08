<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class UserapiModuleProcessor extends WeModuleProcessor {
	private function procLocal($item) {
		$local = basename($item['apiurl']);
		$file = IA_ROOT . '/framework/builtin/userapi/api/' . $local;
		load()->func('communication');
		if (!file_exists($file)) {
			return array();
		}

		return include $file;
	}

	private function procRemote($item) {
		load()->func('communication');
		if (!strexists($item['apiurl'], '?')) {
			$item['apiurl'] .= '?';
		} else {
			$item['apiurl'] .= '&';
		}

		$sign = array(
			'timestamp' => TIMESTAMP,
			'nonce' => random(10, 1),
		);
		$signkey = array($item['token'], $sign['timestamp'], $sign['nonce']);
		sort($signkey, SORT_STRING);
		$sign['signature'] = sha1(implode($signkey));
		$item['apiurl'] .= http_build_query($sign, '', '&');
		
		$body = '<xml>' . PHP_EOL .
			"<ToUserName><![CDATA[{$this->message['to']}]]></ToUserName>" . PHP_EOL .
			"<FromUserName><![CDATA[{$this->message['from']}]]></FromUserName>" . PHP_EOL .
			"<CreateTime>{$this->message['time']}</CreateTime>" . PHP_EOL .
			'<MsgType><![CDATA[text]]></MsgType>' . PHP_EOL .
			"<Content><![CDATA[{$this->message['content']}]]></Content>" . PHP_EOL .
			'<MsgId>' . TIMESTAMP . '</MsgId>' . PHP_EOL .
			'</xml>';
		$response = ihttp_request($item['apiurl'], $body, array('CURLOPT_HTTPHEADER' => array('Content-Type: text/xml; charset=utf-8')));
		$result = array();
		if (!is_error($response)) {
			$temp = @json_decode($response['content'], true);
			if (is_array($temp)) {
				$result = $this->buildResponse($temp);
			} else {
				if (!empty($response['content'])) {
					$obj = @isimplexml_load_string(trim($response['content']), 'SimpleXMLElement', LIBXML_NOCDATA);
					if ($obj instanceof SimpleXMLElement) {
						$type = strtolower(strval($obj->MsgType));
						if ('text' == $type) {
							$result = $this->respText(strval($obj->Content));
						}
						if ('image' == $type) {
							$imid = strval($obj->Image->MediaId);
							$result = $this->respImage($imid);
						}
						if ('voice' == $type) {
							$imid = strval($obj->Voice->MediaId);
							$result = $this->respVoice($imid);
						}
						if ('video' == $type) {
							$video = array();
							$video['video'] = strval($obj->Video->MediaId);
							$video['thumb'] = strval($obj->Video->ThumbMediaId);
							$result = $this->respVideo($video);
						}
						if ('music' == $type) {
							$music = array();
							$music['title'] = strval($obj->Music->Title);
							$music['description'] = strval($obj->Music->Description);
							$music['musicurl'] = strval($obj->Music->MusicUrl);
							$music['hqmusicurl'] = strval($obj->Music->HQMusicUrl);
							$result = $this->respMusic($music);
						}
						if ('news' == $type) {
							$news = array();
							foreach ($obj->Articles->item as $item) {
								$news[] = array(
									'title' => strval($item->Title),
									'description' => strval($item->Description),
									'picurl' => strval($item->PicUrl),
									'url' => strval($item->Url),
								);
							}
							$result = $this->respNews($news);
						}
					}
				}
			}
			if (false !== @stristr($result, '{begin-context}')) {
				$this->beginContext(0);
				$result = str_ireplace('{begin-context}', '', $result);
			}
			if (false !== @stristr($result, '{end-context}')) {
				$this->endContext();
				$result = str_ireplace('{end-context}', '', $result);
			}

			return $result;
		} else {
			return array();
		}
	}

	public function respond() {
		global $_W;
		$rid = $this->rule;
		$rule = table('rule')->getById($rid, $_W['uniacid']);
		$cfg = $this->module['config'];
		if ('0' == $rule['uniacid'] && empty($cfg[$rid])) {
			return '';
		}
		if ($this->inContext) {
			$rid = $_SESSION['__userapi-rid'];
		}
		$item = array();
		if (!empty($rid)) {
			$item = table('userapi_reply')->where(array('rid' => $rid))->orderby(array('id' => 'DESC'))->get();
			if (empty($item['id'])) {
				return array();
			}
		}
		if (empty($item)) {
			$module = $_W['modules']['userapi'];
			$module['settings'] = iunserializer($module['settings']);
			$item['apiurl'] = $module['settings']['apiurl'];
			$item['default-text'] = $module['settings']['default'];
		}
		if ($item['cachetime'] > 0) {
			$key = md5($item['id'] . $this->message['from']);
			$cache = table('userapi_cache')->where('key', $key)->get();
			if (!empty($cache) && TIMESTAMP - $cache['lastupdate'] <= $item['cachetime']) {
				return iunserializer($cache['content']);
			}
		}
		$result = array();
		if (!strexists($item['apiurl'], 'http://') && !strexists($item['apiurl'], 'https://')) {
			$result = $this->procLocal($item);
		} else {
			$result = $this->procRemote($item);
		}
		if (empty($result) && !empty($item['default_text'])) {
			$result = $this->respText($item['default_text']);
		}
		if (!empty($result) && is_array($result)) {
			$result['FromUserName'] = $this->message['to'];
			$result['ToUserName'] = $this->message['from'];

			if ($item['cachetime'] > 0) {
				if (empty($cache)) {
					table('userapi_cache')
						->fill(array(
							'key' => $key,
							'content' => iserializer($result),
							'lastupdate' => TIMESTAMP
						))
						->save();
				} else {
					table('userapi_cache')
						->where(array('key' => $key))
						->fill(array(
							'content' => iserializer($result),
							'lastupdate' => TIMESTAMP
						))
						->save();
				}
			}
		}

		return $result;
	}

	private function buildResponse($data = array()) {
		$result = array();
		$result['MsgType'] = $data['type'];
		$data = $data['content'];

		if ('text' == $result['MsgType']) {
			$result['Content'] = $data;
		} elseif ('news' == $result['MsgType']) {
			$result['ArticleCount'] = $data['ArticleCount'];
			$result['Articles'] = array();
			if (!isset($data[0])) {
				$temp[0] = $data;
				$data = $temp;
			}
			foreach ($data as $row) {
				$result['Articles'][] = array(
					'Title' => $row['Title'],
					'Description' => $row['Description'],
					'PicUrl' => $row['PicUrl'],
					'Url' => $row['Url'],
					'TagName' => 'item',
				);
			}
		} elseif ('music' == $result['MsgType']) {
			$result['Music'] = array(
				'Title' => $data['Title'],
				'Description' => $data['Description'],
				'MusicUrl' => $data['MusicUrl'],
				'HQMusicUrl' => $data['HQMusicUrl'],
			);
		}

		return $result;
	}

	protected function beginContext($expire = 3600) {
		if (!$this->inContext) {
			$_SESSION['__userapi-rid'] = $this->rule;
			parent::beginContext($expire);
		}
	}
}
