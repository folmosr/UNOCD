<?php
class Clasprd3 extends Model {
	
	
	public $useTable = 'ClasPrd3'; 
	public $primaryKey = 'nclas';

	public $hasMany = array(
        'Producto' => array(
            'className'  => 'Producto',
			'foreignKey' => 'ClasPrd3'
        )
    );	
	
}
