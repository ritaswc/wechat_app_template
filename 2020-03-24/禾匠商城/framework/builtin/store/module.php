<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class StoreModule extends WeModule {
	public function welcomeDisplay() {
		header('Location: ' . $this->createWebUrl('goodsbuyer', array('direct' => 1)));
		exit();
	}
}
