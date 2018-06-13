<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class Usuario extends Model {

	public $useTable   = 'users'; 
	public $actsAs = array('Containable');

	
	public function beforeSave($options = array())
	{
		if(isset($this->data['Usuario']['cedula']))
			$this->data['Usuario']['cedula']   = $this->data['Usuario']['nacionalidad_id'].'-'.$this->data['Usuario']['cedula'];
	    if(isset($this->data['Usuario']['nombre']))	
			$this->data['Usuario']['nombre']   = strtoupper(trim($this->data['Usuario']['nombre']));
		if(isset($this->data['Usuario']['apellido']))		
			$this->data['Usuario']['apellido'] = strtoupper(trim($this->data['Usuario']['apellido']));
		if(isset($this->data['Usuario']['correo']))				
			$this->data['Usuario']['correo']   = strtolower(trim($this->data['Usuario']['correo']));
		if(isset($this->data['Usuario']['password'])){
					$passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
					$this->data['Usuario']['password'] = $passwordHasher->hash( $this->data['Usuario']['password']);
		}
	}


     public $virtualFields = array(
  		'f_registro' => 'DATE_FORMAT(f_registro, \'%d/%m/%Y %H:%i\')',
  		'f_consulta' => 'DATE_FORMAT(f_registro, \'%Y-%m-%d 00:00\')',
		  'full_name' => 'CONCAT(apellido, " ", nombre)'	
		  
   );


  public $hasAndBelongsToMany = array(
        'Cliente' =>
            array(
                'className' => 'Cliente',
                'joinTable' => 'clients_users',
                'foreignKey' => 'user_id',
                'associationForeignKey' => 'cliente_id',
                'unique' => 'keepExisting',
		'with'=>'ClientsUser'
            )
    );	
	
    public $hasMany = array(
        'Solicitante'=> array(
					'className' => 'Pedido',
					'foreignKey' => 'id_usuario_solicitante'
        ),
        'Aprobador'=> array(
					'className' => 'Pedido',
					'foreignKey' => 'id_usuario_aprobador'
        )

    );	
}