<?php
class Zona extends Model {
	
	
	public $useTable = 'zonas'; 
	public $primaryKey = 'nzon';

	public $hasMany = array(
        'Zona' => array(
            'className'  => 'Zona',
			'foreignKey' => 'nzon'
        )
    );	
	
}
