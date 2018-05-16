<?php
$data = [];
function test($url)
{
    $times = 100;
    $time  = microtime(1);
    for ($i = 0; $i < $times; $i++) {
        file_get_contents($url);
    }
    $time = microtime(1) - $time;
    return $times / $time;
}
$data['html']    = test('http://thinkphp.local.com/test.html');
$data['php']     = test('http://thinkphp.local.com/test.php');
$data['control'] = test('http://thinkphp.local.com/index.php');
var_dump($data);
var_dump($data['control'] / $data['php']);
var_dump($data['control'] / $data['html']);
