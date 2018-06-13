<?php
class Estatu extends Model {
	
	
	public $useTable = 'estatus'; 
	public $primaryKey = 'id';

	public $hasMany = array(
        'Pedido' => array(
            'className'  => 'Pedido',
			'foreignKey' => 'estado'
        )
    );	
	
}
