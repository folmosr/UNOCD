<?php
class Clasprd1Cliente extends Model {
	
	
	public $useTable = 'clasprd1_clientes'; 

	public $hasMany = array(
        'Producto' => array(
            'className'  => 'Cliente',
			'foreignKey' => 'cliente_id'
        )
    );	
	
}
