<?php
App::uses('Component', 'Controller');
class PDFComponent extends Component {
	
	
	function __construct()
	{
			App::import('Vendor','tcpdf'.DS.'tcpdf'); 
	}
	
	function makePDF($data, $modulo = NULL)
	{
		$titulo = (is_null($modulo))?'SOLICITUD DE PEDIDO':'ACTUALIZACION DE PEDIDO';
		$tel_contacto = (is_null($data['Destinatario']['telefonos']) || $data['Destinatario']['telefonos']=='')?'n/a':$data['Destinatario']['telefonos'];
		$direccion_co = (is_null($data['Destinatario']['direccion']))?'n/a':$data['Destinatario']['direccion'];
		$motivo = (is_null($data['Pedido']['observaciones']) || $data['Pedido']['observaciones']=='')?'n/a':$data['Pedido']['observaciones'];
		
		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator('UNOCD');
		$pdf->SetAuthor('UNOCD');
		$pdf->SetTitle('Orden Nº'.$data['Pedido']['id_pedido']);
		$pdf->SetSubject('Orden Nº'.$data['Pedido']['id_pedido']);
		$pdf->SetKeywords('Orden, Reporte,'.$titulo.', UNOCD, Inventario');
		
		// set default header data
		$pdf->SetHeaderData('unnamed.png', PDF_HEADER_LOGO_WIDTH, $titulo, "Zona Industrial Paracotos, Edo. Miranda - Teléfonos: (0212) 3911431 - (0212)3911254 - UNOCD.COM - info@unocd.com", array(0,0,0), array(0,0,0));
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
		
		// set auto page breaks
		//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		 
		////////////////////////////////// DATOS ////////////////////////////////////////////////////////////
		 
		 
		// set font
		$pdf->SetFont('helvetica', '', 10);
		$color = '#F4F4F4';
		// add a page
		$pdf->AddPage();
		$bk_color = NULL;
		 if($data['Pedido']['estado']==1) 
		 		$bk_color = '#FFDD14'; 
			elseif($data['Pedido']['estado']==2) 
				$bk_color = '#15B74E'; 
			elseif($data['Pedido']['estado']==3) 
				$bk_color ='#FE1010'; 
			elseif($data['Pedido']['estado']==4) 
				$bk_color ='#00BFDD';
					
		// create some HTML content
		$html = '
		<div>
	  <table width="100%" cellpadding="3" cellspacing="0" >
         <thead>
          <tr style="background-color:#F7F7F7;">
            <th colspan="2">
                <b>#ORDEN-'.$data['Pedido']['id_pedido'].'</b>
            </th>
           </tr>
          </thead>  
           <tr> 
                <td><b>Tipo de Viaje:</b></td>
                <td>'. mb_strtoupper($data['Pedido']['tipo_viaje'], 'UTF-8') .'</td>
          </tr>
           <tr> 
                <td><b>Fecha de Emisión:</b></td>
                <td>'.$data['Pedido']['f_solicitud'].'</td>
          </tr>
           <tr> 
                <td><b>Usuario:</b></td>
                <td>'.$data['Solicitante']['apellido'].' '.$data['Solicitante']['nombre'].'</td>
          </tr>
           <tr> 
                <td><b>Zona:</b></td>
                <td>'.$data['Subzona']['Zona']['nombre'].'</td>
          </tr>
           <tr> 
                <td><b>Subzona:</b></td>
                <td>'.$data['Subzona']['nombre'].'</td>
          </tr>
           <tr> 
                <td><b>Persona de Contacto:</b></td>
                <td>'. $data['Destinatario']['nombre'].'</td>
          </tr>
           <tr> 
                <td><b>Teléfono de Contacto:</b></td>
                <td>'.$tel_contacto.'</td>
          </tr>
           <tr> 
                <td><b>Dirección de Entrega:</b></td>
                <td> '.$direccion_co.'</td>
          </tr>
           <tr> 
                <td><b>Motivo/Comentario:</b></td>
                <td>'.$motivo.'</td>
          </tr>
           <tr style="background-color:'.$bk_color.'"> 
                <td><b>Estatus:</b></td>
                <td>'.mb_strtoupper($data['Estatu']['descripcion'], 'UTF-8').'</td>
          </tr>
      </table>
	  <br />
	  <br />
	 <table width="100%" cellpadding="3" cellspacing="0">
        	<thead>
            <tr style="background-color:#F7F7F7;">
            	<th colspan="4"  align="left"><b>DETALLE DEL PEDIDO</b></th>
            </tr>
            <tr style="background-color:#000;">	
                <th  style="color:#FFF" align="center"  width="83"><b>Código</b></th>
            	<th  style="color:#FFF" align="center"  width="270"><b>Descripción</b></th>
            	<th style="color:#FFF" align="center"><b>Cantidad Solicitada</b></th>
				<th style="color:#FFF" align="center"><b>Estado</b></th>
               </tr> 
            </thead>
            <tbody>	  
		';

		for($i = 0; $i < count($data['PedidosProducto']); $i++): 
			
			 if ($data['PedidosProducto'][$i]['nestprd'] == 1) {
			 	$label_class = 'background-color:#5cb85c'; 
				 $title='Disponible';
			 }elseif($data['PedidosProducto'][$i]['nestprd'] == 2) {
			  	$label_class = 'background-color:#ffdd14'; 
			    $title='Retenido';
			 }elseif($data['PedidosProducto'][$i]['nestprd'] == 3) {
				$label_class = 'background-color:#ff702a'; 
			    $title='Dañado(s)';
			 }elseif($data['PedidosProducto'][$i]['nestprd'] == 4) {
				$label_class = 'background-color:#8cbf26';
				$title = 'Retenido Promocional';
			 }

			$color = ($color=='#F4F4F4')?'#FFF':'#F4F4F4';	
			$presentacion_producto = (is_null($data['PedidosProducto'][$i]['PConsolidado']['presentacion']))?'n/a':$data['PedidosProducto'][$i]['PConsolidado']['presentacion'];	
			$html.='<tr style="background-color:'. $color.'">
                    	<td align="center" width="83">'.$data['PedidosProducto'][$i]['PConsolidado']['codi'].'</td>
                    	<td align="left"  width="270"><span>'.ucfirst(strtolower($data['PedidosProducto'][$i]['PConsolidado']['nombre'])).'</span><br /><b>Peso:</b>'.$data['PedidosProducto'][$i]['PConsolidado']['peso'].' gr. <b>Volumen:</b>'. $data['PedidosProducto'][$i]['PConsolidado']['volumen'].' m<sup>3</sup>.<br /><b>Medidas:</b> '. $data['PedidosProducto'][$i]['PConsolidado']['ancho'].' X '. $data['PedidosProducto'][$i]['PConsolidado']['largo'].' X '.$data['PedidosProducto'][$i]['PConsolidado']['profundidad'].' cm.<sup>anc. x lar. x Prof.</sup><br /><b>Prensentación:</b>'.$presentacion_producto.'.</td>
                    	<td align="center">'. $data['PedidosProducto'][$i]['cantidad'].'</td><td align="center"><span style="border-radius: 0;display: inline;padding:.6em ;font-weight: boldcolor: #fff;text-align: center;white-space: nowrap;vertical-align: baseline;'.$label_class.'">'.$title.'</span></td></tr>';
						
		endfor;	
		
		$html.='</tbody></table></div>';
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		// reset pointer to the last page
		$pdf->lastPage();
		////////////////////////////7 CREACION DLE ARCHIVO /////////////////////////////////////////////////
		echo $pdf->Output(WWW_ROOT . 'files'.DS.'pdf' . DS . 'constancia_'.$data['Pedido']['id_pedido'].'.pdf', 'F');
	}
		
}
?>