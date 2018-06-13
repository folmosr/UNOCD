<?php
ini_set('max_execution_time',600);
$index = $this->CustomFunctions->getClientIndexInList($this->Session->read('Auth.User.Cliente'), $this->CustomFunctions->decode($this->passedArgs['cliente']));
 App::import('Vendor','tcpdf'.DS.'tcpdf'); 
 $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

 // set document information
 $pdf->SetCreator('UNOCD C.A');
 $pdf->SetAuthor('UNOCD C.A');
 $pdf->SetTitle('Reporte de Inventario Cliente: '.$this->Session->read('Auth.User.Cliente.'.$index.'.nombre'));
 $pdf->SetSubject('Reporte de Inventario Cliente: '.$this->Session->read('Auth.User.Cliente.'.$index.'.nombre'));
 $pdf->SetKeywords('UNOCD Almacenadora Distribuidora Invetario Productos');
		
 // set default header data
 $pdf->SetHeaderData('unnamed.png', PDF_HEADER_LOGO_WIDTH, 'REPORTE DE INVENTARIO '.$this->Session->read('Auth.User.Cliente.'.$index.'.nombre'), "Zona Industrial Paracotos, Edo. Miranda - Teléfonos: (0212) 3911431 - (0212)3911254 - UNOCD.COM - info@unocd.com", array(0,0,0), array(0,0,0));
 $pdf->setFooterData();
		
 // set header and footer fonts
 $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
 $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
 // set default monospaced font
 $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
 // set margins
 $pdf->SetMargins(PDF_MARGIN_LEFT, 35, PDF_MARGIN_RIGHT);
 $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
 $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
 // set image scale factor
 $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

 ////////////////////////////////// DATOS ////////////////////////////////////////////////////////////

 // set font
 $pdf->SetFont('helvetica', '', 10);
		
 // add a page
 $pdf->AddPage();

 // create some HTML content
 $html = '
 <table width="100%" cellpadding="3" cellspacing="0" >
 	<thead>
		<tr>
			<th colspan="7" align="right"><b>Total de Registros:</b>'.count($data).'</th>
		</tr>
		<tr style="background-color:#000000;">
			<th width="120" style="color:#FFF;font-weight:bold;text-align:center;">CODIGO</th>
			<th width="295" style="color:#FFF;font-weight:bold;text-align:center;">DESCRIPCION</th>
			<th width="120" style="color:#FFF;font-weight:bold;text-align:center;">DISPONIBLES</th>
			<th width="120" style="color:#FFF;font-weight:bold;text-align:center;">DAÑADAS</th>
			<th width="120" style="color:#FFF;font-weight:bold;text-align:center;">RETENIDAS</th>
			<th width="120" style="color:#FFF;font-weight:bold;text-align:center;">PROMOCIONALES</th>
			<th width="120" style="color:#FFF;font-weight:bold;text-align:center;">TOTALES</th>
		</tr>
	</thead>
	<tbody>';
  $color = '#F7F7F7';		
  for($i = 0; $i < count($data); $i++):
  	$color = ($color=='#F7F7F7')?'#FFF':'#F7F7F7';
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
	$totales = $disponibles+$malas+$retenidas+$promo;

		$html.='<tr style="background-color:'.$color.'">
			<td width="120" align="left">'.$data[$i]['PConsolidado']['codi'].'</td>
			<td width="295" align="left">'.$data[$i]['PConsolidado']['nombre'].'</td>
			<td width="120"  align="center">'.$disponibles.'</td>
			<td width="120"  align="center">'.$malas.'</td>
			<td width="120"  align="center">'.$retenidas.'</td>
			<td width="120"  align="center">'.$promo.'</td>
			<td width="120"  align="center">'.$totales.'</td>
		</tr>';
  endfor;	
	$html.='</tbody>	
 </table>
 ';
 // output the HTML content
 $pdf->writeHTML($html, true, false, true, false, '');
 // reset pointer to the last page
 $pdf->lastPage();
 ////////////////////////////7 CREACION DLE ARCHIVO /////////////////////////////////////////////////
 echo $pdf->Output('reporte_inventario_'.$this->Session->read('Auth.User.Cliente.'.$index.'.ncli').'.pdf', 'I');


?>