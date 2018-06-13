<?php
class Subzona extends Model {
	
	
	public $useTable = 'subzonas'; 
	public $primaryKey = 'nsuz';

	public $hasMany = array(
        'Pedido' => array(
            'className'  => 'Pedido',
			'foreignKey' => 'id_subzona'
        ),
         'Destinatario' => array(
            'className'  => 'Destinatario',
			'foreignKey' => 'nsuz'
        )
    );	
	
	public $belongsTo = array(
        'Zona' => array(
            'className' => 'Zona',
            'foreignKey' => 'nzon'
        )
	);	
}