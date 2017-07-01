<?php

use League\Csv\Reader;

class FileReader {

    protected $reader;
    protected $group_name;
    protected static $white_listed = [
        'General1-',
        'General2-',
        'General3-',
        'General4-',
        'General5-',
        'General6-',
        'General7-',
        'General8-',
        'Bank1-',
        'Bank2-',
        'Bank3-',
        'Bank4-',
        'Bank5-',
        'Bank6-',
        'Bank7-',
        'Bank8-'
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

    public function __construct($dir, $file){
        $this->reader = Reader::createFromPath($dir);
        $this->reader->setDelimiter("\t");
        $this->group_name = str_replace('-Inventory.txt', '', $file);
    }
    
    public function read(){
        $result = [];
        foreach ($this->reader->fetchAll() as $k => $item){
            if ($k === 0 ) continue;
            list($slot, $name, , $count) = $item;
            if ($this->isValidSelection($slot)) {
                if (!$this->shouldExclude($name)){
                    array_push($result, [
                        'item_name' => $name,
                        'quantity' => $count,
                        'character_name' => $this->group_name,
                        'item-slot' => $slot,
                        'url' => 'https://wiki.project1999.com/'.str_replace(' ','_', $name)
                    ]);
                }
            }
        }

        return $result;
    }
    
    protected function shouldExclude($name){
        if ($name === 'Empty') {
            return true;
        }
        return false;
    }

    protected function isValidSelection($slot){
        foreach (FileReader::$shared_listed as $item){
            if (strpos($slot, $item) !== false) {
                return false;
            }
        }

        foreach (FileReader::$white_listed as $item){
            if (strpos($slot, $item) !== false) {
                return true;
            }
        }
    }
}

