<?php
echo $this->Session->flash();
$this->Html->script(array(
							'jquery.validationEngine', 
							'jquery.validationEngine-es', 
							'jquery.fancybox.js?v=2.1.5',
							'preventdoublesubmission.jquery.js',
							'custom.functions'
							), array(
									'inline' => false
								)
				);
$this->Html->css(array(
							'validationEngine.jquery',
							'jquery.fancybox.css?v=2.1.5',
						), null, array(
								'inline' => false
							)
				);
$this->Html->scriptBlock(
						'$(document).ready(function(){

								fancyImage();
						     }
						 );
						', 
						 array('inline'=>false)
						);
$this->Html->addCrumb('Actualización Datos de Perfil', '/usuarios/profile'); 

?>
<div class="row">
  <div class="col-md-12">
    <div class="box" style="height:360px;">
      <div class="box-title">
        <h3><i class="icon-table"></i>Actualizar Perfil de Usuario</h3>
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
											'value'=>$this->Session->read('Auth.User.id')
										)
	);
	
	
	echo $this->Form->end("Actualizar");
?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
