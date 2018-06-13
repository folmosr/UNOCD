<?php
class ArchivoCsv extends Model {
	
	
	public $useTable = 'act_inv'; //archivo_csv
	public $primaryKey = 'id';

	public $virtualFields = array(
    	'fecha' => 'DATE_FORMAT(fecha_act, \'%d/%m/%Y %H:%i\')'
	);

}
?>