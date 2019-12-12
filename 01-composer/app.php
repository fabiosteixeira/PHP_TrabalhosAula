<?php

require 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('name');
$log->pushHandler(new StreamHandler('app.log', Logger::INFO));

// add records to the log
$log->warning('Foo');
$log->error('Bar');
$log->info('teste 001');