<?php
class Clasprd3Cliente extends Model {
	
	
	public $useTable = 'clasprd3_clientes'; 

	public $hasMany = array(
        'Producto' => array(
            'className'  => 'Cliente',
			'foreignKey' => 'cliente_id'
        )
    );	
	
}
