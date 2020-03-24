<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/6/29 10:52
 */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title>关注公众号</title>
    <style>
        body {
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 12px;
        }

        .alert {
            padding: 6px 12px;
            border: 2px solid #ccc;
            background: #fff;
            border-radius: 3px;
            text-align: center;
        }

        .alert-error {
            border-color: #ff4544;
            color: #ff4544;
            background: #ffdfe1;
        }

        .alert-success {
            border-color: #32c74d;
            color: #2aa740;
            background: #dfeee8;
        }

    </style>
</head>
<body>
<?php if ($code == 0) : ?>
    <div style="text-align: center;margin-bottom: 12px">
        <img src="<?= $app_qrcode ?>" style="width: 70vw;height: auto">
    </div>
    <div class="alert alert-success">绑定成功，请关注公众号“<?= $app_name ?>”以便接收推送消息。</div>
<?php else : ?>
    <div class="alert alert-error"><?= $msg ?></div>
<?php endif; ?>
</body>
</html>
