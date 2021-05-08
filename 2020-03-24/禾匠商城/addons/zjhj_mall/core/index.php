<?php
if (file_exists(__DIR__ . '/install.lock.php')) {

    header('Location: web/');
} else {
    header('Location: web/install.php');
}