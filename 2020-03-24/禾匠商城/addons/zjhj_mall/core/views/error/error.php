<?php
/** @var Exception $exception */
if ($exception->event_id) {
    $message = '事件 ID: ' . $exception->event_id;
} else {
    $message = get_class($exception);
}

if (method_exists($this, 'beginPage')) {
    $this->beginPage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>服务器错误</title>

    <style>
        body {
            font: normal 9pt "Verdana";
            color: #000;
            background: #fff;
        }

        h1 {
            font: normal 18pt "Verdana";
            color: #f00;
            margin-bottom: .5em;
        }

        h2 {
            font: normal 14pt "Verdana";
            color: #800000;
            margin-bottom: .5em;
        }

        h3 {
            font: bold 11pt "Verdana";
        }

        p {
            font: normal 9pt "Verdana";
            color: #000;
        }

        .version {
            color: gray;
            font-size: 8pt;
            border-top: 1px solid #aaa;
            padding-top: 1em;
            margin-bottom: 1em;
        }
    </style>
</head>

<body>
    <h1>服务器错误</h1>
    <h2><?= nl2br($handler->htmlEncode($message)) ?></h2>
    <p>
        <!-- 请将「事件 ID」发送给我们，以便于我们进行问题追踪。 -->
    </p>
    <p>
        <?= $exception->getMessage() ?>
    </p>
    <div style="resize: both;">
        <pre><?= print_r($exception->getTraceAsString(), true) ?></pre>
    </div>
    <div class="version">
        <?= hj_core_version() ?>
    </div>
    <?php
    if (method_exists($this, 'endBody')) {
        $this->endBody(); // to allow injecting code into body (mostly by Yii Debug Toolbar)
    }
    ?>
</body>
</html>
<?php
if (method_exists($this, 'endPage')) {
    $this->endPage();
}
