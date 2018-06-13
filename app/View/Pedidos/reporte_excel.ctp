<?php
ini_set('max_execution_time',300);
$index = $this->CustomFunctions->getClientIndexInList($this->Session->read('Auth.User.Cliente'), $this->CustomFunctions->decode($this->passedArgs['cliente']));
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Pedidos_Cliente_'.$this->Session->read('Auth.User.Cliente.'.$index.'.ncli').'.xlsx');
header('Cache-Control: max-age=0');
$row =  7;
App::import('Vendor', 'PHPExcel'.DS.'Classes'.DS.'PHPExcel');
$objPHPExcel = new PHPExcel();
 
$objPHPExcel->getProperties()->setCreator('UNOCD - 2014')
                             ->setLastModifiedBy('UNOCD - 2014')
                             ->setTitle('Office 2007 XLSX Reporte de Pedidos')
                             ->setSubject('Office 2007 XLSX Reporte de Pedidos')
                             ->setDescription('Reporte de Pedidos Cliente: '.$this->Session->read('Auth.User.Cliente.'.$index.'.nombre'))
                             ->setKeywords('UNOCD Almacenadora Distribuidora Invetario Productos Pedidos')
                             ->setCategory('Reportes');

$objPHPExcel->getActiveSheet()->setTitle('Pedidos');
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(1);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&IPage &P of &N');

$objPHPExcel->getActiveSheet()->getStyle('A1:I3')->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getStyle('A6:I6')->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getStyle('A7:I'.count($data))->applyFromArray(
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
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'REPORTE DE PEDIDOS '.$this->Session->read('Auth.User.Cliente.'.$index.'.nombre'));
$objPHPExcel->getActiveSheet()->setCellValue('A5', 'TOTAL DE REGISTROS:'.count($data));

$objPHPExcel->getActiveSheet()->setCellValue('A6', 'Nº DE PEDIDO');
$objPHPExcel->getActiveSheet()->setCellValue('C6', 'SOLICITANTE');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'FECHA DE SOLICITUD');
$objPHPExcel->getActiveSheet()->setCellValue('D6', 'FECHA DE APROBACION');
$objPHPExcel->getActiveSheet()->setCellValue('E6', 'FECHA DE DESPACHO');
$objPHPExcel->getActiveSheet()->setCellValue('F6', 'FECHA DE ENTREGA');
$objPHPExcel->getActiveSheet()->setCellValue('G6', 'ESTATUS');
$objPHPExcel->getActiveSheet()->setCellValue('H6', 'OBSERVACIONES');
$objPHPExcel->getActiveSheet()->setCellValue('I6', 'NOVEDADES');
$color = NULL;
for($i = 0; $i < count($data); $i++):
    $w_index = (count($data[$i]['Webtracking'])-1);
	if(!count($data[$i]['Webtracking']) >0 )
	{
		if($data[$i]['Estatu']['id'] == 1)
			$color = 'FFFFFF00'; 
		elseif($data[$i]['Estatu']['id'] == 2)
			$color = 'FF008000';
		elseif($data[$i]['Estatu']['id'] == 3)
			$color = 'FF0000';
		else
			$color = 'FF0000FF';
	}else{
		if($data[$i]['Webtracking'][$w_index]['stat_pedido']==1)
			$color = 'FFFFFF00'; 
		 elseif($data[$i]['Webtracking'][$w_index]['stat_pedido']==2)
				$color = 'FF008000';
		 elseif($data[$i]['Webtracking'][$w_index]['stat_pedido']==3)		 
		 			$color = 'FF0000';
		 elseif($data[$i]['Webtracking'][$w_index]['stat_pedido']==4)
		 {
			 	if($data[$i]['Webtracking'][$w_index]['stat_transf'] > 170)
					$color = 'FF0000';
					else
					$color = 'FF0000FF';
		 }
	}
	$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->applyFromArray(
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
	$estatus = (!count($data[$i]['Webtracking']) > 0)?$data[$i]['Estatu']['descripcion']:$this->CustomFunctions->getWebtrackingStatus($data[$i]['Webtracking'][$w_index]['stat_pedido'], $data[$i]['Webtracking'][$w_index]['stat_transf']);
  	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $data[$i]['Pedido']['id_pedido']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $data[$i]['Solicitante']['apellido']. '  '.$data[$i]['Solicitante']['nombre']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, $data[$i]['Pedido']['f_solicitud']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, (is_null($data[$i]['Pedido']['fecha_proceso']))?'Aún sin procesar':$data[$i]['Pedido']['fecha_proceso']); 

  	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, (!count($data[$i]['Clifactura']) > 0)?NULL:$data[$i]['Clifactura']['FECHADES']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row, (!count($data[$i]['Clifactura']) > 0)?NULL:$data[$i]['Clifactura']['FECHAENT']); 
	
  	$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row, $estatus); 
	
  	$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row, $data[$i]['Clifactura']['Observaciones']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row, $data[$i]['Clifactura']['novedades']); 


	$row+=1;
endfor;



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);
exit;
?>