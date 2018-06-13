<?php
class Clasprd2 extends Model {
	
	
	public $useTable = 'ClasPrd2'; 
	public $primaryKey = 'nclas';

	public $hasMany = array(
        'Producto' => array(
            'className'  => 'Producto',
			'foreignKey' => 'ClasPrd2'
        )
    );	
	
}
