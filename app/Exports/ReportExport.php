<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            '#', 'Candidate ID', 'Name (English)', 'Name (Bangla)',
            "Father's Name (EN)", "Father's Name (BN)",
            "Mother's Name (EN)", "Mother's Name (BN)",
            'Registration No.', 'NID', 'BRN', 'Gender', 'Date of Birth',
            'District', 'Upazila', 'Occupation', 'Program',
            'Assessment Date', 'Exam Status', 'Certificate No.', 'Status',
        ];
    }

    public function map($row): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $row->candidate_id,
            $row->candidate_name,
            $row->candidate_name_bn,
            $row->father_name,
            $row->father_name_bn ?? '',
            $row->mother_name,
            $row->mother_name_bn ?? '',
            $row->registration_number,
            $row->nid,
            $row->brn,
            $row->gender,
            $row->date_of_birth ? \Carbon\Carbon::parse($row->date_of_birth)->format('d-m-Y') : '',
            $row->district_name,
            $row->upazila_name,
            $row->occupation_title,
            $row->program_title,
            $row->assessment_date ? \Carbon\Carbon::parse($row->assessment_date)->format('d-m-Y') : '',
            $row->exam_status,
            $row->certificate_number,
            $row->status,
        ];
    }
}
