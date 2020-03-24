<?php

namespace Hejiang\Storage\Drivers;

interface DriverInterface
{
    function put($localFile, $saveTo);
}