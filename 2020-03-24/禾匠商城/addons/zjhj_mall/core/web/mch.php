<?php
if (!isset($_GET['r'])) {
    $_GET['r'] = 'user/passport/login';
}
require __DIR__ . '/index.php';