<?php
class Clifactura extends Model {

	public $useTable = 'clifactura'; 
	public $actsAs = array('Containable');


	public $virtualFields = array(
    		'FECHADES' => 'DATE_FORMAT(FECHADES, \'%d/%m/%Y\')',
			'FECHAENT' => 'DATE_FORMAT(FECHAENT, \'%d/%m/%Y\')',
	);
						
    public $belongsTo = array(
		'Pedido'

    );	
}
