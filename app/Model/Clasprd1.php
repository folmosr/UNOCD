<?php
class Clasprd1 extends Model {
	
	
	public $useTable = 'ClasPrd1'; 
	public $primaryKey = 'nclas';

	public $hasMany = array(
        'Producto' => array(
            'className'  => 'Producto',
			'foreignKey' => 'ClasPrd1'
        )
    );	
	
}
