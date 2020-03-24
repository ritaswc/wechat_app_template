<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 * @var \yii\web\View $this
 */
$urlManager = Yii::$app->urlManager;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title><?= $this->title ?></title>
    <link href="//at.alicdn.com/t/font_353057_lxz6kujlw4mfgvi.css" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/user/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/user/css/jquery.datetimepicker.min.css" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/css/flex.css?version=<?= $version ?>" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/css/common.css?version=<?= $version ?>" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/user/css/common.v2.css?version=<?= $version ?>"
          rel="stylesheet">

    <script>var _csrf = "<?=Yii::$app->request->csrfToken?>";</script>
    <script>var _upload_url = "<?=Yii::$app->urlManager->createUrl(['upload/file'])?>";</script>
    <script>var _district_data_url = "<?=Yii::$app->urlManager->createUrl(['api/default/district', 'store_id' => $this->context->store->id])?>";</script>

    <script src="<?= Yii::$app->request->baseUrl ?>/statics/user/js/jquery.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/user/js/vue.js"></script>
</head>
<body>
<?= $content ?>
</body>
</html>