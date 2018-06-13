<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	
    public $components = array(
		'Session',
        'RequestHandler',
		'Paginator',
		'Convert',
		'Email',
		'Auth' => array(
		  'authorize' => array('Controller'),
		  'loginAction' => array(
					'controller' => 'usuarios',
					'action' => 'login',
			),
		  'loginRedirect' => array(
					'controller' => 'productos',
					'action' => 'inventario'
		),
		'logoutRedirect' => array(
					'controller' => 'usuarios',
					'action' => 'login'
		),		
		'authenticate' => array(
					'Form' => array(
						 'contain'   => array(
						 			'Cliente'=>array(
										'Denominacion',
										'FAlmacenaje',
										'FDistribucion'
									)
						 ),
						 'fields' => array('username' => 'correo', 'password'=>'password'),
						 'scope' =>array('status IS TRUE'),
						 'userModel' => 'Usuario',
					     'passwordHasher' => array(
									'className' => 'Simple',
									'hashType' => 'sha256'
								)													
						)
				)
		)
    );
    
	public $helpers  = array(
		'Form', 
		'Html', 
		'CustomFunctions'
	);
	
    var $uses = array('Notificacion');
	
    public function beforeFilter(){
        parent::beforeFilter();
        if($this->RequestHandler->responseType() == 'json'){
            $this->RequestHandler->setContent('json', 'application/json' );
        }
    }
	
   public function  get_notificaciones($cliente_id){
	   if(($this->Auth->user('rol_id') == 1) && ($this->Auth->user('aprueba_pedidos'))  && (!$this->Auth->user('realiza_pedidos')))
		 $conditions =  array(
				'Pedido.cliente_id'=>$this->Auth->user('Cliente.0.ncli'),
				'Notificacion.actor '=>1,
				'Notificacion.estado'=>0 
 		);
	   if(($this->Auth->user('rol_id') == 1) && ($this->Auth->user('aprueba_pedidos')) && ($this->Auth->user('realiza_pedidos')))
		 $conditions =  array(
				'Pedido.cliente_id'=>$this->Auth->user('Cliente.0.ncli'),
				'OR'=>array(
						'Pedido.id_usuario_solicitante'=>$this->Auth->user('id')
				),
				'Notificacion.actor IN'=>array(0,1),
				'Notificacion.estado'=>0 
 		);
	  if(($this->Auth->user('rol_id') == 1) && (!$this->Auth->user('aprueba_pedidos')) && ($this->Auth->user('realiza_pedidos')))
		 $conditions =  array(
				'Pedido.id_usuario_solicitante'=>$this->Auth->user('id'),
				'Notificacion.actor'=>0,
				'Notificacion.estado'=>0 
 		);
	  if($this->Auth->user('rol_id') == 3)
		 $conditions =  array(
				'Pedido.cliente_id'=>$cliente_id,
				'Notificacion.actor'=>2,
				'Notificacion.estado'=>0 
 		);

	if(isset($conditions)){
	    $data = $this->Notificacion->find('all', array('contain'=>array(
							'Pedido'=>array(
											'fields'=>array(
												'Pedido.id_pedido',
												'Pedido.estado',
												'Pedido.fecha_solicitud',
												'Pedido.fecha_Proceso',
										),
							)
					),
					'conditions'=>$conditions,
					'order'=>array('Notificacion.actor', 'Notificacion.creacion'),
					'limit'=>10,
					'recursive'=>-1
 			)
    );	
	return ($data);
	}else
		return false;
   }
	
   public function  update_notificaciones($cliente_id){
	
		$set = array(
			'Notificacion.estado'=>true,
			'Notificacion.vista_por'=>$this->Auth->user('id')
			);
	   if(($this->Auth->user('rol_id') == 1) && ($this->Auth->user('aprueba_pedidos'))  && (!$this->Auth->user('realiza_pedidos')))
		 $conditions =  array(
				'Pedido.cliente_id'=>$this->Auth->user('Cliente.0.ncli'),
				'Notificacion.actor '=>1,
				'Notificacion.estado'=>0 
 		);
	   if(($this->Auth->user('rol_id') == 1) && ($this->Auth->user('aprueba_pedidos')) && ($this->Auth->user('realiza_pedidos')))
		 $conditions =  array(
				'Pedido.cliente_id'=>$this->Auth->user('Cliente.0.ncli'),
				'OR'=>array(
						'Pedido.id_usuario_solicitante'=>$this->Auth->user('id')
				),
				//'Pedido.estado IN '=> array(1,2),
				'Notificacion.actor IN'=>array(0,1),
				'Notificacion.estado'=>0 
 		);
	  if(($this->Auth->user('rol_id') == 1) && (!$this->Auth->user('aprueba_pedidos')) && ($this->Auth->user('realiza_pedidos')))
		 $conditions =  array(
				'Pedido.id_usuario_solicitante'=>$this->Auth->user('id'),
				//'Pedido.estado IN '=> array(2,3,4),
				'Notificacion.actor'=>0,
				'Notificacion.estado'=>0 
 		);
	  if($this->Auth->user('rol_id') == 3)
		 $conditions =  array(
				'Pedido.cliente_id'=>$cliente_id,
				'Notificacion.actor'=>2,
				'Notificacion.estado'=>0 
 		);
	if(isset($conditions))
	$this->Notificacion->updateAll(
			 $set,
   		 	$conditions
		);
   }	
	
	public function get_info($key, $user_id)
	{
		$this->response->type('xml');
		$data = $this->PConsolidado->find('first', array(
													'conditions'=>array('PConsolidado.codi'=>$key),
													'contain'=>array(
													   		'Existencia',
															'PedidosProducto'=>array(
																'Pedido'=>array(
																		'fields'=>array(
																			'estado'
																		)
																)
															)
													   )
													)
			);
		$index = $this->getIndexUser($user_id);
		$disponibles = 0;
		$malas = 0;	
		$solicitadas = 0;
		$retenidas = 0;
		$promo = 0;
		$totales = 0;
		if(isset($data['PedidosProducto'])){
				for($i = 0; $i < count($data['PedidosProducto']); $i++):
					if((!isset($data['PedidosProducto'][$i]['Pedido']['estado']))||($data['PedidosProducto'][$i]['Pedido']['estado'] == 1 ) || ($data['PedidosProducto'][$i]['Pedido']['estado'] == 2 ))
						$solicitadas+=$data['PedidosProducto'][$i]['cantidad'];
				endfor;
		
		}if(isset($data['Existencia']))
		{
			for($j = 0; $j < count($data['Existencia']); $j++):
				if($data['Existencia'][$j]['nestprd'] ==1)
					$disponibles+=$data['Existencia'][$j]['unidades'];
				if($data['Existencia'][$j]['nestprd'] ==3)
					$malas+=$data['Existencia'][$j]['unidades'];
				if($data['Existencia'][$j]['nestprd'] ==2)
					$retenidas+=$data['Existencia'][$j]['unidades'];
				if($data['Existencia'][$j]['nestprd'] ==4)
					$promo+=$data['Existencia'][$j]['unidades'];
			endfor;	
		}
		
		$totales = $disponibles+$malas+$retenidas+$promo;
			

		if($this->Auth->user('Cliente.'.$index.'.FAlmacenaje.tipo_factor_id') == 1)
			 $calma = ( str_replace(',','.',$this->Auth->user('Cliente.'.$index.'.FAlmacenaje.valor')) * $data['PConsolidado']['VOLUMEN']);
		elseif($this->Auth->user('Cliente.'.$index.'.FAlmacenaje.tipo_factor_id') == 2)
			$calma = (str_replace(',','.',$this->Auth->user('Cliente.'.$index.'.FAlmacenaje.valor')) * $data['PConsolidado']['CostoPrd']);
		else
			$calma = (str_replace(',','.',$this->Auth->user('Cliente.'.$index.'.FAlmacenaje.valor')) * $data['PConsolidado']['PESO']);
	
		$clasprd1 = ($data['PConsolidado']['clasprd1_descripcion']=='')?'n/a':$data['PConsolidado']['clasprd1_descripcion'];
		$clasprd2 = ($data['PConsolidado']['clasprd2_descripcion'] == '')?'n/a':$data['PConsolidado']['clasprd2_descripcion'];
		$clasprd3 = ($data['PConsolidado']['clasprd3_descripcion']=='')?'n/a':$data['PConsolidado']['clasprd3_descripcion'];
		$clasprd4 = ($data['PConsolidado']['clasprd4_descripcion'] == '')?'n/a':$data['PConsolidado']['clasprd4_descripcion'];
		$clasprd5 = ($data['PConsolidado']['clasprd5_descripcion'] =='')?'n/a':$data['PConsolidado']['clasprd5_descripcion'];	
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<Producto>';
		$xml.= '<calma><![CDATA['.number_format($calma, 7, ',', ' ').']]></calma>';	
		$xml.= '<costo><![CDATA['.$data['PConsolidado']['CostoPrd'].']]></costo>';		
		$xml.= '<dcaja><![CDATA['.$data['PConsolidado']['DISPLAYSporCAJA'].']]></dcaja>';
		$xml.= '<udisp><![CDATA['.$data['PConsolidado']['UNIDADESporDISPLAY'].']]></udisp>';		
		$xml.= '<fcrea><![CDATA['.$data['PConsolidado']['fCreacion'].']]></fcrea>';	
		$xml.= '<ufechae><![CDATA['.$data['PConsolidado']['UFECHAE'].']]></ufechae>';				
		$xml.= '<anti><![CDATA['.$data['PConsolidado']['rotacion'].']]></anti>';
		$xml.= '<disp><![CDATA['.$disponibles.']]></disp>';
		$xml.= '<mala><![CDATA['.$malas.']]></mala>';				
		$xml.= '<rete><![CDATA['.$retenidas.']]></rete>';				
		$xml.= '<prom><![CDATA['.$promo.']]></prom>';	
		$xml.= '<solis><![CDATA['.$solicitadas.']]></solis>';	
		$xml.= '<tota><![CDATA['.$totales.']]></tota>';	
		$xml.= '<cla1><![CDATA['.$clasprd1.']]></cla1>';	
		$xml.= '<cla2><![CDATA['.$clasprd2.']]></cla2>';						
		$xml.= '<cla3><![CDATA['.$clasprd3.']]></cla3>';						
		$xml.= '<cla4><![CDATA['.$clasprd4.']]></cla4>';						
		$xml.= '<cla5><![CDATA['.$clasprd5.']]></cla5>';						
		$xml.='</Producto>';
		echo $xml;
		$this->autoRender = false;
	}
	
	
	

	private function __xmlEscape($string) {
    		
			return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'),  $string);
	}
	
	public function update_solicitados($prd, $pedido, $nwval, $nestprd)
	{
		ini_set('max_execution_time', 300);
		$this->response->type('xml');
	    $xml = '<?xml version="1.0" encoding="UTF-8"?>
		<Pedido>';
	    $cantidad   = $this->Pedido->PedidosProducto->PConsolidado->Existencia->find('first', array(
																	'fields'=>array('unidades'),
																	'conditions'=>array(
																			'Existencia.codi'=>$this->Convert->decode($prd),
																			'Existencia.nestprd'=>$nestprd
																	),
																)
		);
		
		$solicitado = $this->Pedido->PedidosProducto->find('all', array(
													'fields'=>array(
														'sum(PedidosProducto.cantidad)   AS total'
														
														),
													'conditions'=>array(
															'NOT' => array(
																	'id'=>$this->Convert->decode($pedido)
															),
															'producto_id'=>$this->Convert->decode($prd),
															'nestprd'=>$nestprd,
															'Pedido.estado!=3'
													),
													'contain'=>array(
														'Pedido'=>array(
															'fields'=>array('id_pedido', 'cliente_id')
														)
													),			
													'recursive'=>-1,
												)
		);
		$id_pedido = $solicitado[0]['Pedido']['id_pedido'];
		//$ncli = $solicitado[0]['Pedido']['cliente_id'];
		$solicitado = (is_null($solicitado[0][0]['total']))?(int)$nwval:($solicitado[0][0]['total']+$nwval);
		$r = (int)$cantidad['Existencia']['unidades'] - (int)$solicitado;							
		if($r < 0)
			$xml.='<respuesta limite="'.$cantidad['Existencia']['unidades'].'">0</respuesta>';
			else{
				
				/*if($this->Pedido->PedidosProducto->save(array(
													'id'=>$this->Convert->decode($pedido),
													'cantidad'=> $nwval
													)
						))*/
							$xml.='<respuesta>1</respuesta>';
						//else
						//$xml.='<respuesta>2</respuesta>';
			}
		echo $xml.= '</Pedido>';
		$this->autoRender = false;
	}

	
	public function get_destinatarios($item_value, $ncli)
	{
		/*if($item_value!=99)
			$conditions = array('LEFT(UPPER(Destinatario.NOMBRE), 1)'=>$item_value, 'ncli'=>$ncli);
			else
			$conditions = array('LEFT(UPPER(Destinatario.NOMBRE), 1) NOT REGEXP \'^[A-Z1-9]$\'', 'ncli'=>$ncli);*/
		if($item_value!=99){
				$conditions = array(
									
											
											'OR'=>array(
													'AND'=>array(
														'LEFT(UPPER(Destinatario.NOMBRE), 1)'=>$item_value,
														'ncli'=>array($ncli),
													),													
													'idrow'=>14662
									)

											
						);
		}else{
			//'LEFT(UPPER(Destinatario.NOMBRE), 1) NOT REGEXP \'^[A-Z1-9]$\'', 'ncli'=>$ncli	
			$conditions = array(
									
											
											'OR'=>array(
													'AND'=>array(
														'LEFT(UPPER(Destinatario.NOMBRE), 1) NOT REGEXP \'^[A-Z1-9]$\'',
														'ncli'=>array($ncli),
													),													
													'idrow'=>14662
									)

											
						);
		}			
		
		$this->response->type('xml');
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<Lista>';
		$data = $this->Producto->PedidosProducto->Pedido->Destinatario->find('all',
																			array(
																				'fields' => array(
																						'Destinatario.nombre', 
																						'Destinatario.direccion', 
																						'Destinatario.telefonos', 
																						'Destinatario.email',
																						'Destinatario.idrow',
																						'Destinatario.codigo',
																						'Destinatario.nsuz'
																					), 
'contain'=>array(
	'Subzona'=>array(
		'fields'=>array('Subzona.nombre'),
		'Zona'=>array(
			'fields'=>array(
				'Zona.nombre'
			)
		)
	)
),																				'conditions'=>$conditions,
																				'order'=>'Destinatario.nombre',
																				'recursive'=>-1																					
																			)	
		);
	 
		if(count($data) > 0)
		{				
			for($i=0; $i < count($data); $i++):
			 	$szona = NULL;
			 	$zona = NULL;
			 	if(isset($data[$i]['Subzona']['Zona'])){
			 		$szona = $data[$i]['Subzona']['nombre'];
			 		$zona = $data[$i]['Subzona']['Zona']['nombre'];
			 		}
			 		
					$xml.='<Element key="'.$data[$i]['Destinatario']['idrow'].'" codigo="'.$data[$i]['Destinatario']['codigo'].'" address="'.$this->__xmlEscape(ucfirst(strtolower($data[$i]['Destinatario']['direccion']))).'" phone="'.$this->__xmlEscape(ucfirst(strtolower($data[$i]['Destinatario']['telefonos']))).'" email="'.$this->__xmlEscape(ucfirst(strtolower($data[$i]['Destinatario']['email']))).'" nsuz="'.$data[$i]['Destinatario']['nsuz'].'" nsuz_nombre="'.$szona.'" nzon_nombre="'.$this->__xmlEscape(ucfirst(strtolower($zona))).'"  ><![CDATA['.$this->__xmlEscape($data[$i]['Destinatario']['nombre']).']]></Element>';
					
			endfor;
		}
		
		echo $xml.= '</Lista>';
		
		$this->autoRender = false;
	}	
	public function getIndexUser($user_id)
	{
		for($i = 0; $i < count($this->Auth->user('Cliente')); $i++):
			if($this->Auth->user('Cliente.'.$i.'.ncli') == $user_id)
				return $i;
		endfor;
		$this->autoRender = false;
	}
}