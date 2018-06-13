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
$this->Html->addCrumb(
					'Reuperación de Datos', 
					'/usuarios/recovery'
)						
?>
<div class="row">
  <div class="col-md-12">
    <div class="box" style="height:360px;">
      <div class="box-title">
        <h3><i class="icon-table"></i>Recupera tu Contraseña</h3>
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
				
					echo $this->Form->input('correo', array(
											'label'=>'Cuenta de Correo',
											'class'=>'validate[required, custom[email]]'
										)
					);

					


				 echo $this->Form->end(__('Recuperar'));
			?>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

