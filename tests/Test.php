<?php
require __DIR__ . '/../vendor/autoload.php';

$Tshmop = new \Tshmop\Tshmop();

$data = [
    ['name' => '张三', 'age' => 20],
    ['name' => '涛哥', 'age' => 18],
    ['name' => '妹子', 'age' => 17]
];

$Tshmop->put($data);

//while(true){
//
//}