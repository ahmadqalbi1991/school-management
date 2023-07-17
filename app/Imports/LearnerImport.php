<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class LearnerImport implements ToModel
{
    /**
     * @var
     */
    protected $stream_id;

    /**
     * @param $stream_id
     */
    public function __construct($stream_id)
    {
        $this->stream_id = $stream_id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $excel_elements = ['Name', 'Admission number', 'Parent Phone Number', 'Phone Number', 'Parent Email', 'Email', 'UPI Number'];
        if (array_diff($row, $excel_elements)) {
            return new User([
                'name' => $row[0],
                'admission_number' => $row[1],
                'phone_number' => $row[2],
                'parent_email' => $row[3],
                'parent_phone_number' => $row[4],
                'email' => $row[5],
                'role' => 'learner',
                'stream_id' => $this->stream_id,
                'school_id' => Auth::user()->school_id,
                'upi_number' => $row[6],
                'password' => Hash::make('123456789'),
            ]);
        }
    }
}
