<?php 






## array içine yazılan sütunlarda genişliği otomatik yap. ##
foreach ($columns as $column) {
    $sheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
}


// Rename worksheet
$sheet->getActiveSheet()->setTitle('Sayfa1');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$sheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$file_name.'".xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

use PhpOffice\PhpSpreadsheet\IOFactory;
$writer = IOFactory::createWriter($sheet, 'Xlsx');
$writer->save('php://output');
exit;