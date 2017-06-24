<?php

require('vendor/autoload.php');
require_once ('./FileReader.php');

$name = __DIR__.'/files';
$files = scandir($name);
$blackList = [ '.', '..'];

$fileResults =[];

foreach ($files as $file) { 
    if (in_array($file, $blackList)) continue;
    $csv = new FileReader($name.'/'.$file, $file);
    $fileResults = array_merge($fileResults, $csv->read());
}

$headers = join(',',array_keys($fileResults[0]));
echo $headers."\n";
array_map(function($m){
    echo join(',', $m)."\n";
}, $fileResults);













