<?php
class Destinatario extends Model {
	
	
	public $useTable = 'sclientesn'; 
	public $primaryKey = 'idrow';
	public $actsAs     = array('Containable');
	
	public $virtualFields = array(
    		'inicial' => 'LEFT(UPPER(Destinatario.nombre), 1)'
	);
	
	public $belongsTo = array(
        'Subzona' => array(
            'className'  => 'Subzona',
			'foreignKey' => 'nsuz'
        )
		
       );	
    
	public $hasMany = array(
        'Pedido' => array(
            'className'  => 'Pedido',
			'foreignKey' => 'id_destinatario'
        )
		
    );	
	
}