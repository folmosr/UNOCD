<?php
class FAlmacenaje extends Model {
	
	public $useTable   = 'factor_almacenaje'; 
	public $actsAs     = array('Containable');

	public $belongsTo = array(
		'Cliente'=>array(
			'className'=>'Cliente',
            'foreignKey' => 'cliente_id'
		),
		'TiposFactor'=>array(
			'className'=>'TiposFactor',
            'foreignKey' => 'tipo_factor_id'
		)
	);
		 

	
}