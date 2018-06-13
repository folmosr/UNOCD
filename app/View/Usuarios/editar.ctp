<?php 

echo $this->Html->css(
					array(
								'validationEngine.jquery',
							),
							 array('inline'=>false)
					);

echo $this->Html->script(
					array(
							'jquery.validationEngine', 
							'jquery.validationEngine-es',
							'preventdoublesubmission.jquery.js',
							'jquery.custom'
						),
					 array('inline'=>false)	
				);
	$this->Html->addCrumb('Editar datos de Cliente', '/clientes/editar/'.$usuario_id); 
?>

<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-title">
        <h3><i class="icon-table"></i> Datos de Usuario</h3>
      </div>
      <div class="box-content">
        <div class="clearfix"></div>
        <div class="table-responsive">
          <div class="scrollable-area">
            <?php
				echo $this->Session->flash();
				echo $this->Form->create(array(
					'type' => 'file',
					'onSubmit'=>'return validateForm($(this));',
					'inputDefaults' => array(
						'div' => "form-element"
					)
				));
				
					echo $this->Form->input('cedula', array(
											'label'=>false,
											'class'=>'validate[required, custom[integer], minSize[5]], maxSize[8]] identify',
											'value'=>$cedula,											
											'before'=>$this->Form->input('nacionalidad_id',
											 array(
												 'label'=>'Cédula de Identidad',
												 'div'=>false,
												 'after'=>' - ',
												 'default'=>$nacionalidad,
												 'before'=>'<h3>Información Personal</h3>',
												 'options'=>array('V'=>'V', 'E'=>'E'),
												 'style'=>'width:6%'
												)
											),
											'after'=>'<span>*</span>'
										)
					);

					echo $this->Form->input('nombre', array(
											'label'=>'Nombre',
											'class'=>'validate[required, custom[onlyLetterSp]]',
											'after'=>'<span>*</span>'
										)
					);

					echo $this->Form->input('apellido', array(
											'label'=>'Apellido',
											'class'=>'validate[required, custom[onlyLetterSp]]',
											'after'=>'<span>*</span>'
										)
					);

					echo $this->Form->input('correo', array(
											'label'=>'Correo Eléctronico',
											'class'=>'validate[required, custom[email]]',
											'before'=>'<h3>Información de Contacto</h3>',
											'after'=>'<span>*</span>'
										)
					);

					echo $this->Form->input('tel_ofi', array(
											'label'=>'Teléfono de Oficina',
											'class'=>'validate[required, custom[phone]]',
											'after'=>'<span>*</span>'
										)
					);

					echo $this->Form->input('tel_cel', array(
											'label'=>'Teléfono Celular',
											'class'=>'validate[required, custom[phone]]',
											'after'=>'<span>*</span>'
										)
					);

					echo $this->Form->input('Cliente.cliente_id', array(
											'label'=>'Cliente',
											'class'=>'validate[required]',
											'empty'=>'[Seleccionar]',
											'before'=>'<h3>Asociar</h3>',
											'after'=>'<span>*</span>',
											'default'=>$this->data['Cliente'][0]['ncli']
										)
					);
					
					echo $this->Form->input('realiza_pedidos', array(
															'label'=>'Realiza Pedidos',
															'value'=>true,
															'before'=>'<h3>Privilegios</h3>',
															'type'=>'checkbox'
											)
				);

					echo $this->Form->input('aprueba_pedidos', array(
															'label'=>'Aprueba Pedidos',
															'value'=>true,
															'type'=>'checkbox'
											)
				);
					echo $this->Form->input('notificaciones', array(
															'label'=>'Recibir Notificación',
															'value'=>true,
															'type'=>'checkbox'
											)
				);

					echo $this->Form->input('copiar', array(
															'label'=>'Copiar Imgs. e Info.',
															'value'=>true,
															'type'=>'checkbox'
											)
				);

					echo $this->Form->input('id', array(
															'label'=>false,
															'div'=>false,
															'value'=>$this->CustomFunctions->encode($this->data['Usuario']['id']),
															'type'=>'hidden'
											)
				);
					echo $this->Form->input('ClientsUser.id', array(
															'label'=>false,
															'div'=>false,
															'value'=>$this->CustomFunctions->encode($this->data['Cliente'][0]['ClientsUser']['id']),
															'type'=>'hidden'
											)
				);

				echo $this->Form->end('Actualizar');
			?>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
