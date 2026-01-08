<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class StudentsImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        return new Student([
            'students_id'         => $row['id'],
            'students_first_name' => $row['first_name'],
            'students_last_name'  => $row['last_name'],
            'students_middle_initial' => $row['middle_initial'],
            'students_gender'     => $row['gender'],
            'students_program'    => $row['program'],
            'students_major'      => $row['major'],
            'students_unit'       => $row['units'],
        ]);


    }
}
