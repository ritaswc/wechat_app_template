<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/31
 * Time: 15:28
 */
$urlManager = Yii::$app->urlManager;
$preview_value = !empty($value) ? $value : '';
?>
<?php if (!$multiple): ?>
    <div class="image-picker" data-url="<?= $urlManager->createUrl(['upload/image']) ?>">
        <a href="javascript:" class="btn btn-secondary new-image-picker-btn">选择图片</a>
        <div class="image-picker-view-item">
            <input class="image-picker-input"
                   type="hidden"
                <?= isset($name) ? "name='{$name}'" : null ?>
                <?= isset($value) ? "value='{$value}'" : null ?>>
            <div class="image-picker-view"
                 data-responsive="<?= $width ?>:<?= $height ?>"
                 style="width: <?= $width > 150 ? 150 : $width ?>px;<?= $value ? 'background-image: url(' . $value . ')' : null ?>">
                <span class="picker-tip"><?= $tip ?></span>
                <span class="picker-delete">×</span>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="image-picker" data-multiple="true" data-name="<?= $name ?>"
         data-url="<?= $urlManager->createUrl(['upload/image']) ?>">
        <a href="javascript:" class="btn btn-secondary new-image-picker-btn">选择图片</a>
        <div class="picker-multiple-list">
            <div class="image-picker-view-item picker-empty-preview" <?= count($value) ? 'style="display:none"' : null ?>>
                <div class="image-picker-view"
                     data-responsive="<?= $width ?>:<?= $height ?>"
                     style="width: <?= $width > 150 ? 150 : $width ?>px;">
                    <span class="picker-tip"><?= $tip ?></span>
                </div>
            </div>
            <?php foreach ($value as $v): ?>
                <div class="image-picker-view-item">
                    <input class="image-picker-input"
                           type="hidden"
                        <?= isset($name) ? "name='{$name}'" : null ?>
                        <?= isset($v) ? "value='{$v}'" : null ?>>
                    <div class="image-picker-view"
                         data-responsive="<?= $width ?>:<?= $height ?>"
                         style="width: <?= $width > 150 ? 150 : $width ?>px;<?= $v ? 'background-image: url(' . $v . ')' : null ?>">
                        <span class="picker-tip"><?= $tip ?></span>
                        <span class="picker-delete">×</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>