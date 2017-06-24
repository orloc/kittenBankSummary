<?php

require('vendor/autoload.php');

use League\Csv\Reader;

$name = __DIR__.'/files';
$files = scandir($name);
$blackList = [ '.', '..'];

foreach ($files as $file) { 
    if (in_array($file, $blackList)) { 
        continue;
    }

    $csv = new FileReader($name.'/'.$file);
    
    $csv->read();
}

class FileReader {

    protected $reader;
    protected $headers;
    protected static $white_listed = [
        'General1',
        'General2',
        'General3',
        'General4',
        'General5',
        'General6',
        'General7',
        'General8',
        'Bank1',
        'Bank2',
        'Bank3',
        'Bank4',
        'Bank5',
        'Bank6',
        'Bank7',
        'Bank8'
    ];

    protected static $shared_listed = [
        'SharedBank1',
        'SharedBank2',
        'SharedBank3',
        'SharedBank4',
        'SharedBank5',
        'SharedBank6',
        'SharedBank7',
        'SharedBank8'
    ];

    public function __construct($dir){
        $this->reader = Reader::createFromPath($dir);
        $this->reader->setDelimiter("\t");
        
        $this->headers = $this->reader->fetchOne();
    }

    public function read(){
        foreach ($this->reader->fetchAll() as $k => $item){
            if ($k === 0 ) continue;
            list($slot, $name, $id, $count,  $slots) = $item;
            $this->isValidSelection($slot);
        }
    }

    protected function isValidSelection($slot){
        foreach (FileReader::$white_listed as $item){
            if (!strpos($slot, $item) ) {
                var_dump($slot);
            }
        }
    }
}


