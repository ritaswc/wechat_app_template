<?php

function handle_old_db_config() // before 2018.5.27
{
    $ind_db_file = __DIR__ . '/ind_db.php';
    if (file_exists($ind_db_file)) {
        return include $ind_db_file;
    } else {
        return select_db_config('we7');
    }
}

function select_db_config($mode)
{
    switch ($mode) {
        case 'stand-alone':
            return include __DIR__ . '/db-stand-alone.php';
        case 'we7':
            return include __DIR__ . '/db-we7.php';
        case null:
            return handle_old_db_config();
        default:
            throw new Exception('Unknown app mode.');
    }
}

$db_config = select_db_config(
    env('DB_MODE')
);

$db_config['enableSchemaCache'] = env('ENABLE_SCHEMA_CACHE', false);
$db_config['schemaCacheDuration'] = env('SCHEMA_CACHE_DURATION', 3600);
$db_config['schemaCache'] = env('SCHEMA_CACHE', 'cache');

$db_config['on afterOpen'] = function ($event) {
    $event->sender->createCommand(
        "SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'"
    )->execute();
};

return $db_config;