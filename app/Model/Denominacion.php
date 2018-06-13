<?php
class Denominacion extends Model {
	
	
	public $useTable = 'denominaciones'; 
	
	public function beforeSave($options = array()) {
		$this->data['Denominacion']['clasprd1']   = mb_strtoupper(trim($this->data['Denominacion']['clasprd1']), 'UTF-8');
		$this->data['Denominacion']['clasprd2']   = mb_strtoupper(trim($this->data['Denominacion']['clasprd2']), 'UTF-8');
		$this->data['Denominacion']['clasprd3']   = mb_strtoupper(trim($this->data['Denominacion']['clasprd3']), 'UTF-8');
		$this->data['Denominacion']['clasprd4']   = mb_strtoupper(trim($this->data['Denominacion']['clasprd4']), 'UTF-8');
		$this->data['Denominacion']['clasprd5']   = mb_strtoupper(trim($this->data['Denominacion']['clasprd5']), 'UTF-8');

		return true;
	}
	
	
	public $belongsTo = array(
        'Cliente' => array(
            'className' => 'Cliente',
            'foreignKey' => 'cliente_id'
        )
	);	
}
