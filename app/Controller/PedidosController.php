<?php
class PedidosController extends AppController {

	public function beforeFilter()
	{
		parent::beforeFilter();
		
		if (!$this->Auth->loggedIn()) {
				$this->Session->setFlash('Para acceder a este módulo primero debe validarse como <b>Usuario</b> del sistema.', 'flash_custom', array('class' => 'alert-warning'));
				$this->Auth->authError = false;
		}
			
	}

 	public function isAuthorized($usuario) {
		if (($this->Auth->user('rol_id')==1) || ($this->Auth->user('rol_id')==2) || ($this->Auth->user('rol_id')==3)){
            return true;
        }	
	}	
   public $components = array('Paginator', 'PDF');
   public $paginate = array(
       'order' => 'Pedido.id_pedido DESC',
       'limit'=>25,
       'fields'=>array(
			'Pedido.*',
			'Solicitante.id',
			'Solicitante.nombre',
			'Solicitante.apellido',
			'Aprobador.id',
			'Aprobador.nombre',
			'Aprobador.apellido',
			'Subzona.nzon', 
			'Subzona.nsuz', 
			'Subzona.nombre',
			'Client.nombre'
		),
		'joins'=> array(
					array('table' => 'Clientes',
					'alias' => 'Client',
					'type' => 'inner',
					'conditions' => array(
						'Pedido.cliente_id = Client.ncli',
						)
					),
					array('table' => 'clients_users',
					'alias' => 'ClientsUsers',
					'type' => 'inner',
					'conditions' => array(
						'Pedido.id_usuario_solicitante = ClientsUsers.user_id',
					)
				),
				array('table' => 'users',
					'alias' => 'Usuario',
					'type' => 'inner',
					'conditions' => array(
						'ClientsUsers.user_id = Usuario.id'
						
					)
				)
			),
		  'contain'=>array(
										'PedidosProducto'=>array(
																		'Producto'=>array(
																				'fields'=>array(	
																								'Producto.codi',
																								 'Producto.nombre'
																						 ),
																				'Clasprd1',
																				'Clasprd2',
																				'Clasprd3',
																				'Clasprd4',
																				'Clasprd5'
																		)
																		
													),													
													'Solicitante'=>array(
															'fields'=>array(
																		'Solicitante.id',
																		'Solicitante.nombre', 
																		'Solicitante.apellido'
															)
															
														),
													'Aprobador'=>array(
															'fields'=>array(
																		'Aprobador.id',
																		'Aprobador.nombre',
																		 'Aprobador.apellido'
															)
														),
													'Estatu'=>array(
															'fields'=>array(
																'Estatu.id',
																'Estatu.descripcion'
															)
															
													), 
													'Subzona'=>array(
															'Zona'
													), 
													'Destinatario'=>array(
															'fields'=>array(
																		'Destinatario.nombre',
																		'Destinatario.direccion',
																		'Destinatario.telefonos'
															)
													),
													'Webtracking'=>array(
															'order'=>array('stat_pedido', 'stat_transf')
														     
													),
													'Clifactura'
											),
        
    );
	
	public function listar()
	{
	    
		$conditions =  array();
		$subconditions = array();
		$arguments = array();
		$config_vars = array(
				
				'estatus'			=> $this->Pedido->Estatu->find('list', array('fields' => array('id', 'descripcion'), 'order'=>'Estatu.descripcion')),
				'title_for_mod' 	=> 'Pedidos',
				'subtitle_for_mod'  => 'Consulta de Pedidos',
				'nCliSelected' => NULL
		);
	   if(isset($this->passedArgs['cliente']))
		{
			$arguments['cliente'] = $this->passedArgs['cliente'];
			$config_vars['nCliSelected'] = $this->Convert->decode($this->passedArgs['cliente']);
			$this->paginate['joins'][1]['conditions'][1]  = array('ClientsUsers.cliente_id '=>$this->Convert->decode($this->passedArgs['cliente']));
			
		}else{
			$arguments['cliente'] = $this->Convert->encode($this->Auth->user('Cliente.0.ncli'));
			$this->paginate['joins'][1]['conditions'][1] = array('ClientsUsers.cliente_id '=>$this->Auth->user('Cliente.0.ncli'));
		}
		
		
		
		if(( ($this->Auth->user('rol_id')==1) && ($this->Auth->user('aprueba_pedidos')==1) ) || ($this->Auth->user('rol_id')>1) )
		 $config_vars['solicitantes']	= $this->Pedido->Solicitante->find('list', array(
									'fields' => array(
												'Solicitante.id', 
												'full_name'
											), 
									'joins'=> array(
												array('table' => 'clients_users',
															'alias' => 'ClientsUsers',
															'type' => 'inner',
															'conditions' => array(
																	'Solicitante.id = ClientsUsers.user_id',
																				$this->paginate['joins'][1]['conditions'][1]
																	)
																),
																							
														),
									'conditions'=>array(
														'realiza_pedidos'=>1,
														'rol_id != 2'
													),																														
											'order'=>'Solicitante.apellido'
									)
		);
		if($this->request->is("post")) {
			 $url     = array('action'=>'listar');
			 $filters = array();
			if((isset($this->data['Pedido']['id_pedido'])) && ($this->data['Pedido']['id_pedido']))
				$filters['pedido'] = $this->Convert->encode($this->data['Pedido']['id_pedido']);
			if(isset($this->data['Pedido']['id_solicitante']) && ($this->data['Pedido']['id_solicitante']))
				$filters['usuario'] = $this->Convert->encode($this->data['Pedido']['id_solicitante']);
			if(isset($this->data['Pedido']['ncli']) && ($this->data['Pedido']['ncli']))
				$filters['cliente'] = $this->Convert->encode($this->data['Pedido']['ncli']);
			if(($this->data['Pedido']['id_status']) && ($this->data['Pedido']['id_status']))
				$filters['estado'] = $this->Convert->encode($this->data['Pedido']['id_status']);
				if((isset($this->data['Pedido']['f_desde'])) && (isset($this->request->data['Pedido']['f_hasta'])) && (($this->data['Pedido']['f_desde'])&&($this->data['Pedido']['f_hasta']))){
						$filters['d'] = $this->Convert->encode($this->data['Pedido']['f_desde']); 
						$filters['h'] = $this->Convert->encode($this->data['Pedido']['f_hasta']);
				}
			$this->redirect(array_merge($url,$filters)); 
		}
		
		if(isset($this->passedArgs['pedido'])){
			$arguments['pedido'] = $this->passedArgs['pedido'];
			$conditions['Pedido.id_pedido'] = $this->Convert->decode($this->passedArgs['pedido']);
		}
		if(isset($this->passedArgs['usuario']))
		{
			$arguments['pedido'] = $this->passedArgs['usuario'];
			$conditions['Pedido.id_usuario_solicitante'] = $this->Convert->decode($this->passedArgs['usuario']);
		}
		if(isset($this->passedArgs['estado']))
		{
			$arguments['estado'] = $this->passedArgs['estado'];
			$conditions['Pedido.estado'] = $this->Convert->decode($this->passedArgs['estado']);
		}
		
		if((isset($this->passedArgs['d'])) && (isset($this->passedArgs['h'])))
		{
			$arguments['d'] = $this->Convert->decode($this->passedArgs['d']);
			$arguments['h'] = $this->Convert->decode($this->passedArgs['h']);
			$conditions['Pedido.fecha_solicitud BETWEEN ? AND ?'] = array( preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $this->Convert->decode($this->passedArgs['d']).' 00:00'), preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $this->Convert->decode($this->passedArgs['h']).' 00:00'));				
		}
		
		if(isset($this->passedArgs['cliente'])) 
				$config_vars['ncliLogedInn'] = $this->Convert->decode($this->passedArgs['cliente']); 
		else 
			$config_vars['ncliLogedInn'] = $this->Auth->user('Cliente.0.ncli');  
		$config_vars['indexUser'] = $this->getIndexUser($config_vars['ncliLogedInn']);
        
		if(!$this->Auth->user('aprueba_pedidos') && ($this->Auth->user('rol_id')==1))
			$conditions['Pedido.id_usuario_solicitante'] = $this->Auth->user('id'); 
		$conditions['Pedido.cliente_id'] = $this->Auth->user('Cliente.'.$config_vars['indexUser'].'.ncli'); 
		
		if(count($conditions) > 0)
			$this->paginate['conditions'] = $conditions;
		
		
		
		$this->Paginator->settings = $this->paginate;
		$config_vars['data'] = $this->Paginator->paginate();
		$config_vars['arguments'] = $arguments;
		$config_vars['notificacionesVars'] = $this->get_notificaciones($config_vars['ncliLogedInn']);
		$this->set($config_vars);
	
	
	
	}
	
	
	public function crear()
	{
	  ini_set('max_execution_time',300);
	  if($this->request->is('post'))
	  {
			$var_new_array = array();
			$ncli = 0;
			for($i = 0; $i < count ($this->request->data['PedidoProductos']['ProductoId']); $i++):
				$ncli = $this->request->data['PedidoProductos']['ncli'][$i];
				$var_new_array[] = array('producto_id'=>$this->request->data['PedidoProductos']['ProductoId'][$i], 'cantidad'=>$this->request->data['PedidoProductos']['cantidad'][$i], 'nestprd'=>$this->request->data['PedidoProductos']['nestprd'][$i]);	
			endfor;
			$this->request->data['Pedido']['id_usuario_solicitante'] = $this->Auth->user('id');
			$this->request->data['Pedido']['cliente_id'] = $ncli;
			$this->request->data['Pedido']['tipo_viaje'] = ($this->request->data['Pedido']['tipo_viaje']==1)?'normal':'expreso';
			$this->request->data['PedidosProducto'] = $var_new_array;
                        unset($this->request->data['PedidoProductos']);
                        if( $this->__isDisponible($this->request->data['PedidosProducto']))
                       {
			if($this->Pedido->saveAssociated($this->request->data))
			{
						$this->Notificacion->save(array(
										'id_pedido'=>$this->Pedido->id,
										'estado'=>FALSE,
										'actor'=>1,
										'msj'=>'El Pedido <a href="/unocd/pedidos/listar/pedido:'.$pedido_key.'">#'.$this->Pedido->id.'</a> está <li class="label label-yellow">por aprobar</li>' 
										)
							);
						  
							$this->__send($this->data, $ncli, $this->Pedido->id, 0, NULL, NULL);
							$this->__removeFile($this->Pedido->id);
						
							$this->Session->setFlash('<b>Datos de Pedido</b> procesados con éxito.', 'flash_custom', array('class' => 'alert-success'));
							$this->redirect('/productos/inventario/');		
				}else{
							$this->Session->setFlash('Han ocurrido errores durante el proceso.', 'flash_custom', array('class' => 'alert-warning'));
							$this->redirect('/productos/inventario/');		
			}
		    }else{
		         
		    	$this->redirect('/productos/inventario/');	
                    }
		}
	   $this->autoRender = false;
	}
	
        private function __isDisponible($data){
           $total = 0;
           
           for($i = 0; $i < count($data); $i++){
              $disponibilidad = $this->Pedido->PedidosProducto->find('all', array(
                                                               'fields'=>array(
                                                                               'PedidosProducto.*',
                                                                               'Existencia.*'
                                                               ),
                                                              'recursive'=>-1,
                                                              'joins'=>array(
                                                                array( 'table' => 'Existencia',
					                               'alias' => 'Existencia',
					                               'type' => 'inner',
					                               'conditions' => array(
						                               'PedidosProducto.producto_id = Existencia.codi',
                                                                               'Existencia.nestprd'=>$data[$i]['nestprd']
					                                 )   
                                                                      ),
                                                               array( 'table' => 'pedidos',
					                               'alias' => 'Pedido',
					                               'type' => 'inner',
					                               'conditions' => array(
						                               'PedidosProducto.pedido_id = Pedido.id_pedido',
                                                                                'Pedido.estado IN'=>array(1,2,4)
					                                 )   
                                                                      )
                                                                ),
                                                              'conditions'=>array(
                                                                             'PedidosProducto.producto_id'=>$data[$i]['producto_id'],
                                                                             'PedidosProducto.nestprd'=>$data[$i]['nestprd'],
                                                                        )
                                              )
                  );
                if(count($disponibilidad) > 0 )
                {
                    $pedidas    = 0;
                    $existente  = 0;
                
                    for($j = 0; $j < count($disponibilidad); $j++){
                        $pedidas= $disponibilidad[$j]['PedidosProducto']['cantidad'];
                        $existente= $disponibilidad[$j]['Existencia']['unidades'];  
                    }
                 
                  $total = $existente - ($pedidas+$data[$i]['cantidad']);
                 
               }
                if($total < 0){
                  $this->Session->setFlash('El Pedido no se realizó ya que la cantidad solicitada para el <b>Producto con código '.$data[$i]['producto_id'].'</b> supera su disponibilidad .', 'flash_custom', array('class' => 'alert-warning'));
                   return false;
                  }else{
                     return true;
                  }
               
            } 
         }
	
	
	
		public function edit($pedido_key, $status_key, $ncli)
	{
		
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<Pedido>';
		$this->Pedido->unBindModel(array(belongsTo => array('Solicitante', 'Subzona', 'Aprobador', 'Destinatario')));
		if ($this->Pedido->updateAll(
				array(
					'Pedido.estado' => $this->Convert->decode($status_key),
					'Pedido.id_usuario_aprobador'=>$this->Auth->user('id'),
					'Pedido.fecha_proceso' => "'".date("Y-m-d H:i")."'"
				),
				array('Pedido.id_pedido' => $this->Convert->decode($pedido_key))
			)){
					$xml.='<id_status>'.$this->Convert->decode($status_key).'</id_status>';
					
				
								if($this->Convert->decode($status_key)==2){
					$notificacion_array = array(
												0 => array('id_pedido'=>$this->Convert->decode($pedido_key)),  
												1 => array('id_pedido'=>$this->Convert->decode($pedido_key))
					);
					$notificacion_array[0]['actor'] = 2; 
					$notificacion_array[1]['actor'] = 0; 
					$notificacion_array[0]['msj'] = 'El Pedido <a href="/unocd/pedidos/listar/pedido:'.$pedido_key.'">#'.$this->Convert->decode($pedido_key).'</a> fue <li class="label label-success"> aprobado</li>';
					$notificacion_array[1]['msj'] = 'El Pedido <a href="/unocd/pedidos/listar/pedido:'.$pedido_key.'">#'.$this->Convert->decode($pedido_key).'</a> fue <li class="label label-success"> aprobado</li>';
					$paso = 1;
				}if($this->Convert->decode($status_key)==3){
					$notificacion_array = array(
												'id_pedido'=>$this->Convert->decode($pedido_key),
												'actor'=>0,
												'msj'=>'El Pedido <a href="/unocd/pedidos/listar/pedido:'.$pedido_key.'">#'.$this->Convert->decode($pedido_key).'</a> fue <li class="label label-important">negado</li>'
					); 
					$paso = 3;
				}if($this->Convert->decode($status_key)==4){
					$notificacion_array = array(
												0 => array('id_pedido'=>$this->Convert->decode($pedido_key)),  
												1 => array('id_pedido'=>$this->Convert->decode($pedido_key))
					);
					$notificacion_array[0]['actor'] = 1; 
					$notificacion_array[1]['actor'] = 0; 
					$notificacion_array[0]['msj'] = 'El Pedido <a href="/unocd/pedidos/listar/pedido:'.$pedido_key.'">#'.$this->Convert->decode($pedido_key).'</a> fue <li class="label label-info">finalizado</li>';
					$notificacion_array[1]['msj'] = 'El Pedido <a href="/unocd/pedidos/listar/pedido:'.$pedido_key.'">#'.$this->Convert->decode($pedido_key).'</a> fue <li class="label label-info">finalizado</a>';
					$paso = 2;
				}
			  	$this->Notificacion->saveMany($notificacion_array);
				
				$this->__send($this->Pedido->find('first', array('fields'=>array('id_usuario_solicitante'), 'conditions'=>array('id_pedido'=>$this->Convert->decode($pedido_key)), 'recursive'=>-1)), $ncli, $this->Convert->decode($pedido_key),$paso,  NULL, NULL);
							//$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> El Pedido con el número de Orden <b>'.$this->Convert->decode($pedido_key).'</b> ha sido procesado satisfactoriamente.<br /><b>NOTA:</b> No hubo una comunicación efectiva vía Email ya que no se pudo establecer contacto con el servivor SMTP.', 'default', array('class' => 'alert alert-success alert-dismissable'));
					//else
						
						//$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> El Pedido con el número de Orden <b>'.$this->Convert->decode($pedido_key).'</b> ha sido procesado satisfactoriamente.', 'default', array('class' => 'alert alert-success alert-dismissable'));
				$xml.='<respuesta>1</respuesta>';
			}else
				$xml.='<respuesta>0</respuesta>';
				//$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> Han ocurrido errores al intentar de procesar los datos del pedido.', 'default', array('class' => 'alert alert-danger alert-dismissable'));
			echo $xml.='</Pedido>';	
			//$this->redirect('/pedidos/listar/');	
		    $this->autoRender = false;
	}

	
	

	public function __send($order_data, $ncli, $order_id, $steps, $modulo , $producto ) { 
		
		$titulo = (is_null($modulo))?'Solicitud de Pedido -  Sistema de Administración de Inventario UNOCD':'Actualización de Pedido -  Sistema de Administración de Inventario UNOCD';
		$this->loadModel('Usuario');
		$config = array('fields'=>array(
										'Usuario.nombre',
										'Usuario.apellido',
										'Usuario.correo',
										'Usuario.rol_id'
									),
									'joins'=> array(
													array('table' => 'clients_users',
													'alias' => 'ClientsUsers',
													'type' => 'inner',
													'conditions' => array(
														'Usuario.id = ClientsUsers.user_id',
														'ClientsUsers.cliente_id'=>$ncli
													)
												)
						),
	 );			 
	  if($steps == 0){
		 $config['conditions'][0]['OR'] = array(
		 											array(
														'Usuario.id' => (!is_null($modulo))?$order_data:$order_data['Pedido']['id_usuario_solicitante'],
														'Usuario.notificaciones' => 1
														),
													array(
														'Usuario.rol_id' => 1,
														'Usuario.aprueba_pedidos' => 1,
														'Usuario.notificaciones' => 1,
													)	
											);

	  }elseif($steps==1){
		 $config['conditions'][0]['OR'] = array(
		 											array(
														'Usuario.id' => $order_data['Pedido']['id_usuario_solicitante'],
														'Usuario.notificaciones' => 1,
														),
													array(
														'Usuario.rol_id' => 3
													),
													array(
														'Usuario.rol_id' => 1,
														'Usuario.notificaciones' => 1,
														'Usuario.aprueba_pedidos' => 1,
														
													)	,
												
											);

	  }elseif($steps==2){
		 $config['conditions'][0]['OR'] = array(
		 											array(
														'Usuario.id' =>$order_data['Pedido']['id_usuario_solicitante'],
														'Usuario.notificaciones' => 1,
														),
													array(
														'Usuario.rol_id' => 1,
														'Usuario.aprueba_pedidos' => 1,
														'Usuario.notificaciones' => 1,
													),
													array(
														'Usuario.rol_id' => 3
													)/*,
													array(
														'Usuario.id' => $this->Auth->user('id'),
														'Usuario.notificaciones' => 1,
													)	*/	
											);
	  }else{
			 $config['conditions'][0]['OR'] = array(
		 											array(
														'Usuario.id' => $order_data['Pedido']['id_usuario_solicitante'],
														'Usuario.notificaciones' => 1,
														),
													array(
														'Usuario.rol_id' => 1,
														'Usuario.aprueba_pedidos' => 1,
														'Usuario.notificaciones' => 1,
													)	
				);
	  }
	  
	  if($steps > 0){
			if($this->Auth->user('rol_id')==2){
					 $config['conditions'][0]['OR'][] = array(
														'Usuario.id' => $this->Auth->user('id'),
														'rol_id'=>2,
														'Usuario.notificaciones' => 1,
														
													);	
																		
				
			}
	  }
	  $recipients = $this->Usuario->find('all', $config);
	 
	 if($modulo != 3)
	 {
	  $this->data = 		 $this->Pedido->find('first', array(
	  										
		 									'conditions'=>array(
												'Pedido.id_pedido'=>$order_id
											),
											'contain'=>array(
												'Destinatario'=>array(
												 'fields'=>array(
												  	'nombre',
													'telefonos',
													'direccion'
												  )
												),
												'Subzona'=>array(
													'Zona'
												),
												'Solicitante'=>array(
													'fields'=>array(
														'apellido',
														'nombre'
													)
												),
												'Estatu'=>array(
													'fields'=>array(
															'Estatu.id', 
															'Estatu.descripcion'
														)
												),
												'PedidosProducto'=>array(
													'PConsolidado'=>array(
														'fields'=>array(
															'codi',
															'nombre',
															'peso',
															'ancho',
															'presentacion',
															'volumen',
															'largo',
															'profundidad'
														)
													)
												)
											)
										)
			);
		 $this->PDF->makePDF($this->data, $modulo); 	
		 $this->Email->attach(WWW_ROOT . 'files'.DS.'pdf' . DS . 'constancia_'.$this->data['Pedido']['id_pedido'].'.pdf'); 
	    }
		if($modulo == 3)
 		 	$this->set(array('modulo'=>$modulo, 'pedido'=>$order_id));
			else
			$this->set(array('modulo'=>$modulo, 'producto'=>$producto));
		 $this->Email->from = $this->Auth->user('correo');
		 $this->Email->fromName = $this->Auth->user('apellido'). ' '.$this->Auth->user('nombre');
		 $this->Email->subject = $titulo; 
		 $this->autoRender= false;
		 return $this->Email->send($recipients);
		
		 
    } 	


	public function __removeFile($pedido_id)
	{
		if (file_exists(WWW_ROOT . 'files'.DS.'pdf' . DS . 'constancia_'.$pedido_id.'.pdf'))
			unlink(WWW_ROOT . 'files'.DS.'pdf' . DS . 'constancia_'.$pedido_id.'.pdf');
	}
	

	public function reporte_excel()
	{		
		$conditions_config = array();
		$join_conditions_config = array();
		if(isset($this->passedArgs['pedido']))
			$conditions_config['Pedido.id_pedido'] = $this->Convert->decode($this->passedArgs['producto']);
		if(isset($this->passedArgs['usuario']))
			$conditions_config['Pedido.id_usuario'] = $this->Convert->decode($this->passedArgs['usuario']);
		if(isset($this->passedArgs['estado']))
			$conditions_config['Pedido.estado'] = $this->Convert->decode($this->passedArgs['estado']);
		if((isset($this->passedArgs['d'])) && (isset($this->passedArgs['h'])))
			$conditions_config['Pedido.fecha_proceso BETWEEN ? AND ?'] = array( preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $this->Convert->decode($this->passedArgs['d']).' 00:00'), preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $this->Convert->decode($this->passedArgs['h']).' 00:00'));				
	   if(isset($this->passedArgs['cliente']))
			$join_conditions_config = array('ClientsUsers.cliente_id '=>$this->Convert->decode($this->passedArgs['cliente']));
			
		$config = array(
					'fields'=>array(
						'id_pedido',
						'id_usuario_solicitante',
						'f_solicitud',
						'fecha_proceso',
						'estado'
					),
				'joins'=> array(
							array('table' => 'clients_users',
							'alias' => 'ClientsUsers',
							'type' => 'inner',
							'conditions' => array(
								'Pedido.id_usuario_solicitante = ClientsUsers.user_id',
							)
						),
						array('table' => 'users',
							'alias' => 'Usuario',
							'type' => 'inner',
							'conditions' => array(
								'ClientsUsers.user_id = Usuario.id',
								$join_conditions_config
							)
						),
																					
					),
					'conditions'=>array(
						$conditions_config
					),
					'contain'=>array(
						'Solicitante'=>array(
							'fields'=>array(
								'nombre',
								'apellido'
							),
							'Cliente'=>array(
								'conditions'=>array(
									'Cliente.ncli'=>$this->Auth->user('Cliente.0.ncli')
								)
							)
						),
						'Clifactura',
						'Webtracking'=>array(
									'order'=>array('stat_pedido', 'stat_transf')
														     
						),
						'Estatu'=>array(
							'fields'=>array(
									'id',
									'descripcion'
								)
						)
					),		
						
		);
		
		$data = $this->Pedido->find('all',$config);		
		$this->set(compact('data'));

	}
	
	public function update_pedido()
	{
		 if($this->request->is('post'))
		 {
			if($this->request->data['PedidosProducto']['FormAccion']==1){
				
					$id_pedido = $this->Convert->decode($this->request->data['PedidosProducto']['id_pedido']);
					if($this->Pedido->PedidosProducto->save(array(
													'id'=>$this->request->data['PedidosProducto']['id'],
													'cantidad'=>$this->request->data['PedidosProducto']['cantidad'][$this->request->data['PedidosProducto']['index']]
													)
						)){
						if($this->__send($this->Convert->decode($this->request->data['PedidosProducto']['id_solicitante']),$this->Convert->decode($this->request->data['PedidosProducto']['id_cliente']),$id_pedido, 0, 1, $this->Convert->decode($this->request->data['PedidosProducto']['id_producto']))){
									
									$this->__removeFile($id_pedido);
									$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> El Pedido con el número de Orden <b>'.$id_pedido.'</b> ha sido procesado satisfactoriamente.', 'default', array('class' => 'alert alert-success alert-dismissable'));
							}else
									$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> El Pedido con el número de Orden <b>'. $id_pedido.'</b> ha sido procesado satisfactoriamente, más hubo problemas de comunicación a la hora en enviar la notificación de actualización.', 'default', array('class' => 'alert alert-success alert-dismissable'));
							$this->redirect('/pedidos/listar/');	
							
						}
			}elseif($this->request->data['PedidosProducto']['FormAccion']==2){
					$id_pedido = $this->Convert->decode($this->request->data['PedidosProducto']['id_pedido']);
					 if($this->Pedido->PedidosProducto->deleteAll(array('PedidosProducto.id' => $this->request->data['PedidosProducto']['id']), false)){
						if($this->Pedido->PedidosProducto->find('count', array('conditions'=>array('PedidosProducto.pedido_id'=>$this->Convert->decode($this->request->data['PedidosProducto']['id_pedido'])))) > 0){
							if($this->__send($this->Convert->decode($this->request->data['PedidosProducto']['id_solicitante']),$this->Convert->decode($this->request->data['PedidosProducto']['id_cliente']), $this->Convert->decode($this->request->data['PedidosProducto']['id_pedido']), 0, 2, $this->Convert->decode($this->request->data['PedidosProducto']['id_producto'])))
									$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> El Pedido con el número de Orden <b>'.$id_pedido.'</b> ha sido procesado satisfactoriamente.', 'default', array('class' => 'alert alert-success alert-dismissable'));
								else
									$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> El Pedido con el número de Orden <b>'.$id_pedido.'</b> ha sido procesado satisfactoriamente, mas hubo problemas de comunicación a la hora en enviar la notificación de actualización.', 'default', array('class' => 'alert alert-success alert-dismissable'));
						}else{
								if($this->Pedido->deleteAll(array('Pedido.id_pedido' => $this->Convert->decode($this->request->data['PedidosProducto']['id_pedido'])), false)){
									if($this->__send($this->Convert->decode($this->request->data['PedidosProducto']['id_solicitante']),$this->Convert->decode($this->request->data['PedidosProducto']['id_cliente']), $this->Convert->decode($this->request->data['PedidosProducto']['id_pedido']), 0, 3))
										$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> El Pedido con el número de Orden <b>'.$id_pedido.'</b> ha sido procesado satisfactoriamente.', 'default', array('class' => 'alert alert-success alert-dismissable'));
									else
										$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> El Pedido con el número de Orden <b>'.$id_pedido.'</b> ha sido procesado satisfactoriamente, más hubo problemas de comunicación a la hora en enviar la notificación de actualización.', 'default', array('class' => 'alert alert-success alert-dismissable'));
									
								}else{
										$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> El Pedido con el número de Orden <b>'.$this->Convert->decode($pedido_key).'</b> no ha sido procesado satisfactoriamente. <br><b>NOTA:</b> Fueron cancelados todos los productos relacionados con el pedido, más el mismo no pudo ser eliminado.', 'default', array('class' => 'alert alert-success alert-dismissable'));
								}
								
							}
					 }
					$this->redirect('/pedidos/listar/');
			}else{
							$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> Es indispensable seleccionar que tipo de acción desea realizar con el <b>Pedido N° '.$this->Convert->decode($this->request->data['PedidosProducto']['id_pedido']).'</b>.', 'default', array('class' => 'alert alert-success alert-dismissable'));
							$this->redirect('/pedidos/listar/');	
			}
		 }else
							$this->redirect('/pedidos/listar/');
		$this->autoRender = false;
	}

    public function do_upload($id_pedido)
	{
    	$maxsize    = 400000;
		$acceptable = array(
			'application/pdf'
		);		
		if(($this->data['Pedido']['file']['size'] >= $maxsize) || ($this->data['Pedido']['file']['size'] == 0)) 
			$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> No ha sido posible cargar el archivo para el Pedido Nº <b>'.$this->Convert->decode($id_pedido).'</b>.<br />NOTA: Revise el tamaño del archivo. (400 KB).', 'default', array('class' => 'alert alert-warning alert-dismissable'));
		elseif((!in_array($this->data['Pedido']['file']['type'], $acceptable)) && (!empty($this->data['Pedido']['file']['type'])))
			$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> No ha sido posible cargar el archivo para el Pedido Nº <b>'.$this->Convert->decode($id_pedido).'</b>.<br />NOTA: Revise el tipo del archivo. (PDF).', 'default', array('class' => 'alert alert-warning alert-dismissable'));
		else{
		
		
	    echo $filename = WWW_ROOT.'files'.DS.'pedidos'.DS.$this->Convert->decode($id_pedido).'.pdf'; 
       /* copy uploaded file */
       if (move_uploaded_file($this->data['Pedido']['file']['tmp_name'],$filename))
            /* save message to session */
			$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> Se ha cargado el archivo satisfactoriamente para el Pedido Nº <b>'.$this->Convert->decode($id_pedido).'</b>.', 'default', array('class' => 'alert alert-success alert-dismissable'));
            /* redirect */
		else
            /* save message to session */
			$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> No ha sido posible cargar el archivo para el Pedido Nº <b>'.$this->Convert->decode($id_pedido).'</b>.', 'default', array('class' => 'alert alert-warning alert-dismissable'));
            /* redirect */
		}
		//$this->redirect($this->referer());	
	}

}
?>