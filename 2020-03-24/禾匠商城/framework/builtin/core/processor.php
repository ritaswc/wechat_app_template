<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class CoreModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$reply_type = $this->reply_type;
		$key = array_rand($reply_type);
		$type = $reply_type[$key];
		switch ($type) {
			case 'basic':
				$result = $this->basic_respond();

				return $this->respText($result);
				break;
			case 'images':
				$result = $this->image_respond();

				return $this->respImage($result);
				break;
			case 'music':
				$result = $this->music_respond();

				return $this->respMusic(array(
						'Title' => $result['title'],
						'Description' => $result['description'],
						'MusicUrl' => $result['url'],
						'HQMusicUrl' => $result['hqurl'],
					));
				break;
			case 'news':
				$result = $this->news_respond();

				return $this->respNews($result);
				break;
			case 'voice':
				$result = $this->voice_respond();

				return $this->respVoice($result);
				break;
			case 'video':
				$result = $this->video_respond();

				return $this->respVideo(array(
						'MediaId' => $result['mediaid'],
						'Title' => $result['title'],
						'Description' => $result['description'],
					));
				break;
		}
	}

	private function basic_respond() {
		$rids = !is_array($this->rule) ? explode(',', $this->rule) : $this->rule;
		$reply = table('basic_reply')->where(array('rid IN' => $rids))->orderby('RAND()')->get();
		if (empty($reply)) {
			return false;
		}
		$reply['content'] = htmlspecialchars_decode($reply['content']);
				$reply['content'] = str_replace(array('<br>', '&nbsp;'), array("\n", ' '), $reply['content']);
		$reply['content'] = strip_tags($reply['content'], '<a>');

		return $reply['content'];
	}

	private function image_respond() {
		global $_W;
		$rid = $this->rule;
		$mediaid = table('images_reply')->where(array('rid' => $rid))->orderby('RAND()')->getcolumn('mediaid');
		if (empty($mediaid)) {
			return false;
		}

		return $mediaid;
	}

	private function music_respond() {
		global $_W;
		$rid = $this->rule;
		$item = table('music_reply')->where(array('rid' => $rid))->orderby('RAND()')->get();
		if (empty($item['id'])) {
			return false;
		}

		return $item;
	}

	private function news_respond() {
		global $_W;
		load()->model('material');
		$rid = $this->rule;
		$commends = table('news_reply')
			->where(array('rid' => $rid, 'parent_id' => -1))
			->orderby(array('displayorder' => 'DESC', 'id' => 'ASC'))
			->limit(8)
			->getall();
		if (empty($commends)) {
						$main = table('news_reply')
				->where(array(
					'rid' => $rid,
					'parent_id' => 0
				))
				->orderby('RAND()')
				->get();
			if (empty($main['id'])) {
				return false;
			}
			$commends = table('news_reply')
				->where(array('id' => $main['id']))
				->whereor(array('parent_id' => $main['id']))
				->orderby(array(
					'displayorder' => 'ASC',
					'id' => 'ASC'
				))
				->limit(8)
				->getall();
		}
		if (empty($commends)) {
			return false;
		}
		$news = array();
		if (!empty($commends[0]['media_id'])) {
			$news = material_build_reply($commends[0]['media_id']);
		}
		foreach ($commends as $key => $commend) {
			$row = array();
			if (!empty($commend['media_id'])) {
				if (empty($news[$key]['url'])) {
					$news[$key]['url'] = $this->createMobileUrl('detail', array('id' => $commend['id']));
				}
			} else {
				$row['title'] = $commend['title'];
				$row['description'] = $commend['description'];
				!empty($commend['thumb']) && $row['picurl'] = tomedia($commend['thumb']);
				$row['url'] = empty($commend['url']) ? $this->createMobileUrl('detail', array('id' => $commend['id'])) : $commend['url'];
				$news[] = $row;
			}
		}

		return $news;
	}

	private function voice_respond() {
		global $_W;
		$rid = $this->rule;
		$mediaid = table('voice_reply')
			->where(array('rid' => $rid))
			->orderby('RAND()')
			->getcolumn('mediaid');
		if (empty($mediaid)) {
			return false;
		}

		return $mediaid;
	}

	private function video_respond() {
		global $_W;
		$rid = $this->rule;
		$item = table('video_reply')
			->where(array('rid' => $rid))
			->orderby('RAND()')
			->get();
		if (empty($item)) {
			return false;
		}

		return $item;
	}
}
