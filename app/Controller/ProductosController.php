<?php
class ProductosController extends AppController {
	
   public $components = array('Paginator');
   public $uses 	  = array('PConsolidado', 'Producto', 'ArchivoCsv','Cliente');
   public $paginate = array(
	'fields'=>array(
						'DISTINCT PConsolidado.id',
						'PConsolidado.codi',
						'PConsolidado.fCreacion',
						'PConsolidado.nombre',
						'PConsolidado.PESO',
						'PConsolidado.VOLUMEN',
						'PConsolidado.Ancho',
						'PConsolidado.Largo',
						'PConsolidado.Profundidad',
						'PConsolidado.DISPLAYSporCAJA',
						'PConsolidado.UNIDADESporDISPLAY',
						'PConsolidado.costoPrd',
						'PConsolidado.ClasPrd1_descripcion',
						'PConsolidado.ClasPrd2_descripcion',
						'PConsolidado.ClasPrd3_descripcion',
						'PConsolidado.ClasPrd4_descripcion',
						'PConsolidado.ClasPrd5_descripcion',
						'PConsolidado.presentacion',
						'PConsolidado.rotacion',
						'Existencia.*',
						//'PedidosProducto.*',
						//'(SELECT Pedido.estado FROM pedidos AS Pedido WHERE Pedido.id_pedido= PedidosProducto.pedido_id) AS \'Pedido.estado\''
						
						
		),		
		'limit' => 100,
        'order' => array(
            'PConsolidado.codi' => 'asc'
        ),
		'joins' => array(
				array(
					'table' => 'Existencia',
					'alias' => 'Existencia',
					'type' => 'INNER',
					'conditions' => array(
						'Existencia.codi = PConsolidado.codi',
						'Existencia.unidades > 0'
					)
				)/*,
				array(
					'table' => 'pedidos_productos',
					'alias' => 'PedidosProducto',
					'type' => 'LEFT',
					'conditions' => array(
						'PedidosProducto.producto_id    = PConsolidado.codi',
						'PedidosProducto.nestprd = Existencia.nestprd'
					)
				)*/	
			),
		'contain'=>array(
			'PedidosProducto'=>array(
				'Pedido'
			),
			
		),	
		'recursive'=>-1			
    );

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
			
	public function inventario()
	{
		$config_vars = array(
				'title_for_mod' => 'Inventario',
				'subtitle_for_mod'  => 'Consulta del Inventario/ Solicitud de Pedidos',
				//'zonas'=>$this->Producto->PedidosProducto->Pedido->Subzona->Zona->find('list', array('fields' => array('Zona.nombre'), 'order'=>'Zona.nombre')),
				'estatus'=>$this->Producto->Existencia->Estadoprd->find('list', array('fields' => array('Estadoprd.estado'), 'order'=>'Estadoprd.estado')),
				'actualizacion'=>$this->ArchivoCsv->find('first', array('fields'=>array('fecha'), 'conditions'=>array('id=2'), 'order'=>'fecha DESC')),
				'nCliSelected'=>NULL
		);
		 
		if($this->request->is("post")) {
			 $url     = array('action'=>'inventario');
			 $filters = array();
			 if(isset($this->data['Producto']['ClasPrd1']) && $this->data['Producto']['ClasPrd1'])
					$filters['clasprd1'] = $this->Convert->encode($this->data['Producto']['ClasPrd1']);
			 if(isset($this->data['Producto']['ClasPrd4']) && $this->data['Producto']['ClasPrd4'])
					$filters['clasprd4'] = $this->Convert->encode($this->data['Producto']['ClasPrd4']);
					
			 if(isset($this->data['Producto']['ClasPrd2']) && $this->data['Producto']['ClasPrd2'])
					$filters['clasprd2'] = $this->Convert->encode($this->data['Producto']['ClasPrd2']);
			 if(isset($this->data['Producto']['ClasPrd5']) && $this->data['Producto']['ClasPrd5'])
					$filters['clasprd5'] = $this->Convert->encode($this->data['Producto']['ClasPrd5']);
			 if(isset($this->data['Producto']['ClasPrd3']) && $this->data['Producto']['ClasPrd3'])
					$filters['clasprd3'] = $this->Convert->encode($this->data['Producto']['ClasPrd3']);
			
			 if(isset($this->data['Producto']['nombre']) && $this->data['Producto']['nombre'])
					$filters['nombre'] = $this->Convert->encode($this->data['Producto']['nombre']);
					
			 if(isset($this->data['Producto']['codi']) && $this->data['Producto']['codi'])
					$filters['codi'] = $this->Convert->encode($this->data['Producto']['codi']);
					
					
			 if(isset($this->data['Producto']['rango']) && $this->data['Producto']['rango'])
					$filters['rango'] = $this->Convert->encode($this->data['Producto']['rango']);

			 if(isset($this->data['Producto']['ncli']) && $this->data['Producto']['ncli'])
					$filters['cliente'] = $this->Convert->encode($this->data['Producto']['ncli']);

			 if(isset($this->data['Producto']['nestprd']) && $this->data['Producto']['nestprd'])
					$filters['estatus'] = $this->Convert->encode($this->data['Producto']['nestprd']);
			
			$this->redirect(array_merge($url,$filters)); 
		}

		if(isset($this->passedArgs['clasprd1'])){
			$arguments['clasprd1'] = $this->passedArgs['clasprd1'];
			$conditions_config['PConsolidado.ClasPrd1'] = $this->Convert->decode($this->passedArgs['clasprd1']);
		}

		if(isset($this->passedArgs['clasprd2'])){
			$arguments['clasprd2'] = $this->passedArgs['clasprd2'];
			$conditions_config['PConsolidado.ClasPrd2'] = $this->Convert->decode($this->passedArgs['clasprd2']);
		}
		if(isset($this->passedArgs['clasprd5'])){
			$arguments['clasprd5'] = $this->passedArgs['clasprd5'];
			$conditions_config['PConsolidado.ClasPrd5'] = $this->Convert->decode($this->passedArgs['clasprd5']);
		}
		if(isset($this->passedArgs['clasprd4']))
		{
			$arguments['clasprd4'] = $this->passedArgs['clasprd4'];
			$conditions_config['PConsolidado.ClasPrd4'] = $this->Convert->decode($this->passedArgs['clasprd4']);
		}

		if(isset($this->passedArgs['clasprd3']))
		{
			$arguments['clasprd3'] = $this->passedArgs['clasprd3'];
			$conditions_config['PConsolidado.ClasPrd3'] = $this->Convert->decode($this->passedArgs['clasprd3']);
		}
		if(isset($this->passedArgs['nombre']))
		{
			$arguments['nombre'] = $this->passedArgs['nombre'];
			$conditions_config['PConsolidado.nombre LIKE '] = '%'.$this->Convert->decode($this->passedArgs['nombre']).'%';
		}
		if(isset($this->passedArgs['codi']))
		{
			$arguments['codi'] = $this->passedArgs['codi'];
			$conditions_config['PConsolidado.codi'] = $this->Convert->decode($this->passedArgs['codi']);
		}
		if(isset($this->passedArgs['estatus'])){
			$arguments['estatus'] = $this->passedArgs['estatus'];
			$conditions_config_existencia['Existencia.nestprd'] = $this->Convert->decode($this->passedArgs['estatus']);
		}else{
			if(!$this->Auth->user('aprueba_pedidos'))
		 		$conditions_config_existencia['Existencia.nestprd'] = 1;
				else
					$conditions_config_existencia['Existencia.nestprd'] =array( 1,2,3,4);
		}
		if(isset($this->passedArgs['cliente'])){
			$arguments['cliente'] = $this->passedArgs['cliente'];
			$config_vars['nCliSelected'] = $this->Convert->decode($this->passedArgs['cliente']);
			$conditions_config['PConsolidado.ncli'] = $this->Convert->decode($this->passedArgs['cliente']);
		}else{
			$arguments['cliente'] = $this->Convert->encode($this->Auth->user('Cliente.0.ncli'));
			$conditions_config['PConsolidado.ncli'] = $this->Auth->user('Cliente.0.ncli');
		}
		if(isset($this->passedArgs['rango'])){
			$arguments['rango'] = $this->passedArgs['rango'];
			$rango = $this->Convert->decode($arguments['rango']);
			if($rango != 1826)
				$conditions_config['PConsolidado.rotacion BETWEEN ? AND ?'] = array (0, $rango) ;
				else
					$conditions_config['PConsolidado.rotacion BETWEEN ? AND ?'] = array (365, $rango) ;
		}	
		if(count($this->Auth->user('Cliente')) > 0){ 
			if(isset($this->passedArgs['cliente'])) 
					$config_vars['ncliLogedInn'] = $this->Convert->decode($this->passedArgs['cliente']); 
			elseif(isset($this->data['Producto']['ncli']))
					$config_vars['ncliLogedInn'] = $this->data['Producto']['ncli']; 	
				else 
					$config_vars['ncliLogedInn'] = $this->Auth->user('Cliente.0.ncli');  
		} else { 
			$config_vars['ncliLogedInn'] = $this->Auth->user('Cliente.0.ncli');  
		}
		$config_vars['indexUser'] = $this->getIndexUser($config_vars['ncliLogedInn']);
		 if($this->Auth->user('Cliente.'.$config_vars['indexUser'] .'.Denominacion.activo_clasprd1'))
		 	$config_vars['clasprd1'] = $this->Cliente->Clasprd1Cliente->find('list', array('fields' => array('clasprd_id', 'descripcion'), 'conditions'=>array('Clasprd1Cliente.cliente_id'=>$this->Auth->user('Cliente.'.$config_vars['indexUser'] .'.ncli')),    'order'=>'descripcion'));
			
		 if($this->Auth->user('Cliente.'.$config_vars['indexUser'] .'.Denominacion.activo_clasprd2'))
		 	$config_vars['clasprd2'] = $this->Cliente->Clasprd2Cliente->find('list', array('fields' => array('clasprd_id', 'descripcion'), 'conditions'=>array('Clasprd2Cliente.cliente_id'=>$this->Auth->user('Cliente.'.$config_vars['indexUser'] .'.ncli')),    'order'=>'descripcion'));
			
		 if($this->Auth->user('Cliente.'.$config_vars['indexUser'] .'.Denominacion.activo_clasprd3'))
		 	$config_vars['clasprd3'] = $this->Cliente->Clasprd3Cliente->find('list', array('fields' => array('clasprd_id', 'descripcion'), 'conditions'=>array('Clasprd3Cliente.cliente_id'=>$this->Auth->user('Cliente.'.$config_vars['indexUser'] .'.ncli')),    'order'=>'descripcion'));
		
		 if($this->Auth->user('Cliente.'.$config_vars['indexUser'] .'.Denominacion.activo_clasprd4'))
		 	$config_vars['clasprd4'] = $this->Cliente->Clasprd4Cliente->find('list', array('fields' => array('clasprd_id', 'descripcion'), 'conditions'=>array('Clasprd4Cliente.cliente_id'=>$this->Auth->user('Cliente.'.$config_vars['indexUser'] .'.ncli')),    'order'=>'descripcion'));
		 
		 if($this->Auth->user('Cliente.'.$config_vars['indexUser'] .'.Denominacion.activo_clasprd5'))
		 	$config_vars['clasprd5'] = $this->Cliente->Clasprd5Cliente->find('list', array('fields' => array('clasprd_id', 'descripcion'), 'conditions'=>array('Clasprd5Cliente.cliente_id'=>$this->Auth->user('Cliente.'.$config_vars['indexUser'] .'.ncli')),    'order'=>'descripcion'));
		
		$this->paginate['conditions'] = $conditions_config;
		$this->paginate['joins'][0]['conditions'][2] = $conditions_config_existencia;
		$this->Paginator->settings = $this->paginate;
		$config_vars['data'] = $this->Paginator->paginate();
		$config_vars['arguments'] = $arguments;
		$config_vars['iniciales_destinatarios'] = $this->__toArray($this->Producto->PedidosProducto->Pedido->Destinatario->find('all', array(
									'fields'=>array(
										'inicial'
									),	
		                           'conditions'=>array(
								               'ncli'=>$config_vars['ncliLogedInn'],
											   'LEFT(UPPER(Destinatario.NOMBRE), 1) REGEXP \'^[A-Z1-9]$\''
								   ),
								   'order'=>'inicial'
							)
		));
		$config_vars['notificacionesVars'] = $this->get_notificaciones($config_vars['ncliLogedInn']);		
		$this->set($config_vars);
	}
	
	
	public function reporte_excel()
	{	
		if(!$this->Auth->user('aprueba_pedidos'))
			$conditions_config_existencia['Existencia.nestprd'] = 1;	
			else
			 $conditions_config_existencia = array();
		if(isset($this->passedArgs['clasprd1']))
			$conditions_config['PConsolidado.ClasPrd1'] = $this->Convert->decode($this->passedArgs['clasprd1']);
		if(isset($this->passedArgs['clasprd2']))
			$conditions_config['PConsolidado.ClasPrd2'] = $this->Convert->decode($this->passedArgs['clasprd2']);
		if(isset($this->passedArgs['clasprd5']))
			$conditions_config['PConsolidado.ClasPrd5'] = $this->Convert->decode($this->passedArgs['clasprd5']);
		if(isset($this->passedArgs['clasprd4']))
			$conditions_config['PConsolidado.ClasPrd4'] = $this->Convert->decode($this->passedArgs['clasprd4']);
		if(isset($this->passedArgs['clasprd3']))
			$conditions_config['PConsolidado.ClasPrd3'] = $this->Convert->decode($this->passedArgs['clasprd3']);
		if(isset($this->passedArgs['nombre']))
			$conditions_config['PConsolidado.nombre LIKE '] = '%'.$this->Convert->decode($this->passedArgs['nombre']).'%';
		if(isset($this->passedArgs['codi']))
			$conditions_config['PConsolidado.codi'] = $this->Convert->decode($this->passedArgs['codi']);
		if(isset($this->passedArgs['cliente']))
			$conditions_config['PConsolidado.ncli'] = $this->Convert->decode($this->passedArgs['cliente']);
			else
				$conditions_config['PConsolidado.ncli'] = $this->Auth->user('Cliente.0.ncli');
		if(isset($this->passedArgs['rango'])){
			$rango = $this->Convert->decode($this->passedArgs['rango']);
			if($rango != 1826)
				$conditions_config['PConsolidado.rotacion BETWEEN ? AND ?'] = array (0, $rango) ;
				else
					$conditions_config['PConsolidado.rotacion BETWEEN ? AND ?'] = array (365, $rango) ;
		}
		if(isset($this->passedArgs['estatus']))
			$conditions_config_existencia['Existencia.nestprd'] = $this->Convert->decode($this->passedArgs['estatus']);	
		
	$config = array(
					'fields'=>array(
						'DISTINCT codi',
						'fCreacion',
						'nombre',
						'PESO',
						'VOLUMEN',
						'Ancho',
						'Largo',
						'Profundidad',
						'DISPLAYSporCAJA',
						'UNIDADESporDISPLAY',
						'costoPrd',
						'ClasPrd1_descripcion',
						'ClasPrd2_descripcion',
						'ClasPrd3_descripcion',
						'ClasPrd4_descripcion',
						'ClasPrd5_descripcion',
						'presentacion',
						'rotacion'
						
					),
					'conditions'=>array(
						$conditions_config
					),
				'joins' => array(
						array(
							'table' => 'Existencia',
							'alias' => 'Existencia',
							'type' => 'INNER',
							'conditions' => array(
								'Existencia.codi = PConsolidado.codi',
								$conditions_config_existencia
							)
						)
					),	
					'contain'=>array(
						'Existencia'
					),
					'recursive'=>-1	
		);

		$data = $this->PConsolidado->find('all',$config);		
		$this->set(compact('data'));

	}
	
	public function reporte_pdf()
	{
		$this->layout = 'pdf';
		if(!$this->Auth->user('aprueba_pedidos'))
			$conditions_config_existencia['Existencia.nestprd'] = 1;	
		else
			 $conditions_config_existencia = array();
		if(isset($this->passedArgs['clasprd1']))
			$conditions_config['PConsolidado.ClasPrd1'] = $this->Convert->decode($this->passedArgs['clasprd1']);
		if(isset($this->passedArgs['clasprd2']))
			$conditions_config['PConsolidado.ClasPrd2'] = $this->Convert->decode($this->passedArgs['clasprd2']);
		if(isset($this->passedArgs['clasprd5']))
			$conditions_config['PConsolidado.ClasPrd5'] = $this->Convert->decode($this->passedArgs['clasprd5']);
		if(isset($this->passedArgs['clasprd4']))
			$conditions_config['PConsolidado.ClasPrd4'] = $this->Convert->decode($this->passedArgs['clasprd4']);
		if(isset($this->passedArgs['clasprd3']))
			$conditions_config['PConsolidado.ClasPrd3'] = $this->Convert->decode($this->passedArgs['clasprd3']);
		if(isset($this->passedArgs['nombre']))
			$conditions_config['PConsolidado.nombre LIKE '] = '%'.$this->Convert->decode($this->passedArgs['nombre']).'%';
		if(isset($this->passedArgs['codi']))
			$conditions_config['PConsolidado.codi'] = $this->Convert->decode($this->passedArgs['codi']);
		if(isset($this->passedArgs['cliente']))
			$conditions_config['PConsolidado.ncli'] = $this->Convert->decode($this->passedArgs['cliente']);
			else
				$conditions_config['PConsolidado.ncli'] =  $this->Auth->user('Cliente.0.ncli');
		if(isset($this->passedArgs['rango'])){
			$rango = $this->Convert->decode($this->passedArgs['rango']);
			if($rango != 1826)
				$conditions_config['PConsolidado.rotacion BETWEEN ? AND ?'] = array (0, $rango) ;
				else
					$conditions_config['PConsolidado.rotacion BETWEEN ? AND ?'] = array (365, $rango) ;
		}	
		if(isset($this->passedArgs['estatus']))
			$conditions_config_existencia['Existencia.nestprd'] = $this->Convert->decode($this->passedArgs['estatus']);	
	$config = array(
					'fields'=>array(
						'DISTINCT codi',
						'nombre',
					),
					'conditions'=>array(
						$conditions_config
					),
				'joins' => array(
						array(
							'table' => 'Existencia',
							'alias' => 'Existencia',
							'type' => 'INNER',
							'conditions' => array(
								'Existencia.codi = PConsolidado.codi',
								$conditions_config_existencia
							)
						)
					),
			  'contain'=>array(
			  		'Existencia'
			  ),
			  'recursive'=>-1
		);
		$data = $this->PConsolidado->find('all',$config);		
		$this->set(compact('data'));
	}
	
	
	private function __toArray($data)
	{
		$list =  array('99'=>'#');
		for($i = 0 ; $i < count($data); $i++):
			$list[$data[$i]['Destinatario']['inicial']] = $data[$i]['Destinatario']['inicial'];
		endfor;
		return $list;
		}
	
}
?>