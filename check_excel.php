<?php
require 'vendor/autoload.php';
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExcelDataImport;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$path = 'd:\laragon\www\bonfe\TSC- CGD-Candidate Information for Certificate prepare.xlsx';
if (!file_exists($path)) {
    echo "File not found: $path";
    exit;
}

$rows = Excel::toArray(new ExcelDataImport, $path);
if (isset($rows[0][0])) {
    print_r(array_keys($rows[0][0]));
    print_r($rows[0][0]); // Print first row data to see sample values
} else {
    echo "No rows found or structure invalid.";
}
