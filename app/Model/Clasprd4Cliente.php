<?php
class Clasprd4Cliente extends Model {
	
	
	public $useTable = 'clasprd4_clientes'; 

	public $hasMany = array(
        'Producto' => array(
            'className'  => 'Cliente',
			'foreignKey' => 'cliente_id'
        )
    );	
	
}
