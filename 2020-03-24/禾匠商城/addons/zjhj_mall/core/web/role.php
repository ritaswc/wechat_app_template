<?php
if (!isset($_GET['r'])) {
    $_GET['r'] = 'mch/permission/passport/index';
}
require __DIR__ . '/index.php';