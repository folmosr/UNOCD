<?php
class Estadoprd extends Model {
	
	
	public $useTable = 'Estadoprd'; 
	public $primaryKey = 'NESTPRD';

	public $hasMany = array(
        'Existencia' => array(
            'className'  => 'Existencia',
			'foreignKey' => 'nestprd'
        )
    );	
	
}
