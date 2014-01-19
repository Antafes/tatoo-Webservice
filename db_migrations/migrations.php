<?php
require_once(__DIR__.'/../lib/config.default.php');

$GLOBALS['config']['migrations_dir'] = __DIR__.'/files/';

$result = migration_manager($_REQUEST);

echo $result;