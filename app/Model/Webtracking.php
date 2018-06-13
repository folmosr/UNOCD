<?php
class Webtracking extends Model {

	public $useTable = 'webtracking'; 
	public $actsAs = array('Containable');

	public $virtualFields = array(
    		'fecha' => 'DATE_FORMAT(fecha, \'%d/%m/%Y %H:%i\')',
	);
						
    public $belongsTo = array(
		'Pedido'

    );	
}
