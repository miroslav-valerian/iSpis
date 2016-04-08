<?php

require __DIR__ . '/../vendor/autoload.php';

use Valerian\Ispis\CEE;

$username = 'username';
$password = 'password';

$name = 'name';
$surname = 'surname';
$dob = \DateTime::createFromFormat('Y-m-d', '1977-08-12'); // Date of birth
$nin = '9705028003'; // National identification number

$cee = new CEE($username, $password);
$result = $cee
    ->setSeparateQuery(true)
    ->setName($name)
    ->setSurname($surname)
    ->setDob($dob)
    ->setNin($nin)
    ->isDebtor()
;

var_dump($result);
