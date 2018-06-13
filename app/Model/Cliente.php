<?php
class Cliente extends Model {
	
	public $primaryKey = 'ncli';
	public $useTable   = 'Clientes'; 
	public $actsAs     = array('Containable');

	/*public $hasMany = array(
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'cliente'
        )
		
    );*/

	public $virtualFields = array(
  		'clave_nombre' => 'CONCAT(ncli,\' - \',nombre)'
		
    );
   	
	public $hasOne = array(
		'Denominacion'=>array(
			'className'=>'Denominacion',
            'foreignKey' => 'cliente_id'
		),
		'FAlmacenaje'=>array(
			'className'=>'FAlmacenaje',
            'foreignKey' => 'cliente_id'
		),
		'FDistribucion'=>array(
			'className'=>'FDistribucion',
            'foreignKey' => 'cliente_id'
		)
	);
		 
	public $belongsTo = array(
        'Clasprd1Cliente' => array(
            'className' => 'Clasprd1Cliente',
            'foreignKey' => 'cliente_id'
        ),
        'Clasprd2Cliente' => array(
            'className' => 'Clasprd2Cliente',
            'foreignKey' => 'cliente_id'
        ),
        'Clasprd3Cliente' => array(
            'className' => 'Clasprd3Cliente',
            'foreignKey' => 'cliente_id'
        ),
        'Clasprd4Cliente' => array(
            'className' => 'Clasprd4Cliente',
            'foreignKey' => 'cliente_id'
        ),
        'Clasprd5Cliente' => array(
            'className' => 'Clasprd5Cliente',
            'foreignKey' => 'cliente_id'
        )

    );
	
}