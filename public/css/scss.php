<?php
include "../../vendor/autoload.php";
use Leafo\ScssPhp\Compiler;
use Leafo\ScssPhp\Server;

$scss = new Compiler();
$scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
$server = new Server('scss', null, $scss);
$server->serve();