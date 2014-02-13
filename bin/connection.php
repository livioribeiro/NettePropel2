<?php
require __DIR__ . '/../../../autoload.php';

$appDir = realpath(__DIR__ . '/../../../../app');
$connection = \NettePropel2\Setup::getConnectionForCli($appDir);
system("echo '$connection'");