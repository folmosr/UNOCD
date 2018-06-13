<?php
App::uses('AppHelper', 'View/Helper');

class CustomFunctionsHelper extends AppHelper {

	public $helpers = array('Html');

	public function ini_object(){
		  	 App::import('Vendor', 'encode', array('file' => 'encode'.DS.'class.encode.php')); 
			 return(new Encode());
	}
	
	public function encode($a){
		$object = $this->ini_object();
		return($object->AsciiToHex(base64_encode($a))); 
	}

	public function decode($a){
		$object = $this->ini_object();
		return(base64_decode($object->HexToAscii($a))); 
	}
	
	public function getNacionalidad($v){
		return(($v==1)?'V':'E');
	 }
	
	public function completeDate($date)
	{
		setlocale(LC_ALL,"es_ES@euro","es_ES","esp"); 
		echo ucwords(strftime("%A %d de %B del %Y", strtotime($date)));
		
	}
	
	
	
	public function cleanFields($data, $flag)
	{
		if($flag)
		{
			$cleaned = array();
			foreach ($data as $k=>$v):
				$cleaned[$k] = strtoupper(trim($v));	
			endforeach;
			return ($cleaned);
		}else
			return (strtoupper(trim($data)));
	}
	
	public function selectedList($data, $subIndex)
	{
		$list = array();
		for($i = 0; $i < count($data); $i++):
		  $list[] =  $data[$i][$subIndex];
		endfor;
		return ($list);
	}
	public function getClientList($data)
	{
	  $list = array();
	  for($i = 0; $i < count($data); $i++ ):
	  	$list[$data[$i]['ncli']]= $data[$i]['ncli'].' - '.$data[$i]['nombre'];
	  endfor;
	  return $list;
	}

	public function getClientIndexInList($data, $key)
	{
	  for($i = 0; $i < count($data); $i++ ):
	  	if ($data[$i]['ncli'] == $key)
			return $i;
	  endfor;
	}
	
	function getSumSolicitados($data, $nestprd)
	{	$sum = 0;
		for($i = 0; $i < count($data); $i++)
		{
				//|| ( $data[$i]['Pedido']['estado']==4 )
			if(($data[$i]['nestprd']== $nestprd) && (   ( $data[$i]['Pedido']['estado']==1 ) || ( $data[$i]['Pedido']['estado']==2 )  )  )
					$sum+=$data[$i]['cantidad'];
		}
		return $sum;
	}
	
	function getEstatusTagColor($data, $w_index)
	{
		
		if(($data[$w_index]['stat_pedido']== 1) && ($data[$w_index]['stat_transf']== 100))
			return 'label-yellow'; 
		elseif(($data[$w_index]['stat_pedido']== 2) && ($data[$w_index]['stat_transf']== 100))
			return 'label-success'; 
		elseif(($data[$w_index]['stat_pedido']== 3) && ($data[$w_index]['stat_transf']== 100))
			return 'label-important'; 
		elseif(($data[$w_index]['stat_pedido']== 4) && ($data[$w_index]['stat_transf']== 175))
			return 'label-important';
		elseif(($data[$w_index]['stat_pedido']== 4) && ($data[$w_index]['stat_transf']== 180))
			return 'label-important'; 
		elseif(($data[$w_index]['stat_pedido']== 4) && ($data[$w_index]['stat_transf']== 185))
			return 'label-important'; 
		else
			return 'label-info'; 
	}
	
	function getWebtrackingStatus($stat_pedido,$stat_transf)
	{
			if(($stat_pedido== 1 ) && ($stat_transf==100)){
				return 'Pediente por Aprobar';
			}elseif(($stat_pedido== 2 ) && ($stat_transf==100)){
				return 'Aprobado';
			}elseif(($stat_pedido== 3 ) && ($stat_transf==100)){
				return 'Cancelado';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==100)){
				return 'Finalizado';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==110)){
				return 'Procesado';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==115)){
				return 'Preparando Pedido';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==160)){
				return 'Despachado';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==170)){
				return 'Entregado';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==175)){
				return 'No Recibido';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==180)){
				return 'Devolución Parcial';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==185)){
				return 'Devolución Total';
			}
	}


	function getWebtrackingUbicacion($stat_pedido,$stat_transf, $cliente)
	{
			if(($stat_pedido== 1 ) && ($stat_transf==100)){
				return 'Oficina Principal - '.$cliente;
			}elseif(($stat_pedido== 2 ) && ($stat_transf==100)){
				return 'Oficina Principal - '.$cliente;
			}elseif(($stat_pedido== 3 ) && ($stat_transf==100)){
				return 'Oficina Principal - '.$cliente;
			}elseif(($stat_pedido== 4 ) && ($stat_transf==100)){
				return 'Dep. Coordinanción - UNOCD';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==110)){
				return 'Dep. Coordinanción - UNOCD';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==115)){
				return 'Almacén - UNOCD';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==160)){
				return 'En Tránsito';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==170)){
				return '(Destinatario)';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==175)){
				return 'Almacén - UNOCD';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==180)){
				return 'Almacén - UNOCD';
			}elseif(($stat_pedido== 4 ) && ($stat_transf==185)){
				return 'Almacén - UNOCD';
			}
	}

}
?>