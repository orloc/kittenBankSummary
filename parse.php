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

$reduced = array_reduce($fileResults, function($carry, $val){
    if (!isset($carry[$val['item_name']])){
        $carry[$val['item_name']] = [ $val ];
        return $carry;
    }
    $carry[$val['item_name']][] = $val;
    return $carry;
}, []);


$aggregated = array_map(function($item){
    $count = 0;
    $names = array_map(function($i) use (&$count){
        $count += intval($i['quantity']);
        return $i['character_name'];
    }, $item);
    
    $flipped = array_flip(array_flip($names));
    
    return [
        'item_name' => $item[0]['item_name'],
        'quantity' => $count,
        'LIFO Character' => $flipped[(count($flipped) - 1)],
        'url' => $item[0]['url']
    ];
}, $reduced);


echo join(',',['item_name', 'quantity', 'LIFO Character', 'url'])."\n";
array_map(function($m){
    echo join(',', $m)."\n";
}, $aggregated);













