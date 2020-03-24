<?php

use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name("*.php")
    ->exclude("Resources")
    ->exclude("Tests")
    ->in(__DIR__."/src/");

return new Sami($iterator, array(
    "build_dir" => __DIR__."/docs",
    "cache_dir" => __DIR__."/cache",
));
