<?php
class Existencia extends Model {
	
	
	public $useTable = 'Existencia'; 

	public $belongsTo = array(
        'Estadoprd' => array(
            'className' => 'Estadoprd',
            'foreignKey' => 'nestprd'
        ),
		'PConsolidado' => array(
            'className' => 'PConsolidado',
			'foreignKey' => 'codi'
        )
		
    );

}
