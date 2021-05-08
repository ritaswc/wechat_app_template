<?php
require_once __DIR__ . '/AdminController.php';

use \LeanCloud\File;

class Upload extends AdminController {
	function avatar() {
		// 图片上传
		$file = File::createWithLocalFile($_FILES['file']['tmp_name'], $_FILES['file']['type']);
		// 保存图片
		$file->save();
		echo json_encode(['url' => $file->get('url')]);
	}
}
?>