<?php
class Clasprd5 extends Model {
	
	
	public $useTable = 'ClasPrd5'; 
	public $primaryKey = 'nclas';

	public $hasMany = array(
        'Producto' => array(
            'className'  => 'Producto',
			'foreignKey' => 'ClasPrd5'
        )
    );	
	
}
