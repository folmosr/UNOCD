<?php
class TiposFactor extends Model {
	
	public $useTable   = 'tipos_factores'; 
	public $actsAs     = array('Containable');

	public $hasMany = array(
		'FAlmacenaje'=>array(
			'className'=>'FAlmacenaje',
            'foreignKey' => 'tipo_factor_id'
		),
		'FDistribucion'=>array(
			'className'=>'FDistribucion',
            'foreignKey' => 'tipo_factor_id'
		)
		
	);
		 

	
}