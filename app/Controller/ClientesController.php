<?php
class ClientesController extends AppController {


	public function beforeFilter()
	{
		parent::beforeFilter();
		if (!$this->Auth->loggedIn()) {
				$this->Session->setFlash('Para acceder a este módulo primero debe validarse como <b>Usuario</b> del sistema.', 'flash_custom', array('class' => 'alert-warning'));
				$this->Auth->authError = false;
		}
			
	}
	
 	public function isAuthorized($usuario) {
		if (($this->Auth->user('rol_id')==2) || ($this->Auth->user('rol_id')==3)){
            return true;
        }	
	}		

	public function crear()
	{
		$conditions = array();
		if($this->Auth->user('rol_id') == 3){
			 
			$lista =  $this->Cliente->find('list', 
				array(
								'fields' => array('ncli', 'clave_nombre'),     
								'order'=>'nombre',
								'conditions'=>array('Cliente.ncli'=>$this->__getClientList())
					)
				);			
		}else{
			$lista =  $this->Cliente->find('list', 
				array(
								'fields' => array('ncli', 'clave_nombre'),     
								'order'=>'nombre'
					)
				);			
		}
		$config_vars = array(
				'clientes'   => $lista,			
				'tipos_factores'=>$this->Cliente->FAlmacenaje->TiposFactor->find('list', array('fields' => array('id', 'descripcion'), 'order'=>'descripcion')),
				'title_for_mod' => 'Clientes',
				'subtitle_for_mod'  => 'Creación de Clientes',
				'ncliLogedInn'=>$this->Auth->user('Cliente.0.ncli'),
				'notificacionesVars'=>$this->get_notificaciones($this->Auth->user('Cliente.0.ncli'))
		);
		
		if($this->request->is("post") || $this->request->is("put")) {
			if($this->request->is("put"))
			{
				$this->request->data['Denominacion']['id'] = $this->Convert->decode($this->request->data['Denominacion']['id']);
				$this->request->data['FAlmacenaje']['id'] = $this->Convert->decode($this->request->data['FAlmacenaje']['id']);
				$this->request->data['FDistribucion']['id'] = $this->Convert->decode($this->request->data['FDistribucion']['id']);
			}
			if($this->Cliente->saveAssociated($this->request->data))
			{
							$this->Session->setFlash('<b>Datos de Cliente</b> procesados con éxito.', 'flash_custom', array('class' => 'alert-success'));
							$this->redirect('/clientes/crear/');		
				}else{
							$this->Session->setFlash('Han ocurrido errores durante el proceso.', 'flash_custom', array('class' => 'alert-danger'));
							$this->redirect('/clientes/crear/');		
			}
		}else{
				if(isset($this->passedArgs['cliente']))
					$this->data = $this->Cliente->find('first', array(
														'conditions'=>array('Cliente.ncli'=>$this->Convert->decode($this->passedArgs['cliente'])),
														'contain'=>array(
																'Denominacion',
																'FAlmacenaje',
																'FDistribucion'
															)
														)
										);
		}
		$this->set($config_vars);
	}
	
	
	private function __getClientList()
	{
		$tmp = array();
		$clientes =  $this->Auth->user('Cliente');
		if( count($clientes) > 1)
		{
			for($i = 0; $i < count($clientes); $i++){
				$tmp[] = $clientes[$i]['ncli'];
			}
		}else
			$tmp = $clientes[0]['ncli'];
		return $tmp;
	}
}
?>