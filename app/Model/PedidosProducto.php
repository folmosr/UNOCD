<?php
class PedidosProducto extends Model {
	
	public $actsAs = array('Containable');
						
    public $belongsTo = array(
		'Producto', 
		'Pedido',
		'PConsolidado'=>array(
					'className' => 'PConsolidado',
					'foreignKey' => 'producto_id',
		)

    );	
	public function beforeFind($results, $primary = false) {
	  		$this->query('SET SQL_BIG_SELECTS=1'); 
	}
}
