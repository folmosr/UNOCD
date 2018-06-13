<?php
class PConsolidado extends Model {
	
	
	public $useTable = 'p_consolidados'; 
	public $primaryKey = 'codi';

	public $actsAs = array('Containable');
	public $virtualFields = array(
    		'fCreacion' => 'DATE_FORMAT(fCreacion, \'%d/%m/%Y %H:%i\')',
    		'UFECHAE' => 'DATE_FORMAT(UFECHAE, \'%d/%m/%Y %H:%i\')',
	);
	
	
	public $hasMany = array(
        'PedidosProducto'=> array(
					'className' => 'PedidosProducto',
					'foreignKey' => 'producto_id',
        ),
        'Existencia' => array(
            'className' => 'Existencia',
			'foreignKey' => 'codi'
        )
    );

	function paginateCount($conditions = null, $recursive = '-1', $extra = array()) {
		$parameters = compact('conditions');
		unset($extra['contain']);
		$fields	= array('fields'=>array(
						'DISTINCT PConsolidado.id',
						'PConsolidado.codi',
						'PConsolidado.fCreacion',
						'PConsolidado.nombre',
						'PConsolidado.PESO',
						'PConsolidado.VOLUMEN',
						'PConsolidado.Ancho',
						'PConsolidado.Largo',
						'PConsolidado.Profundidad',
						'PConsolidado.DISPLAYSporCAJA',
						'PConsolidado.UNIDADESporDISPLAY',
						'PConsolidado.costoPrd',
						'PConsolidado.ClasPrd1_descripcion',
						'PConsolidado.ClasPrd2_descripcion',
						'PConsolidado.ClasPrd3_descripcion',
						'PConsolidado.ClasPrd4_descripcion',
						'PConsolidado.ClasPrd5_descripcion',
						'PConsolidado.presentacion',
						'PConsolidado.rotacion'
		));
		$this->recursive = $recursive;
		$data = $this->find('all', array_merge($fields, $parameters, $extra));
		return count($data);
	}


}