<?php
class Producto extends Model {
	
	public $primaryKey = 'codi';
	public $useTable   = 'productos'; 
	public $actsAs = array('Containable');

	
	/*public $hasOne =  array(
		'Complemento'=>array(
				'className' => 'ViewInventario',
				'foreignKey' => 'codi'
			)	
	);*/
	
	
    public $hasMany = array(
        'PedidosProducto'=> array(
					'className' => 'PedidosProducto',
					'foreignKey' => 'producto_id'
        )
    );
	
	
	public $belongsTo = array(
        'Clasprd1' => array(
            'className' => 'Clasprd1',
            'foreignKey' => 'Clasprd1'
        ),
        'Clasprd4' => array(
            'className' => 'Clasprd4',
            'foreignKey' => 'Clasprd4'
        ),
        'Clasprd2' => array(
            'className' => 'Clasprd2',
            'foreignKey' => 'Clasprd2'
        ),
        'Clasprd5' => array(
            'className' => 'Clasprd5',
            'foreignKey' => 'Clasprd5'
        ),
        'Clasprd3' => array(
            'className' => 'Clasprd3',
            'foreignKey' => 'Clasprd3'
        ),
        'Existencia' => array(
            'className' => 'Existencia',
			'foreignKey' => 'codi'
        )
				
				
				
    );
		
}
