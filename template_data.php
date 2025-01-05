<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require 'vendor/autoload.php'; // Pastikan PhpSpreadsheet sudah diinstall melalui Composer

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header di Excel
$sheet->setCellValue('A1', 'Nama Peserta');
$sheet->setCellValue('B1', 'No Telepon');
$sheet->setCellValue('C1', 'Email');
$sheet->setCellValue('D1', 'Alamat');
$sheet->setCellValue('E1', 'Status');
$sheet->setCellValue('F1', 'Peringkat');
$sheet->setCellValue('G1', 'Jenis Lomba');


// Atau, jika Anda ingin menentukan lebar kolom secara manual:
$sheet->getColumnDimension('A')->setWidth(25); // Lebar kolom A
$sheet->getColumnDimension('B')->setWidth(15); // Lebar kolom B
$sheet->getColumnDimension('C')->setWidth(30); // Lebar kolom C
$sheet->getColumnDimension('D')->setWidth(15); // Lebar kolom D
$sheet->getColumnDimension('E')->setWidth(15); // Lebar kolom E
$sheet->getColumnDimension('F')->setWidth(15); // Lebar kolom F
$sheet->getColumnDimension('G')->setWidth(20); // Lebar kolom G

// Set nama file
$filename = 'data_peserta.xlsx';

// Header untuk mengatur file agar didownload
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Buat file Excel dan kirim ke browser
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
