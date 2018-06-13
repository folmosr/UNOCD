<?php
class Notificacion extends Model {
	
	
	public $useTable = 'notificaciones'; 

	public $virtualFields = array(
    		'unixcreaciontime' => ' UNIX_TIMESTAMP( Notificacion.creacion ) ',
	);
	
	public $actsAs     = array('Containable');
	
	public $belongsTo = array(
        'Pedido' => array(
			'type'=>'INNER',
            'className' => 'Pedido',
            'foreignKey' => 'id_pedido'
        ),
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'vista_por'
        ),
				
    );
}
?>