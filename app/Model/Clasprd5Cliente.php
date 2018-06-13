<?php
class Clasprd5Cliente extends Model {
	
	
	public $useTable = 'clasprd5_clientes'; 

	public $hasMany = array(
        'Producto' => array(
            'className'  => 'Cliente',
			'foreignKey' => 'cliente_id'
        )
    );	
	
}
