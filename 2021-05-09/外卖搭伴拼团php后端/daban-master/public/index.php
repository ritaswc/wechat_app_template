<?php

require_once __DIR__ . '/../vendor/autoload.php';

use LeanCloud\Client;
use LeanCloud\Engine\LeanEngine;
use LeanCloud\Engine\Cloud;

Client::initialize(
    '6LXne5XRbSQLmG3rwLmC9TdM-gzGzoHsz',
    'xEhlLPpmOAKCNM4TnpTdsvEX',
    'rl2BJdmke7Q5kXMgFSIV1AIW'
);


$engine = new LeanEngine();
$engine->start();
