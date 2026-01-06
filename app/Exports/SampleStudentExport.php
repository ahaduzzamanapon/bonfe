<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SampleStudentExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'John Doe', 'Jon Doe BN', 'Kisholoy Adorsho Shikhkha', 'Computer Operation', 'Cox\'s Bazar', 'Town Hall', 'Dhaka', 'Father Name', 'Mother Name', '1234567890', '1990123456789', '01700000000', '2000-01-01', 'Male', 'abc@example.com', 'HSC', '2025-01-01', '6'
            ],
            [
                'Jane Smith', 'Jane Smith BN', 'Kisholoy Adorsho Shikhkha', 'Computer Operation', 'Cox\'s Bazar', 'Ramu', 'Chittagong', 'Father Name', 'Mother Name', '0987654321', '1991123456789', '01800000000', '2001-02-02', 'Female', 'xyz@example.com', 'SSC', '2025-01-01', '6'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Name_BN',
            'Institute',
            'Trade',
            'District',
            'Upazila',
            'Address',
            'Father_Name',
            'Mother_Name',
            'NID',
            'BRN',
            'Mobile',
            'DOB',
            'Gender',
            'Email',
            'Qualification',
            'Training_Start_Date',
            'Program_ID'
        ];
    }
}
