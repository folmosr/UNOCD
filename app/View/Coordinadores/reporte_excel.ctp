<?php
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Coordinadores_UNOCD.xlsx');
header('Cache-Control: max-age=0');
$row =  7;
App::import('Vendor', 'PHPExcel'.DS.'Classes'.DS.'PHPExcel');
$objPHPExcel = new PHPExcel();
 
$objPHPExcel->getProperties()->setCreator('UNOCD - 2014')
                             ->setLastModifiedBy('UNOCD - 2014')
                             ->setTitle('Office 2007 XLSX Reporte de Coordinadores')
                             ->setSubject('Office 2007 XLSX Reporte de Coordinadores')
                             ->setDescription('Reporte de Coordinadores')
                             ->setKeywords('UNOCD Almacenadora Distribuidora Invetario Productos Pedidos Usuarios Coordinadores')
                             ->setCategory('Reportes');

$objPHPExcel->getActiveSheet()->setTitle('Coordinadores');
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(1);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&IPage &P of &N');

$objPHPExcel->getActiveSheet()->getStyle('A1:F3')->applyFromArray(
	array(
	
			'font' => array(
				'bold' => true,
				'color'=>array('argb' => 'FFFFFFFF'),
				'size'=>20,
				'underline'=>true
		),
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
		),

		
		'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array(
							'argb' => 'FFA0A0A0',
						)
					),
				)
			
);

$objPHPExcel->getActiveSheet()->getStyle('A6:F6')->applyFromArray(
	array(
		'font' => array(
				'bold' => true,
				'color'=>array('argb' => 'FFFFFFFF'),
				'size'=>12,
				'underline'=>true
		),
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		),

		'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array(
							'argb' => 'FF000000',
						)
					),
				)
			
);

$objPHPExcel->getActiveSheet()->getStyle('A7:F'.count($data))->applyFromArray(
	array(
		'font' => array(
				'size'=>12,
		),
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		)
			
);

$objPHPExcel->setActiveSheetIndex(0); 

$objDrawingPType = new PHPExcel_Worksheet_Drawing();
$objDrawingPType->setWorksheet($objPHPExcel->getActiveSheet());
$objDrawingPType->setName('UNOCD');
$objDrawingPType->setPath(realpath('img'.DS.'unnamed.png'));
$objDrawingPType->setWidthAndHeight(127, 66);
$objDrawingPType->setCoordinates('A1');
$objDrawingPType->setOffsetX(0);
$objDrawingPType->setOffsetY(0);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('C2', 'REPORTE DE COORDINADORES ');
$objPHPExcel->getActiveSheet()->setCellValue('A5', 'TOTAL DE REGISTROS:'.count($data));

$objPHPExcel->getActiveSheet()->setCellValue('A6', 'Nº');
$objPHPExcel->getActiveSheet()->setCellValue('C6', 'NOMBRE/APELLIDO');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'CORREO ELECTRONICO');
$objPHPExcel->getActiveSheet()->setCellValue('D6', 'CLIENTE(S)');
$objPHPExcel->getActiveSheet()->setCellValue('E6', 'FECHA DE CREACION');
$objPHPExcel->getActiveSheet()->setCellValue('F6', 'ESTADO');
$clientes = $status = $color = NULL;
for($i = 0; $i < count($data); $i++):
	if($data[$i]['Usuario']['status'] == 1){
		$status = 'Activo';
		$color = 'FF008000';
	}elseif($data[$i]['Usuario']['status'] == 0){
		$color = 'FF0000';
		$status = 'No Activo';
	}
	$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->applyFromArray(
		array(
		 'font'  => array(
				'bold'  => true,
				'color' => array('argb' => 'FFFFFFFF'),
				'size'  => 10,
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('argb' => $color)
			)
		)
	);
	
	for($j = 0; $j < count($data[$i]['Cliente']); $j++):
		if(is_null($clientes))
			$clientes = $data[$i]['Cliente'][$j]['nombre']."\n"; 
			else
				$clientes.= $data[$i]['Cliente'][$j]['nombre']."\n"; 
	endfor;
  	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $data[$i]['Usuario']['id']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $data[$i]['Usuario']['apellido']. '  '.$data[$i]['Usuario']['nombre']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, $data[$i]['Usuario']['correo']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $clientes); 
	$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setWrapText(true);
  	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $data[$i]['Usuario']['f_registro']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row, $status); 
	$row+=1;
endfor;



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);
exit;
?>