<?php
class FDistribucion extends Model {
	
	public $useTable   = 'factor_distribucion'; 
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