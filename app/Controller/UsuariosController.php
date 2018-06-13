<?php
class UsuariosController extends AppController {

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow(array('login', 'recovery', 'secure', 'profile', 'logout'));
	}

 	public function isAuthorized($usuario) {
		if (($this->Auth->user('rol_id')==2) || ($this->Auth->user('rol_id')==3)){
            return true;
          }
        }	
			
			

    public $components = array('Paginator', 'Cookie');

    public $paginate = array(
        'limit' =>100,
        'order' => array(
            'Usuario.apellido' => 'asc'
        ),
		'conditions'=>array(
			'Usuario.rol_id'=>1
		),
		'joins'=> array(
					array('table' => 'clients_users',
					'alias' => 'ClientsUsers',
					'type' => 'INNER',
					'conditions' => array(
						'Usuario.id = ClientsUsers.user_id',
					)
				)
		)	,	
		'contain'=>array(
					'Cliente'=>array(
							'fields'=>array(
									'Cliente.ncli',
									'Cliente.nombre'
								)
								
					)
		),
		
    );

	public function crear()
	{
		$config_vars = array(
			'title_for_mod' => 'Usuarios',
			'subtitle_for_mod'  => 'Creación de Usuarios',
			'clientes'   => $this->Usuario->Cliente->find('list', array('fields' => array('ncli', 'clave_nombre'),     'order'=>'nombre')),
			'ncliLogedInn'=>$this->Auth->user('Cliente.0.ncli'),
			'notificacionesVars'=>$this->get_notificaciones($this->Auth->user('Cliente.0.ncli'))						
		);
		if($this->request->is("post")) {
			
			$this->request->data['Usuario']['rpass']  = $this->request->data['Usuario']['password']   = $this->__getNewPass();

			if(($this->Usuario->find('count', array(
										'conditions'=>array('Usuario.correo'=>$this->request->data['Usuario']['correo'])
										)
							) > 0) || ($this->Usuario->find('count', array(
										'conditions'=>array('Usuario.cedula'=> $this->data['Usuario']['nacionalidad_id'].'-'.$this->data['Usuario']['cedula'])
										)
							) > 0))
		{
				$this->Session->setFlash('Han ocurrido errores durante el proceso.<br />NOTA: Verifique que ese número de <b>Cédula de Identidad</b> y/o <b>Correo Eléctronico</b> no este registrado en la <b>Base de Datos</b> bajo los datos  de otro usuario.', 'flash_custom', array('class' => 'alert-warning'));
				$this->redirect('/usuarios/crear/');		
		}else
			{
					if($this->Usuario->saveAssociated($this->request->data))
					{
									$this->__send($this->request->data, 'registre');
									$this->Session->setFlash('<b>Datos de Cliente</b> procesados con éxito.', 'flash_custom', array('class' => 'alert-success'));
									$this->redirect('/usuarios/crear/');		
						}else{
									$this->Session->setFlash('Han ocurrido errores durante el proceso.', 'flash_custom', array('class' => 'alert-warning'));
									$this->redirect('/usuarios/crear/');		
					}
					
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
					'title_for_mod' => 'Usuarios',
					'usuario_id' => $usuario_id,
					'subtitle_for_mod'  => 'Actualización de Datos de Usuarios',
					'clientes'   => $this->Usuario->Cliente->find('list', array('fields' => array('ncli', 'clave_nombre'),     'order'=>'nombre')),	
					'ncliLogedInn'=>$this->Auth->user('Cliente.0.ncli'),
					'notificacionesVars'=>$this->get_notificaciones($this->Auth->user('Cliente.0.ncli'))		
				);
				if($this->request->is("put")) {
					$this->request->data['Usuario']['id'] = $this->Convert->decode($this->request->data['Usuario']['id']);
					$this->request->data['ClientsUser']['id'] = $this->Convert->decode($this->request->data['ClientsUser']['id']);
					if($this->Usuario->saveAssociated($this->request->data))
					{
									$this->Session->setFlash('<b>Datos de Usuario</b> procesados con éxito.', 'flash_custom', array('class' => 'alert-success'));
									$this->redirect('/usuarios/listar/');		
						}else{
									$this->Session->setFlash('Han ocurrido errores durante el proceso.<br />NOTA: Verifiue que ese número de <b>Cédula de Identidad</b> y/o <b>Correo Eléctronico</b> no este registrado en la <b>Base de Datos</b> bajo los datos  de otro usuario.', 'flash_custom', array('class' => 'alert-warning'));
									$this->redirect('/usuarios/listar/');		
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
				$this->Session->setFlash('<em><b>ATENCION:</b> <em><b>Datos de Usuario</b></em> no encontrado.</em>', 'flash_custom', array('class' => 'alert-success'));
				$this->redirect('/usuarios/listar/');		
			}
		
		}else{
			$this->Session->setFlash('<em><b>ATENCION:</b> <em><b>Datos de Usuario</b></em> no recibido.</em>', 'flash_custom', array('class' => 'alert-warning'));
			$this->redirect('/usuarios/crear/');		
		}
	}
	
	public function listar()
	{	
		$arguments = array();
		$conditions_clients = $this->Auth->user('Cliente.0.ncli');
		$conditions = array(
			'Usuario.rol_id'=>1
		);
		$config_vars = array(
			'title_for_mod' => 'Usuarios',
			'subtitle_for_mod'  => 'Administración de Usuarios',
			'ncliLogedInn'=>$this->Auth->user('Cliente.0.ncli'),
			'notificacionesVars'=>$this->get_notificaciones($this->Auth->user('Cliente.0.ncli')),
			'clientes'   => $this->Usuario->Cliente->find('list', array('fields' => array('ncli', 'clave_nombre'),     'order'=>'nombre'))			
		);
		if($this->request->is("post")) {
			 $url     = array('action'=>'listar');
			 $filters = array();
			 if(isset($this->data['Usuario']['ncli']) && $this->data['Usuario']['ncli'])
					$filters['cliente'] = $this->Convert->encode($this->data['Usuario']['ncli']);
			
			 if(isset($this->data['Usuario']['nombre']) && $this->data['Usuario']['nombre'])
					$filters['nombre'] = $this->Convert->encode($this->data['Usuario']['nombre']);
			 if(isset($this->data['Usuario']['f_ini']) && $this->data['Usuario']['f_ini'])
					$filters['f_ini'] = $this->Convert->encode($this->data['Usuario']['f_ini']);
			 if(isset($this->data['Usuario']['f_fin']) && $this->data['Usuario']['f_fin'])
					$filters['f_fin'] = $this->Convert->encode($this->data['Usuario']['f_fin']);
			$this->redirect(array_merge($url,$filters)); 
		}
		if(isset($this->passedArgs['cliente'])){
			$arguments['cliente'] = $this->passedArgs['cliente'];
			$conditions_clients = $this->Convert->decode($this->passedArgs['cliente']);
			$config_vars['nCliSelected'] = $this->Convert->decode($this->passedArgs['cliente']);
			
		}
		if(isset($this->passedArgs['nombre'])){
			$arguments['nombre'] = $this->passedArgs['nombre'];
			$conditions['Usuario.nombre LIKE '] = '%'.$this->Convert->decode($this->passedArgs['nombre']).'%';
			$conditions['OR'] = $conditions['Usuario.apellido LIKE '] = '%'.$this->Convert->decode($this->passedArgs['nombre']).'%';;
		}
		if(isset($this->passedArgs['f_ini']) && isset($this->passedArgs['f_fin']))
		{
			$arguments['f_ini'] = $this->passedArgs['f_ini'];
			$arguments['f_fin'] = $this->passedArgs['f_fin'];
			$conditions['Usuario.f_consulta BETWEEN STR_TO_DATE(?, \'%d/%m/%Y 00:00\') AND STR_TO_DATE(?, \'%d/%m/%Y 00:00\')  '] = array($this->Convert->decode($this->passedArgs['f_ini']),$this->Convert->decode($this->passedArgs['f_fin']))  ;
		}
		/*if(isset($this->passedArgs['cedula']) || isset($this->passedArgs['correo']) || isset($this->passedArgs['nombre']) || isset($this->passedArgs['cedula']) || (isset($this->passedArgs['f_ini']) &&  isset($this->passedArgs['f_fin'])))
				$this->paginate['conditions'] = $conditions;*/
		$this->paginate['joins'][0]['conditions']['cliente_id'] = $conditions_clients;
		$this->Paginator->settings = $this->paginate;
		$config_vars['data'] = $this->Paginator->paginate();
		$config_vars['arguments'] = $arguments;
		$this->set($config_vars);
	}
	
	public function q_on($usuario_id)
	{
		if(!is_null($usuario_id))
		{
			if($this->Usuario->find('count', array('conditions'=>array('Usuario.id'=>$this->Convert->decode($usuario_id)))) > 0)
			{			
						$data =  $this->Usuario->find('first', array( 'recursive'=>-1, 'conditions' => array('Usuario.id' => $this->Convert->decode($usuario_id))));
						list($nacionalidad, $cedula) = explode('-', $data['Usuario']['cedula']);
						$data['Usuario']['nacionalidad_id'] = $nacionalidad;
						$data['Usuario']['cedula'] = $cedula;
						$data['Usuario']['status'] = ($data['Usuario']['status'])?false:true;
						if($this->Usuario->save($data)){
									$this->Session->setFlash('<b>Datos de Usuario</b> procesados satisfactoriamente.', 'flash_custom', array('class' => 'alert-success'));
									$this->redirect('/usuarios/listar');		
								}else{
									$this->Session->setFlash('Han ocurrido errores al intentar de procesar los datos.', 'flash_custom', array('class' => 'alert-warning'));
									$this->redirect('/usuarios/listar/');		
							}			
				
				
			}else{
				$this->Session->setFlash('<em><b>ATENCION:</b> <em><b>Datos de Usuario</b></em> no encontrado.</em>', 'flash_custom', array('class' => 'alert-success'));
				$this->redirect('/usuarios/listar/');		
			}
		
		}else{
			$this->Session->setFlash('<em><b>ATENCION:</b> <em><b>Datos de Usuario</b></em> no recibido.</em>', 'flash_custom', array('class' => 'alert-warning'));
			$this->redirect('/usuarios/crear/');		
		}

	}

	
	public function profile()
	{
		$config_vars = array(
			'title_for_mod' => 'Usuarios',
			'subtitle_for_mod'  => 'Actualización Datos de Perfil',
			'ncliLogedInn'=>$this->Auth->user('Cliente.0.ncli'),
			'notificacionesVars'=>$this->get_notificaciones($this->Auth->user('Cliente.0.ncli'))			
		);
		if($this->request->is("post")) {
			$this->request->data['Usuario']['id'] = $this->request->data['Usuario']['id'];
			if($this->Usuario->save($this->request->data))
					{
									$this->Session->setFlash('<b>Datos de Usuario</b> procesados con éxito.', 'flash_custom', array('class' => 'alert-success'));
									$this->redirect('/usuarios/profile/');		
						}else{
									$this->Session->setFlash('Han ocurrido errores durante el proceso.<br />NOTA: Verifiue que ese número de <b>Cédula de Identidad</b> y/o <b>Correo Eléctronico</b> no este registrado en la <b>Base de Datos</b> bajo los datos  de otro usuario.', 'flash_custom', array('class' => 'alert-warning'));
									$this->redirect('/usuarios/profile/');		
					}
		}
		$this->set($config_vars);
	}
	
	public function login()
	{
		App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

		$config_vars = array(
			'title_for_mod' => 'Acceso al Sistema',
			'subtitle_for_mod'  => 'Panel de Validación de Usuarios',
			'cookie'=>$this->Cookie->read('correo')

		);
			
		 if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				 if ($this->request->data['Usuario']['remain'] == 1) {
		                unset($this->request->data['Usuario']['remain']);
						$this->Cookie->write('correo', $this->request->data['Usuario']['correo'], true, '2 weeks');
            }
			        $this->redirect(array('controller'=>'productos','action'=>'inventario'));
        	}else
				$this->Session->setFlash(__('Correo o Contraseña inválidos, vuelve a intentar'), 'flash_custom', array('class' => 'alert-warning'));
			
    	}
		$this->set($config_vars);
	}

	public function logout() {
		return $this->redirect($this->Auth->logout());
	}	



	public function reporte_excel()
	{	
		$config = array(
				'fields'=>array(
					'id',
					'apellido',
					'nombre',
					'correo',
					'f_registro',
					'status'
				),
				'conditions'=>array(
					'Usuario.rol_id'=>1,
					
				),
				'joins'=> array(
							array('table' => 'clients_users',
							'alias' => 'ClientsUsers',
							'type' => 'INNER',
							'conditions' => array(
								'Usuario.id = ClientsUsers.user_id',
								
							)
						)
				)	,	
				'contain'=>array(
							'Cliente'=>array(
									'fields'=>array(
											'Cliente.ncli',
											'Cliente.nombre'
										)
							)
				)
		);
		if(isset($this->passedArgs['cliente'])){
			$config['joins'][0]['conditions']['cliente_id'] = $this->Convert->decode($this->passedArgs['cliente']);
		}		
		if(isset($this->passedArgs['cedula']))
			$config['conditions']['Usuario.cedula'] = $this->Convert->decode($this->passedArgs['cedula']);
		if(isset($this->passedArgs['correo']))
			$config['conditions']['Usuario.correo'] = $this->Convert->decode($this->passedArgs['correo']);
			
		if(isset($this->passedArgs['nombre'])){
			$config['conditions']['Usuario.nombre LIKE '] = '%'.$this->Convert->decode($this->passedArgs['nombre']).'%';
			$config['conditions']['OR'] = $conditions['Usuario.apellido LIKE '] = '%'.$this->Convert->decode($this->passedArgs['nombre']).'%';;
		}
		if(isset($this->passedArgs['f_ini']) && isset($this->passedArgs['f_fin']))
			$config['conditions']['Usuario.f_consulta BETWEEN STR_TO_DATE(?, \'%d/%m/%Y 00:00\') AND STR_TO_DATE(?, \'%d/%m/%Y 00:00\')  '] = array($this->Convert->decode($this->passedArgs['f_ini']),$this->Convert->decode($this->passedArgs['f_fin']))  ;
		
		
		$data = $this->Usuario->find('all',$config);		
		$this->set(compact('data'));

	}
		 
   public function recovery($mail = NULL)
	{
	
			$var_configs = array(	'title_for_mod' => 'Usuarios',
								'subtitle_for_mod'  => 'Recuperación de Datos',
													
							);
								
		 if ($this->request->is('post')) {
			
		
			$var_configs['data'] = $this->Usuario->find('first', array(
																	'fields'=>array('nombre', 'apellido', 'correo', 'id'),
																	'conditions'=>array('Usuario.correo'=>$this->data['Usuario']['correo']) ,
																	 'recursive'=>-1
															)
													);
			$var_configs['module'] = 'recovery';
			if($this->__send($var_configs['data'], $var_configs['module'])){
				$this->Session->setFlash('se le ha enviado un correo con los pasos a seguir para la recuperación de la clave.', 'flash_custom', array('class' => 'alert-success'));
				$this->redirect('/usuarios/login/');		
			}
		}
			$this->set($var_configs);
	}
	

	public function secure($correo, $usuario_id)
	{
		if(!is_null($usuario_id))
		{
			if( $this->Usuario->find('count', array('conditions'=>array('Usuario.id'=>$this->Convert->decode($usuario_id), 'Usuario.correo'=>$this->Convert->decode($correo)))) > 0)
			{	
					$config_vars = array(
						'title_for_mod' => 'Usuarios',
						'subtitle_for_mod'  => 'Recuperación de Datos',
						'correo' => $correo,
						'usuario_id' => $usuario_id
					);
				
			
					if($this->request->is('post')){
						$this->request->data['Usuario']['id']       =	$this->Convert->decode($this->request->data['Usuario']['id']);
						$this->request->data['Usuario']['nombre']   =	$this->Convert->decode($this->request->data['Usuario']['nb']);
						$this->request->data['Usuario']['apellido'] =	$this->Convert->decode($this->request->data['Usuario']['ap']);
						$this->request->data['Usuario']['correo']   =	$this->Convert->decode($this->request->data['Usuario']['ml']);

						if($this->Usuario->save($this->request->data)){
									$this->__send($this->Usuario->find('first', array('recursive'=>-1,'conditions'=>array('Usuario.id'=>$this->Convert->decode($usuario_id), 'Usuario.correo'=>$this->Convert->decode($correo)))),'secure');
									$this->Session->setFlash('<b>Clave</b> recuperada satisfactoriamente.', 'flash_custom', array('class' => 'alert-success'));
									$this->redirect('/usuarios/login/');		
								}else{
									$this->Session->setFlash('Han ocurrido errores al intentar de procesar los datos.', 'flash_custom', array('class' => 'alert-success'));
									$this->redirect('/usuarios/login/');		
							}			
				
					}
					
			           $config_vars['data'] = $this->Usuario->find('first', array(
																	'fields'=>array('id', 'nombre', 'apellido', 'correo'),
																	'conditions'=>array('Usuario.id'=>$this->Convert->decode($usuario_id), 'Usuario.correo'=>$this->Convert->decode($correo)) ,
																	 'recursive'=>-1
															)
													);			
					$this->set($config_vars);
			}else{
				$this->Session->setFlash('<b>ATENCION:</b> <b>Datos de Usuario</b> no encontrado.', 'flash_custom', array('class' => 'alert-success'));
				$this->redirect('/usuarios/login/');		
			}
		
		}else{
			$this->Session->setFlash('<b>ATENCION:</b> <b>Datos de Usuario</b> no recibido.', 'flash_custom', array('class' => 'alert-success'));
			$this->redirect('/usuarios/login/');		
		}
	}
			
	private function __send($data , $module ) { 
			 if($module=='registre')
			 	  $title ='Registro de Usuario';
				elseif($module=='recovery')
					$title ='Recuperación de Clave';
				 else
					  $title ='Cambio de Clave';
			 $this->set(array('data' => $data,'module' => $module)); 
			 $this->Email->from = 'pedido@unocd.com';
			 $this->Email->fromName = 'Administrador (UNOCD)';
			 $this->Email->to = $this->request->data['Usuario']['correo']; 
			 $this->Email->subject = $title.' -  Sistema de Administración de Inventario UNOCD'; 
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