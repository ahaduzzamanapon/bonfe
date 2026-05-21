<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $type;
    protected $colDef;
    protected $rowIndex = 0;

    public function __construct($data, string $type = 'project_wise')
    {
        $this->data    = $data;
        $this->type    = $type;
        $this->colDef  = $this->resolveColumns($type);
    }

    public function collection()
    {
        return $this->data;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFill()
              ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
              ->getStartColor()->setRGB('1a3a5c');
        $sheet->getStyle('1')->getFont()->getColor()->setRGB('FFFFFF');
    }

    public function headings(): array
    {
        $c = $this->colDef;
        $h = ['#'];
        if (!empty($c['show_district']))   $h[] = 'District';
        if (!empty($c['show_upazila']))    $h[] = 'Upazila';
        if (!empty($c['show_program']))    $h[] = 'Program';
        if (!empty($c['show_occupation'])) $h[] = 'Occupation';
        if (!empty($c['show_gender']))     $h[] = 'Gender';
        if (!empty($c['show_name'])) {
            $h[] = 'Name (English)';
            $h[] = 'নাম (বাংলা)';
        }
        if (!empty($c['show_father'])) {
            $h[] = "Father's Name (EN)";
            $h[] = "Father's Name (BN)";
        }
        if (!empty($c['show_mother'])) {
            $h[] = "Mother's Name (EN)";
            $h[] = "Mother's Name (BN)";
        }
        if (!empty($c['show_cand_id']))   $h[] = 'Candidate ID';
        if (!empty($c['show_reg']))       $h[] = 'Reg. No.';
        if (!empty($c['show_brn_nid']))   $h[] = 'BRN / NID';
        if (!empty($c['show_exam']))      $h[] = 'Exam Status';
        if (!empty($c['show_cert_no']))   $h[] = 'Certificate No.';
        return $h;
    }

    public function map($row): array
    {
        $this->rowIndex++;
        $c = $this->colDef;
        $r = [$this->rowIndex];
        if (!empty($c['show_district']))   $r[] = $row->district_name;
        if (!empty($c['show_upazila']))    $r[] = $row->upazila_name;
        if (!empty($c['show_program']))    $r[] = $row->program_title;
        if (!empty($c['show_occupation'])) $r[] = $row->occupation_title;
        if (!empty($c['show_gender']))     $r[] = $row->gender;
        if (!empty($c['show_name'])) {
            $r[] = $row->candidate_name;
            $r[] = $row->candidate_name_bn;
        }
        if (!empty($c['show_father'])) {
            $r[] = $row->father_name;
            $r[] = $row->father_name_bn ?? '';
        }
        if (!empty($c['show_mother'])) {
            $r[] = $row->mother_name;
            $r[] = $row->mother_name_bn ?? '';
        }
        if (!empty($c['show_cand_id']))   $r[] = $row->candidate_id;
        if (!empty($c['show_reg']))       $r[] = $row->registration_number;
        if (!empty($c['show_brn_nid']))   $r[] = $row->nid ?? $row->brn;
        if (!empty($c['show_exam']))      $r[] = $row->exam_status;
        if (!empty($c['show_cert_no']))   $r[] = $row->certificate_number;
        return $r;
    }

    private function resolveColumns(string $type): array
    {
        $map = [
            'project_wise' => [
                'show_district'=>true,'show_upazila'=>true,'show_program'=>true,'show_occupation'=>true,
                'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
                'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>false,
            ],
            'district_wise' => [
                'show_district'=>true,'show_upazila'=>false,'show_program'=>false,'show_occupation'=>false,
                'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
                'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>false,
            ],
            'upazila_wise' => [
                'show_district'=>true,'show_upazila'=>true,'show_program'=>false,'show_occupation'=>false,
                'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
                'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>false,
            ],
            'gender_wise' => [
                'show_district'=>false,'show_upazila'=>false,'show_program'=>false,'show_occupation'=>false,
                'show_gender'=>true,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
                'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>false,
            ],
            'occupation_wise' => [
                'show_district'=>true,'show_upazila'=>true,'show_program'=>false,'show_occupation'=>true,
                'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
                'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>false,
            ],
            'student_id' => [
                'show_district'=>true,'show_upazila'=>true,'show_program'=>false,'show_occupation'=>false,
                'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
                'show_cand_id'=>true,'show_reg'=>false,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>false,
            ],
            'certificate_distribution' => [
                'show_district'=>true,'show_upazila'=>true,'show_program'=>false,'show_occupation'=>false,
                'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
                'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>true,
            ],
            'nyc_students' => [
                'show_district'=>true,'show_upazila'=>true,'show_program'=>false,'show_occupation'=>false,
                'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
                'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>true,'show_cert_no'=>false,
            ],
        ];
        return $map[$type] ?? $map['project_wise'];
    }
}
