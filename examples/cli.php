<?php

require __DIR__ . '/../vendor/autoload.php';

use Valerian\Ispis\CEE;

$username = 'username';
$password = 'password';

$cee = new CEE($username, $password);
var_dump($cee->isDebtor('9705028003'));

