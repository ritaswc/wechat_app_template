<?php


defined('YII_ENV') or exit('Access Denied');

use app\models\Option;

$version = hj_core_version();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title><?= !empty($this->title) ? $this->title . ' - ' : null ?><?= Option::get('name', 0, 'admin', '禾匠信息科技') ?></title>
    <link rel="stylesheet"
          href="<?= Yii::$app->request->baseUrl ?>/statics/admin/css/bootstrap.min.css?v=<?= $version ?>">
    <link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/statics/admin/css/common.css?v=<?= $version ?>">
    <script>var _csrf = "<?=Yii::$app->request->csrfToken?>";</script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/admin/js/vue.js?v=<?= $version ?>"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/admin/js/jquery.min.js?v=<?= $version ?>"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/admin/js/popper.min.js?v=<?= $version ?>"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/admin/js/bootstrap.min.js?v=<?= $version ?>"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/admin/js/common.js?v=<?= $version ?>"></script>
</head>
<body>
<?= $content ?>
</body>
</html>