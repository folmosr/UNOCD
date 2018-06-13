<?php
class NotificacionesController extends AppController {

	public function beforeFilter()
	{
		parent::beforeFilter();
		if (!$this->Auth->loggedIn()) {
				$this->Session->setFlash('Para acceder a este módulo primero debe validarse como <b>Usuario</b> del sistema.', 'flash_custom', array('class' => 'alert-warning'));
				$this->Auth->authError = false;
		}
			
	}

 	public function isAuthorized($usuario) {
		if (($this->Auth->user('rol_id')==1)  || ($this->Auth->user('rol_id')==3)){
            return true;
        }	
	}	
	
	public function listar()
	{
		//CODIGO PARA ACTUALIZAR
		$config_vars = array(
			'title_for_mod' 	=> 'Tus Notificaciones',
			'subtitle_for_mod'  => 'Acciones sobre pedidos',
			'cliente' =>isset($this->passedArgs['cliente']),
			'notificacionesVars'=>(!($this->Convert->decode($this->passedArgs['cliente'])))?$this->get_notificaciones($this->Convert->decode($this->passedArgs['cliente'])):false,
			'data' =>(isset($this->passedArgs['cliente']))?$this->__getMisNotificaciones($this->Convert->decode($this->passedArgs['cliente'])):false
		);
		
		$this->set($config_vars);
	}
	
	
	private function __getMisNotificaciones($cliente_id)
	{
	   if(($this->Auth->user('rol_id') == 1) && ($this->Auth->user('aprueba_pedidos'))  && (!$this->Auth->user('realiza_pedidos')))
		 $conditions =  array(
				'Pedido.cliente_id'=>$this->Auth->user('Cliente.0.ncli'),
				'Notificacion.actor '=>1
 		);
	   if(($this->Auth->user('rol_id') == 1) && ($this->Auth->user('aprueba_pedidos')) && ($this->Auth->user('realiza_pedidos')))
		 $conditions =  array(
				'Pedido.cliente_id'=>$this->Auth->user('Cliente.0.ncli'),
				'OR'=>array(
						'Pedido.id_usuario_solicitante'=>$this->Auth->user('id')
				),
				'Notificacion.actor IN'=>array(0,1)
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
				'Notificacion.actor'=>2
 		);
	
		if(isset($conditions)){
			$data = $this->Notificacion->find('all', array('contain'=>array(
								'Usuario'=>array(
												'fields'=>array(
													'Usuario.full_name'
												)	
											),
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
						'recursive'=>-1
				)
		);	
		return ($data);
		}else
			return false;
	}
	
	public function notify()
	{
		if(isset($this->passedArgs['cliente']))
		{
		  $r =  $this->update_notificaciones($this->passedArgs['cliente']);
		}else
			$r = 0;
		echo 	    $xml = '<?xml version="1.0" encoding="UTF-8"?>
							<Notificaciones>
								<respuesta>'.$r.'</respuesta>
							</Notificaciones>
					';
		$this->autoRender = false;
	}
}
?>