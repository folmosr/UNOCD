<?php
class Clasprd4 extends Model {
	
	
	public $useTable = 'ClasPrd4'; 
	public $primaryKey = 'nclas';

	public $hasMany = array(
        'Producto' => array(
            'className'  => 'Producto',
			'foreignKey' => 'ClasPrd4'
        )
    );	
	
}
