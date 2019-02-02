<?php

$common_php_dir = '../php_common';
$common_autoload_file = $common_php_dir . '/autoload.php';
require ($common_autoload_file);

$cli_php_dir = '../php_cli';
$cli_autoload_file = $cli_php_dir . '/autoload.php';
require ($cli_autoload_file);

require ('./local/autoload.php');

use cli\classes as cli;
use local\classes\cli as localCli;

\common\Config::obj(__DIR__ . '/config/config.ini');

if ($argc >= 1) {
    $f = new localCli\Flag();
    try {
        $f->exchangeArray(array_slice($argv, 1));
    } catch (\UnexpectedValueException $e) {
        exit(\common\logging\Logger::obj()->writeException($e));
    }
    if (strlen($f->dir) > 0) {
        $this->dir = $f->dir;
    }
}

if ($f->db === 'memcache') {
    $rm = new localCli\MemcacheStatsMenu($f);
} else if ($f->db === 'mongodb') {
    $rm = new localCli\MongoDbStatsMenu($f);
} else if ($f->db === 'redis') {
    $rm = new localCli\RedisStatsMenu($f);
} else {
    exit(\common\logging\Logger::obj()->write("Error in Database Selection with --db flag must be memcache | mongodb | redis\n", -1, TRUE));
}

$rm->readLine();

exit(0);
