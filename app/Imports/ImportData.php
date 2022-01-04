<?php

namespace BookStack\Imports;

use BookStack\DataImport;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportData implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new DataImport([
            'page_title'     => $row[0],
            'page_content'    => $row[1],
            'page_description' => $row[2],
            'chapter' => $row[3],
            'book' => $row[4],
            'shelf' =>  "",
            'root' =>  ""
        ]);
    }
}
