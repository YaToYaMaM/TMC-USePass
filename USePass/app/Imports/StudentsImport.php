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
            'ID'         => $row['students_id'],
            'First Name' => $row['students_first_name'],
            'Last Name'  => $row['students_last_name'],
            'Middle Initial' => $row['students_middle_initial'],
            'Gender'     => $row['students_gender'],
            'Program'    => $row['students_program'],
            'Major'      => $row['students_major'],
            'Units'       => $row['students_unit'],
        ]);


    }
}
