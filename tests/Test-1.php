<?php
require __DIR__ . '/../vendor/autoload.php';

$Tshmop = new \Tshmop\Tshmop();

$data = $Tshmop->get();

print_r($data);