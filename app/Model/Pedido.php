<?php
class Pedido extends Model {
	
	public $primaryKey = 'id_pedido';
	public $actsAs = array('Containable');


	public function beforeFind($results, $primary = false) {
	  		$this->query('SET SQL_BIG_SELECTS=1'); 
	}
		
	public $virtualFields = array(
    		'f_solicitud' => 'DATE_FORMAT(fecha_solicitud, \'%d/%m/%Y %H:%i\')',
			'fecha_proceso' => 'DATE_FORMAT(fecha_proceso, \'%d/%m/%Y %H:%i\')'
	);
	
	public $hasOne = array(
        'Clifactura'=> array(
					'className' => 'Clifactura',
					'foreignKey' => 'documento'
        )
	);
	
    public $hasMany = array(
        'PedidosProducto'=> array(
					'className' => 'PedidosProducto',
					'foreignKey' => 'pedido_id'
        ),
        'Webtracking'=> array(
					'className' => 'Webtracking',
					'foreignKey' => 'pedido_id'
        ),
		
    );	
	
	public $belongsTo = array(
        'Solicitante' => array(
            'className' => 'Usuario',
            'foreignKey' => 'id_usuario_solicitante'
        ),
        'Subzona' => array(
            'className' => 'Subzona',
            'foreignKey' => 'id_subzona'
        ),
        'Aprobador' => array(
            'className' => 'Usuario',
            'foreignKey' => 'id_usuario_aprobador'
        ),
        'Destinatario' => array(
            'className' => 'Destinatario',
            'foreignKey' => 'id_destinatario'
        ),
        'Estatu' => array(
            'className' => 'Estatu',
            'foreignKey' => 'estado'
        ),
    );
	
}