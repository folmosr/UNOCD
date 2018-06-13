<?php
$c = 0;
ini_set('max_execution_time',300);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Reporte_Productos_SinFoto_Cliente_'.$ncli.'.xlsx');
header('Cache-Control: max-age=0');
$row =  7;
App::import('Vendor', 'PHPExcel'.DS.'Classes'.DS.'PHPExcel');
$objPHPExcel = new PHPExcel();
 
$objPHPExcel->getProperties()->setCreator('UNOCD - 2014')
                             ->setLastModifiedBy('UNOCD - 2014')
                             ->setTitle('Office 2007 XLSX Reporte de Productos sin fotos')
                             ->setSubject('Office 2007 XLSX Reporte de Productos sin fotos')
                             ->setDescription('REPORTE DE PEDIDOS SIN FOTOS Cliente: '.$ncli)
                             ->setKeywords('UNOCD Almacenadora Distribuidora Invetario Productos Pedidos')
                             ->setCategory('Reportes');

$objPHPExcel->getActiveSheet()->setTitle('Códigos de Productos sin Fotos');
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(1);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&IPage &P of &N');

$objPHPExcel->getActiveSheet()->getStyle('A1:E3')->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getStyle('A6:E6')->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getStyle('A7:E'.(count($data)-1))->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'REPORTE DE PRODUCTOS SIN FOTOS '.$ncli);
$objPHPExcel->getActiveSheet()->setCellValue('A5', 'TOTAL DE REGISTROS:'.count($data));

$objPHPExcel->getActiveSheet()->setCellValue('A6', 'CODIGO DE PRODUCTO');
$color = NULL;
for($i = 0; $i < count($data); $i++):
	if (@getimagesize('http://unocd.com/inventario/imagenes3pl/Fotosprd/'.$data[$i]['PConsolidado']['codi'].'.jpg') == false) {
		if(@getimagesize('http://unocd.com/inventario/imagenes3pl/Fotosprd/'.$data[$i]['PConsolidado']['codi'].'.JPG') == false){
			  	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $data[$i]['PConsolidado']['codi']); 
				$c++;
				$row+=1;
		}
	}
endfor;
$row+=1;
$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':E'.$row)->applyFromArray(
	array(
		'font' => array(
				'size'=>11,
		),
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		)
			
);
$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'TOTAL DE CODIGOS SIN FOTO ASOCIADA:'.$c);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);
exit;
?>