<?php
class AdministradoresController extends AppController {

	public function beforeFilter()
	{
		parent::beforeFilter();
		if (!$this->Auth->loggedIn()) {
				$this->Session->setFlash('Para acceder a este módulo primero debe validarse como <b>Usuario</b> del sistema.', 'flash_custom', array('class' => 'alert-warning'));
				$this->Auth->authError = false;
		}
			
	}

 	public function isAuthorized($usuario) {
		if ($this->Auth->user('rol_id')==2){
            return true;
        }	
	}

 


    public $components = array('Paginator');
    public $uses = array('Usuario');
    public $paginate = array(
        'limit' =>100,
        'order' => array(
            'Usuario.apellido' => 'asc'
        ),
		'conditions'=>array(
			'Usuario.rol_id'=>2,
			
		),
		'recursive'=>-1		
    );

	public function crear()
	{
		$config_vars = array(
			'title_for_mod' => 'Adminsitradores',
			'subtitle_for_mod'  => 'Creación de Administradores',
			'clientes'   => $this->Usuario->Cliente->find('list', array('fields' => array('ncli', 'clave_nombre'),     'order'=>'nombre')),
			'ncliLogedInn'=>$this->Auth->user('Cliente.0.ncli'),
			'notificacionesVars'=>$this->get_notificaciones($this->Auth->user('Cliente.0.ncli'))
		);
		if($this->request->is("post")) {
			$this->request->data['Usuario']['rpass']  = $this->request->data['Usuario']['password']   = $this->__getNewPass();
			$this->request->data['Usuario']['rol_id']  = 2;
			
			$var_new_array = array();
			for($i = 0; $i < count ($this->request->data['Cliente']['cliente_id']); $i++):
				$var_new_array[] = array('cliente_id'=>$this->request->data['Cliente']['cliente_id'][$i]);	
			endfor;
			$this->request->data['Cliente'] = $var_new_array;

			if($this->Usuario->saveAssociated($this->request->data))
			{
							$this->__send();
							$this->Session->setFlash('<b>Datos de Administrador</b> procesados con éxito.', 'flash_custom', array('class' => 'alert-success'));
							$this->redirect('/administradores/crear/');		
				}else{
							$this->Session->setFlash('Han ocurrido errores durante el proceso.', 'flash_custom', array('class' => 'alert-warning'));
							$this->redirect('/administradores/crear/');		
			}
		}
		$this->set($config_vars);
	}

	public function editar($usuario_id)
	{
		if(!is_null($usuario_id))
		{
			if($this->Usuario->find('count', array('conditions'=>array('Usuario.id'=>$this->Convert->decode($usuario_id)))) > 0)
			{			
				$config_vars = array(
					'title_for_mod' => 'Administradores',
					'usuario_id' => $usuario_id,
					'subtitle_for_mod'  => 'Actualización de Datos de Administrador',
					'clientes'   => $this->Usuario->Cliente->find('list', array('fields' => array('ncli', 'clave_nombre'),     'order'=>'nombre')),
					'ncliLogedInn'=>$this->Auth->user('Cliente.0.ncli'),
					'notificacionesVars'=>$this->get_notificaciones($this->Auth->user('Cliente.0.ncli'))		
				);
				if($this->request->is("put")) {
					$this->request->data['Usuario']['id'] = $this->Convert->decode($this->request->data['Usuario']['id']);
					$var_new_array = array();
					for($i = 0; $i < count ($this->request->data['Cliente']['cliente_id']); $i++):
						$var_new_array[] = array('cliente_id'=>$this->request->data['Cliente']['cliente_id'][$i]);	
					endfor;
					$this->request->data['Cliente'] = $var_new_array;
					if(($this->Usuario->find('count', array(
												'conditions'=>array('Usuario.correo'=>$this->request->data['Usuario']['correo'])
												)
									) == 0) || ($this->Usuario->find('count', array(
												'conditions'=>array('Usuario.cedula'=>$this->request->data['Usuario']['cedula'])
												)
									) == 0))
					{
					
						if($this->Usuario->saveAssociated($this->request->data))
						{
										$this->Session->setFlash('<b>Datos de Administrador</b> procesados con éxito.', 'flash_custom', array('class' => 'alert-success'));
										$this->redirect('/administradores/listar/');		
							}else{
										$this->Session->setFlash('Han ocurrido errores durante el proceso.<br />NOTA: Verifiue que ese número de <b>Cédula de Identidad</b> y/o <b>Correo Eléctronico</b> no este registrado en la <b>Base de Datos</b> bajo los datos  de otro usuario.', 'flash_custom', array('class' => 'alert-warning'));
										$this->redirect('/administradores/listar/');		
						}
					}else{
						$this->Session->setFlash('Han ocurrido errores durante el proceso.<br />NOTA: Verifiue que ese número de <b>Cédula de Identidad</b> y/o <b>Correo Eléctronico</b> no este registrado en la <b>Base de Datos</b> bajo los datos  de otro usuario.', 'flash_custom', array('class' => 'alert-warning'));
						$this->redirect('/administradores/crear/');		
					}
				}else{
					$this->data =  $this->Usuario->find('first', array( 
																	'conditions' => array('Usuario.id' => $this->Convert->decode($usuario_id)), 
																	'contain'=>array(
																				'Cliente'=>array(	'fields'=>array(
																												'Cliente.ncli',
																												'Cliente.nombre'
																											)
																								)
																						)
																)
					);
					list($config_vars['nacionalidad'], $config_vars['cedula']) = explode('-', $this->data['Usuario']['cedula']);
				}
				$this->set($config_vars);
			}else{
				$this->Session->setFlash('<em><b>ATENCION:</b> <em><b>Datos de Administrador</b></em> no encontrado.</em>', 'flash_custom', array('class' => 'alert-success'));
				$this->redirect('/administradores/listar/');		
			}
		
		}else{
			$this->Session->setFlash('<em><b>ATENCION:</b> <em><b>Datos de Administrador</b></em> no recibido.</em>', 'flash_custom', array('class' => 'alert-warning'));
			$this->redirect('/administradores/crear/');		
		}
	}
	
	public function listar()
	{ 
	
	   if($this->Auth->user('id')!=1 && $this->Auth->user('id')!=2){
			$conditions = array( 'NOT'=> array(
							'Usuario.id'=>array(1,2)		
				)
			);
		
		}			
		$config_vars = array(
			'title_for_mod' => 'Administradores',
			'subtitle_for_mod'  => 'Gestión de Usuarios Administradores',
			'ncliLogedInn'=>$this->Auth->user('Cliente.0.ncli'),
			'notificacionesVars'=>$this->get_notificaciones($this->Auth->user('Cliente.0.ncli'))
		);
		if($this->request->is("post")) {
			 $url     = array('action'=>'listar');
			 $filters = array();
			 if(isset($this->data['Usuario']['cedula']) && $this->data['Usuario']['cedula'])
					$filters['cedula'] = $this->Convert->encode($this->data['Usuario']['cedula']);
			 if(isset($this->data['Usuario']['correo']) && $this->data['Usuario']['correo'])
					$filters['correo'] = $this->Convert->encode($this->data['Usuario']['correo']);
			 if(isset($this->data['Usuario']['nombre']) && $this->data['Usuario']['nombre'])
					$filters['nombre'] = $this->Convert->encode($this->data['Usuario']['nombre']);
			 if(isset($this->data['Usuario']['f_ini']) && $this->data['Usuario']['f_ini'])
					$filters['f_ini'] = $this->Convert->encode($this->data['Usuario']['f_ini']);
			 if(isset($this->data['Usuario']['f_fin']) && $this->data['Usuario']['f_fin'])
					$filters['f_fin'] = $this->Convert->encode($this->data['Usuario']['f_fin']);

			$this->redirect(array_merge($url,$filters)); 
		}

		if(isset($this->passedArgs['cedula']))
			$conditions['Usuario.cedula'] = $this->Convert->decode($this->passedArgs['cedula']);
			
		if(isset($this->passedArgs['correo']))
			$conditions['Usuario.correo'] = $this->Convert->decode($this->passedArgs['correo']);
			
			
		if(isset($this->passedArgs['nombre'])){
			$conditions['Usuario.nombre LIKE '] = '%'.$this->Convert->decode($this->passedArgs['nombre']).'%';
			$conditions['OR'] = $conditions['Usuario.apellido LIKE '] = '%'.$this->Convert->decode($this->passedArgs['nombre']).'%';;
		}
		if(isset($this->passedArgs['f_ini']) && isset($this->passedArgs['f_fin']))
			$conditions['Usuario.f_registro BETWEEN STR_TO_DATE(?, \'%d/%m/%Y 00:00\') AND STR_TO_DATE(?, \'%d/%m/%Y 00:00\')  '] = array($this->Convert->decode($this->passedArgs['f_ini']),$this->Convert->decode($this->passedArgs['f_fin']))  ;
															
		if(isset($conditions))
			array_push($this->paginate['conditions'] ,$conditions);
		
		$this->Paginator->settings = $this->paginate;
		$config_vars['data'] = $this->Paginator->paginate();
		
		$this->set($config_vars);
	}
	
	public function q_on($usuario_id)
	{
		if(!is_null($usuario_id))
		{
			if($this->Usuario->find('count', array('conditions'=>array('Usuario.id'=>$this->Convert->decode($usuario_id)))) > 0)
			{			
						$data =  $this->Usuario->find('first', array( 'recursive'=>-1, 'conditions' => array('Usuario.id' => $this->Convert->decode($usuario_id))));
						$data['Usuario']['status'] = ($data['Usuario']['status'])?false:true;
						if($this->Usuario->save($data)){
									$this->Session->setFlash('<em><b>Datos de Administrador</b> procesados satisfactoriamente.</em>', 'flash_custom', array('class' => 'alert-success'));
									$this->redirect('/administradores/listar');		
								}else{
									$this->Session->setFlash('<em>Han ocurrido errores al intentar de procesar los datos.</em>', 'flash_custom', array('class' => 'alert-warning'));
									$this->redirect('/administradores/listar/');		
							}			
				
				
			}else{
				$this->Session->setFlash('<em><b>ATENCION:</b> <em><b>Datos de Administrador</b></em> no encontrado.</em>', 'flash_custom', array('class' => 'alert-success'));
				$this->redirect('/administradores/listar/');		
			}
		
		}else{
			$this->Session->setFlash('<em><b>ATENCION:</b> <em><b>Datos de Administrador</b></em> no recibido.</em>', 'flash_custom', array('class' => 'alert-warning'));
			$this->redirect('/administradores/crear/');		
		}

	}
	

	public function reporte_nofoto()
	{
		$config_vars = array(
			'title_for_mod' 	=> 'Administradores',
			'subtitle_for_mod'  => 'Reporte de productos sin foto asociada por cliente',
			'clientes'   		=> $this->Usuario->Cliente->find('list', array('fields' => array('ncli', 'clave_nombre'),     'order'=>'nombre')),
			'ncliLogedInn'		=> $this->Auth->user('Cliente.0.ncli'),
			'data'				=> array('init_set'=>true),
			'nCliSelected'		=> NULL,
			'notificacionesVars'	=> $this->get_notificaciones($this->Auth->user('Cliente.0.ncli'))		
		);

		if($this->request->is("post")) {
			$config_vars['nCliSelected'] = $this->data['PConsolidado']['ncli'];
		    	$this->loadModel('PConsolidado');
			$config_vars['data'] = $this->PConsolidado->find('all',
											  		array(
														'fields'=>array(
																'DISTINCT PConsolidado.codi'
															),
		'joins' => array(
				array(
					'table' => 'Existencia',
					'alias' => 'Existencia',
					'type' => 'INNER',
					'conditions' => array(
						'Existencia.codi = PConsolidado.codi'
					)
				)),
															
														'conditions'=>array(
																'PConsolidado.ncli'=>$this->data['PConsolidado']['ncli']
														),
														'recursive'=>-1
													)
											  );
		  
		  $config_vars['data']['init_set'] = false;
		  
		}
		$this->set($config_vars);
	}
	
    public function reporte_excel($ncli)
	{		
		$this->loadModel('PConsolidado');
		$config_vars['data'] = $this->PConsolidado->find('all',
											  		array(
														'fields'=>array(
																'DISTINCT PConsolidado.codi'
															),
															'joins' => array(
				array(
					'table' => 'Existencia',
					'alias' => 'Existencia',
					'type' => 'INNER',
					'conditions' => array(
						'Existencia.codi = PConsolidado.codi'
					)
				)),
														'conditions'=>array(
																'PConsolidado.ncli'=>$this->Convert->decode($ncli)
														),
														'recursive'=>-1
													)
	  );		
		$config_vars['ncli']=	$this->Convert->decode($ncli);	
		$this->set($config_vars);

	}

	
	private function __send() { 
		 	 
			 $this->Email->from = 'pedido@unocd.com';
			 $this->Email->fromName = 'Administrador (UNOCD)';
			 $this->Email->to = $this->request->data['Usuario']['correo']; 
			 $this->Email->subject = 'Registro de Administrador -  Sistema de Administración de Inventario UNOCD'; 
			 return $this->Email->send();
			 		
    } 	

	private function __getNewPass($length = 8)
	{
		$password = "";
		$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
		$maxlength = strlen($possible);
		if ($length > $maxlength) {
		  $length = $maxlength;
		}
		$i = 0; 
		while ($i < $length) { 
		  $char = substr($possible, mt_rand(0, $maxlength-1), 1);
		  if (!strstr($password, $char)) { 
			$password .= $char;
			$i++;
		  }
	
		}
		return $password;
	}



	
}
?>