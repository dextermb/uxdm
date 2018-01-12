<?php

namespace RapidWeb\uxdm\Objects\Sources;

use RapidWeb\uxdm\Interfaces\SourceInterface;
use RapidWeb\uxdm\Objects\DataRow;
use RapidWeb\uxdm\Objects\DataItem;

class JSONFilesSource implements SourceInterface
{
    private $files;
    private $fields = [];

    public function __construct(array $files = []) {
        $this->files = $files;
    }

    public function getDataRows($page = 1, $fieldsToRetrieve = []) {

        $perPage = 10;

        $offset = 0 + (($page-1) * $perPage);

        $files = array_slice($this->files, $offset, $perPage);

        $dataRows = [];
        
        foreach($files as $file) {

            $dataRow = new DataRow;

            $array = json_decode(file_get_contents($file), true);
            $dottedArray = array_dot($array);

            foreach($dottedArray as $key => $value) {
                
                if (in_array($key, $fieldsToRetrieve)) {
                    $dataRow->addDataItem(new DataItem($key, $value));
                }

            }
            
            $dataRows[] = $dataRow;
        }

        return $dataRows;
    }

    public function getFields() {
        return $this->fields;
    }
}