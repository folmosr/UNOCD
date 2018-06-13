<?php 
//echo date("d/m/Y H:i");
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
$this->Html->scriptBlock('
	$(document).ready(function(){
			 cleanLocalStorage(0);
	});
', 
	 array('inline'=>false)
);
	$this->Html->addCrumb('Autenticación de Usuarios', '/usuarios/login'); 
?>

<div class="row">
  <div class="col-md-12">
    <div class="box" style="height:608px;">
      <div class="box-title">
        <h3><i class="icon-table"></i>Inicia sesión para acceder</h3>
      </div>
      <div class="box-content">
        <div class="clearfix"></div>
        <div class="table-responsive">
          <div class="scrollable-area">
            <?php
				echo $this->Session->flash();
				echo $this->Form->create(array(
					'type' => 'file',
					'onsubmit'=>'return validateForm($(this));',
					'inputDefaults' => array(
						'div' => "form-element"
					)
				));
				
					echo $this->Form->input('correo', array(
											'label'=>'Cuenta de Correo',
											'class'=>'validate[required, custom[email]]',
											'value'=>(isset($cookie) && !is_null($cookie))?$cookie:NULL
										)
					);

					echo $this->Form->input('password', array(
											'label'=>'Contraseña',
											'type'=>'password',
											'class'=>'validate[required, custom[onlyLetterNumber]]',
											'after'=>'<div class="recovery_tools">
																				<span>'. $this->Html->link('¿Haz olvidado tu contraseña?',  array(
																																'controller' => 'usuarios',
																																'action' => 'recovery',
																																'full_base' => true																											)
													) .'</span>|
																				<span>'.$this->Form->input('remain', array('label'=>false, 'div'=>false, 'type'=>'checkbox', 'value'=>1)).'No cerrar sesión</span>
																		</div>',
										)
					);


				echo $this->Form->end('Acceder');
			?>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
