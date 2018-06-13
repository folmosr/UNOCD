<?php
$this->Html->script(array(
							'jquery.validationEngine', 
							'jquery.validationEngine-es', 
							'preventdoublesubmission.jquery.js'
							), array(
									'inline' => false
								)
				);
$this->Html->css(array(
							'validationEngine.jquery'
						), null, array(
								'inline' => false
							)
				);
	$this->Html->addCrumb('Recuperación de Datos', '/usuarios/secure/'.$correo.'/'.$usuario_id); 
						
?>
<div class="row">
  <div class="col-md-12">
    <div class="box" style="height:360px;">
      <div class="box-title">
        <h3><i class="icon-table"></i>Recuperación de Datos</h3>
      </div>
      <div class="box-content">
        <div class="clearfix"></div>
        <div class="table-responsive">
          <div class="scrollable-area">
    <?php 				echo $this->Session->flash();
				echo $this->Form->create(array(
					'type' => 'file',
					'onSubmit'=>'return validateForm($(this));',
					'inputDefaults' => array(
						'div' => "form-element"
					)
				));

	
		echo $this->Form->input('password', array(
													'label'=>'Contraseña',
													'class'=>'validate[required,minSize[8]]',
													'after'=>'<span>*</span>'
												)
		);
		
		echo $this->Form->input('c_password', array(
													'label'=>'Confirmar Contraseña',
													'class'=>'validate[required,equals[UsuarioPassword]]',
													'type'=>'password',
													'after'=>'<span>*</span>'
												)
		);

			echo $this->Form->input('id', array(
													'label'=>false,
													'div'=>false,
													'type'=>'hidden',
													'value'=>$this->CustomFunctions->encode($data['Usuario']['id'])
												)
			);
			echo $this->Form->input('ml', array(
													'label'=>false,
													'div'=>false,
													'type'=>'hidden',
													'value'=>$this->CustomFunctions->encode($data['Usuario']['correo'])
												)
			);
			echo $this->Form->input('nb', array(
													'label'=>false,
													'div'=>false,
													'type'=>'hidden',
													'value'=>$this->CustomFunctions->encode($data['Usuario']['nombre'])
												)
			);
			echo $this->Form->input('ap', array(
													'label'=>false,
													'div'=>false,
													'type'=>'hidden',
													'value'=>$this->CustomFunctions->encode($data['Usuario']['apellido'])
												)
			);
		
		echo $this->Form->end("Recuperar");
	?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
