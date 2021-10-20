<?php

namespace BookStack\Imports;

use BookStack\QuestionImport;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportQuestionOption implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new QuestionImport([
            'stt'     => $row[0],
            'topic'    => $row[1],
            'title' => $row[2],
            'question' => $row[3],
            'correct_ans' => $row[4],
            'option1' => $row[5],
            'option2' => $row[6],
            'option3' => $row[7],
            'option4' => $row[8],
        ]);
    }
}
