<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
?>
<?php foreach ($list as $item) : ?>
    <div class="file-item text-center" data-name="<?= $item['file_url'] ?>"
         data-url="<?= $item['file_url'] ?>">
        <img src="<?= $item['file_url'] ?>"
             class="file-cover">
    </div>
<?php endforeach; ?>