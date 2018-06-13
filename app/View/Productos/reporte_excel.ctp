<?php
ini_set('max_execution_time',300);
$index = $this->CustomFunctions->getClientIndexInList($this->Session->read('Auth.User.Cliente'), $this->CustomFunctions->decode($this->passedArgs['cliente']));
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Inventario_Cliente_'.$this->Session->read('Auth.User.Cliente.'.$index.'.ncli').'.xlsx');
header('Cache-Control: max-age=0');
$row =  7;
$lastRow = count($data)+1;
App::import('Vendor', 'PHPExcel'.DS.'Classes'.DS.'PHPExcel');
$objPHPExcel = new PHPExcel();
 
$objPHPExcel->getProperties()->setCreator('UNOCD - 2014')
                             ->setLastModifiedBy('UNOCD - 2014')
                             ->setTitle('Office 2007 XLSX Reporte de Inventario')
                             ->setSubject('Office 2007 XLSX Reporte de Inventario')
                             ->setDescription('Reporte de Inventario Cliente: '.$this->Session->read('Auth.User.Cliente.0.nombre'))
                             ->setKeywords('UNOCD Almacenadora Distribuidora Invetario Productos')
                             ->setCategory('Reportes');

$objPHPExcel->getActiveSheet()->setTitle('Inventario');
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(1);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&IPage &P of &N');

$objPHPExcel->getActiveSheet()->getStyle('A1:V3')->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getStyle('A6:V6')->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getStyle('A7:V'.count($data))->applyFromArray(
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
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'REPORTE DE INVENTARIO '.$this->Session->read('Auth.User.Cliente.'.$index.'.nombre'));
$objPHPExcel->getActiveSheet()->setCellValue('A5', 'TOTAL DE REGISTROS:'.count($data));

$objPHPExcel->getActiveSheet()->setCellValue('A6', 'CODIGO');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'FECHA DE CREACION');
$objPHPExcel->getActiveSheet()->setCellValue('C6', 'DESCRIPCION');
$objPHPExcel->getActiveSheet()->setCellValue('D6', 'PESO');
$objPHPExcel->getActiveSheet()->setCellValue('E6', 'VOLUMEN');
$objPHPExcel->getActiveSheet()->setCellValue('F6', 'MEDIDAS');
$objPHPExcel->getActiveSheet()->setCellValue('G6', 'PRESENTACION');
$objPHPExcel->getActiveSheet()->setCellValue('H6', 'DISPLAY POR CAJAS');
$objPHPExcel->getActiveSheet()->setCellValue('I6', 'DISPLAY POR UNIDADES');

if(($this->Session->read('Auth.User.aprueba_pedido')) || ($this->Session->read('Auth.User.rol_id')==2)||($this->Session->read('Auth.User.rol_id')==3)){
	$objPHPExcel->getActiveSheet()->setCellValue('J6', 'COSTO UNITARIO');
	$objPHPExcel->getActiveSheet()->setCellValue('K6', 'COSTO DE ALMACENAMIENTO');
}else{
				$objPHPExcel->getActiveSheet()->getStyle('J6:K6')->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('argb' => 'FF808080')
						)
					)
				);				
}
$objPHPExcel->getActiveSheet()->setCellValue('L6', 'UNIDADES DISPONIBLES');
$objPHPExcel->getActiveSheet()->setCellValue('M6', 'UNIDADES DAÑADAS');
$objPHPExcel->getActiveSheet()->setCellValue('N6', 'UNIDADES RETENIDAS');
$objPHPExcel->getActiveSheet()->setCellValue('O6', 'UNIDADES PROMOCIONALES');
$objPHPExcel->getActiveSheet()->setCellValue('P6', 'UNIDADES TOTALES');
$objPHPExcel->getActiveSheet()->setCellValue('Q6', 'DIAS SIN MOVILIZAR');

if($this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.activo_clasprd1'))
	$objPHPExcel->getActiveSheet()->setCellValue('R6', $this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.clasprd1'));
	else
				$objPHPExcel->getActiveSheet()->getStyle('R6')->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('argb' => 'FF808080')
						)
					)
				);				
if($this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.activo_clasprd2'))	
	$objPHPExcel->getActiveSheet()->setCellValue('S6', $this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.clasprd2'));
	else
				$objPHPExcel->getActiveSheet()->getStyle('S6')->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('argb' => 'FF808080')
						)
					)
				);				
if($this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.activo_clasprd3'))	
	$objPHPExcel->getActiveSheet()->setCellValue('T6', $this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.clasprd3'));
	else
					$objPHPExcel->getActiveSheet()->getStyle('T6')->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('argb' => 'FF808080')
						)
					)
				);			

if($this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.activo_clasprd4'))	
	$objPHPExcel->getActiveSheet()->setCellValue('U6', $this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.clasprd4'));
	else
					$objPHPExcel->getActiveSheet()->getStyle('U6')->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('argb' => 'FF808080')
						)
					)
				);				
if($this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.activo_clasprd5'))	
	$objPHPExcel->getActiveSheet()->setCellValue('V6', $this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.clasprd5'));
else
					$objPHPExcel->getActiveSheet()->getStyle('V6')->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('argb' => 'FF808080')
						)
					)
				);				


for($i = 0; $i < count($data); $i++):
	$disponibles = 0;
	$malas = 0;	
	$retenidas = 0;
	$promo = 0;
	$totales = 0;
	if(isset($data[$i]['Existencia']))
	{
		for($j = 0; $j < count($data[$i]['Existencia']); $j++):
			if($data[$i]['Existencia'][$j]['nestprd'] ==1)
				$disponibles+=$data[$i]['Existencia'][$j]['unidades'];
			if($data[$i]['Existencia'][$j]['nestprd'] ==3)
				$malas+=$data[$i]['Existencia'][$j]['unidades'];
			if($data[$i]['Existencia'][$j]['nestprd'] ==2)
				$retenidas+=$data[$i]['Existencia'][$j]['unidades'];
			if($data[$i]['Existencia'][$j]['nestprd'] ==4)
				$promo+=$data[$i]['Existencia'][$j]['unidades'];
		endfor;	
	}
	if(count($this->Session->read('Auth.User.Cliente.'.$index.'.FAlmacenaje')) > 0)
	{
		/*if($this->Session->read('Auth.User.Cliente.'.$index.'.FAlmacenaje.tipo_factor_id') == 1)
			$calma = ($this->Session->read('Auth.User.Cliente.'.$index.'.FAlmacenaje.valor') * $data[$i]['PConsolidado']['VOLUMEN']);
		elseif($this->Session->read('Auth.User.Cliente.'.$index.'.FAlmacenaje.tipo_factor_id') == 2)
			$calma = ($this->Session->read('Auth.User.Cliente.'.$index.'.FAlmacenaje.valor') *$data[$i]['PConsolidado']['costoPrd']);
		else
			$calma = ($this->Session->read('Auth.User.Cliente.'.$index.'.FAlmacenaje.valor') * $data[$i]['PConsolidado']['PESO']);*/
		if($this->Session->read('Auth.User.Cliente.'.$index.'.FAlmacenaje.tipo_factor_id') == 1)
			 $calma = ( str_replace(',','.',$this->Session->read('Auth.User.Cliente.'.$index.'.FAlmacenaje.valor')) * $data[$i]['PConsolidado']['VOLUMEN']);
		elseif($this->Session->read('Auth.User.Cliente.'.$index.'.FAlmacenaje.tipo_factor_id') == 2)
			$calma = (str_replace(',','.',$this->Session->read('Auth.User.Cliente.'.$index.'.FAlmacenaje.valor')) * $data[$i]['PConsolidado']['CostoPrd']);
		else
			$calma = (str_replace(',','.',$this->Session->read('Auth.User.Cliente.'.$index.'.FAlmacenaje.valor')) * $data[$i]['PConsolidado']['PESO']);
						
	}else
		$calma = 0;
	$totales = $disponibles+$malas+$retenidas+$promo;
	$objRichText = new PHPExcel_RichText();
	$objBoldAnc = $objRichText->createTextRun('Ancho: ');
	$objBoldAnc->getFont()->setBold(true);
	$objRichText->createText($data[$i]['PConsolidado']['Ancho']."\n");
	$objBoldLar = $objRichText->createTextRun('Largo: ');
	$objBoldLar->getFont()->setBold(true);
	$objRichText->createText($data[$i]['PConsolidado']['Largo']."\n");
	$objBoldProf = $objRichText->createTextRun('Profundidad: ');
	$objBoldProf->getFont()->setBold(true);
	$objRichText->createText($data[$i]['PConsolidado']['Profundidad']."\n");
	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getNumberFormat()->setFormatCode('0000');
  	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $data[$i]['PConsolidado']['codi']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, $data[$i]['PConsolidado']['fCreacion']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $data[$i]['PConsolidado']['nombre']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $data[$i]['PConsolidado']['PESO']); 
  	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $data[$i]['PConsolidado']['VOLUMEN']); 
	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row, $objRichText); 
	$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row, $data[$i]['PConsolidado']['presentacion']); 
	$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row, $data[$i]['PConsolidado']['DISPLAYSporCAJA']); 
	
	$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row, $data[$i]['PConsolidado']['UNIDADESporDISPLAY']); 
	
	
	if(($this->Session->read('Auth.User.aprueba_pedido')) || ($this->Session->read('Auth.User.rol_id')==2)||($this->Session->read('Auth.User.rol_id')==3)){

		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$row, $data[$i]['PConsolidado']['costoPrd']); 
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$row, $calma); 	
	}else{
		$objPHPExcel->getActiveSheet()->getStyle('J'.$row.':K'.$row)->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('argb' => 'FF808080')
						)
					)
				);				
	}
	
	$objPHPExcel->getActiveSheet()->SetCellValue('L'.$row, $disponibles); 	
	$objPHPExcel->getActiveSheet()->SetCellValue('M'.$row, $malas); 	
	$objPHPExcel->getActiveSheet()->SetCellValue('N'.$row, $retenidas); 	
	$objPHPExcel->getActiveSheet()->SetCellValue('O'.$row, $promo); 	
	$objPHPExcel->getActiveSheet()->SetCellValue('P'.$row, $totales); 	
	$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$row, $data[$i]['PConsolidado']['rotacion'].' Día(s)'); 	
	
	if($this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.activo_clasprd1'))
		$objPHPExcel->getActiveSheet()->setCellValue('R'.$row, $data[$i]['PConsolidado']['ClasPrd1_descripcion']);
		else
					$objPHPExcel->getActiveSheet()->getStyle('R'.$row)->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('argb' => 'FF808080')
						)
					)
				);				
	if($this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.activo_clasprd2'))	
		$objPHPExcel->getActiveSheet()->setCellValue('S'.$row, $data[$i]['PConsolidado']['ClasPrd2_descripcion']);
		else
					$objPHPExcel->getActiveSheet()->getStyle('S'.$row)->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('argb' => 'FF808080')
						)
					)
				);				
		
	if($this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.activo_clasprd3'))
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$row, $data[$i]['PConsolidado']['ClasPrd3_descripcion']);
			else
					$objPHPExcel->getActiveSheet()->getStyle('T'.$row)->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('argb' => 'FF808080')
						)
					)
				);				

	if($this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.activo_clasprd4'))
		$objPHPExcel->getActiveSheet()->setCellValue('U'.$row, $data[$i]['PConsolidado']['ClasPrd4_descripcion']);
				else
					$objPHPExcel->getActiveSheet()->getStyle('U'.$row)->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('argb' => 'FF808080')
						)
					)
				);				

	if($this->Session->read('Auth.User.Cliente.'.$index.'.Denominacion.activo_clasprd5'))
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$row, $data[$i]['PConsolidado']['ClasPrd5_descripcion']);
			else
					$objPHPExcel->getActiveSheet()->getStyle('V'.$row)->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('argb' => 'FF808080')
						)
					)
				);			

	unset(	$objRichText, 
			$objBoldAnc,
			$objBoldLar, 
			$objBoldProf );
	$row+=1;
endfor;


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);
exit;
?>