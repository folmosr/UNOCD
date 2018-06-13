<?php
class Clasprd2Cliente extends Model {
	
	
	public $useTable = 'clasprd2_clientes'; 

	public $hasMany = array(
        'Producto' => array(
            'className'  => 'Cliente',
			'foreignKey' => 'cliente_id'
        )
    );	
	
}
