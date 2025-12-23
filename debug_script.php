<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$path = base_path('districtuser.xlsx');
try {
    echo "Reading Excel file...\n";
    $data = \Maatwebsite\Excel\Facades\Excel::toArray(new \App\Imports\ExcelDataImport, $path);

    if (!empty($data) && count($data)) {
        $rows = $data[0]; 
        echo "Found " . count($rows) . " rows.\n";
        $count = 0;

        foreach ($rows as $row) {
            if (empty($row['name']) || empty($row['e_mail'])) {
                continue;
            }

            echo "Processing: " . $row['e_mail'] . "\n";

            $existingUser = User::where('email', $row['e_mail'])->first();
            if ($existingUser) {
                echo " - User exists, skipping.\n";
                continue; 
            }

            $designation_id = get_designation_id($row['designation']);
            $district_id = get_district_id($row['district']);
            
            User::create([
                'name' => $row['name'],
                'last_name' => $row['last_name'] ?? '',
                'email' => $row['e_mail'],
                'password' => bcrypt('password'),
                'designation_id' => $designation_id,
                'district_id' => $district_id,
                'gender' => $row['gender'] ?? null,
                'group_id' => 2,
                'phone_number' => '0'.$row['phone_number'] ?? null,
                'religion' => $row['religion'] ?? null,
                'image' => $row['image'] ?? 'no-image.png',
                'signature' => $row['signature'] ?? 'no-image.png',
            ]);
            $count++;
        }
        echo "Import completed. Imported $count users.\n";
    } else {
        echo "No data found or empty array\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

function get_designation_id($name)
{
    if (empty($name)) return null;
    $designation = \App\Models\Designation::where('desi_name', $name)->first();
    if ($designation) {
        return $designation->id;
    }
    $new = new \App\Models\Designation();
    $new->desi_name = $name;
    $new->desi_status = 'Active'; 
    $new->save();
    return $new->id;
}

function get_district_id($name)
{
    if (empty($name)) return null;
    $district = \App\Models\District::where('name_en', 'LIKE', '%'.$name.'%')
                                    ->orWhere('name_bn', 'LIKE', '%'.$name.'%')->first();
    if ($district) {
        return $district->id;
    }
    $new = new \App\Models\District();
    $new->name_en = $name;
    $new->name_bn = $name; 
    $new->save();
    return $new->id;
}
