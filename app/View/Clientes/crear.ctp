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
							'math/base_convert',
							'strings/str_pad',
							'strings/chr',
							'strings/substr',
							'strings/ord',
							'strings/strlen',
							'url/base64_encode',
							'url/base64_decode',
							'preventdoublesubmission.jquery.js',
							'jquery.custom'
						),
					 array('inline'=>false)	
				);
$this->Html->scriptBlock('
	$(document).ready(function(){
			activaClasificacion();		
	});
	
', 
	 array('inline'=>false)
);
	$this->Html->addCrumb('Crear Clientes', '/clientes/crear'); 
?>

<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-title">
        <h3><i class="icon-table"></i> Datos de Clientes</h3>
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
				
					echo $this->Form->input('ncli', array(
											'label'=>'Cliente',
											'class'=>'validate[required]',
											'empty'=>'[Seleccionar]',
											'options'=>$clientes,
											'onchange'=>'searchCliente( \''.$this->CustomFunctions->encode($this->Html->url('/', true).'clientes/crear/').'\', $(this));',
											'after'=>'<span>*</span><span class="nota_validacion" style="display:none;">* Valor no permitido</span><h3>Denominación de Clasificaciones</h3>'
										)
					);


					echo $this->Form->input('Denominacion.clasprd1', array(
											'label'=>'Clasificación Nº 1',
											'rel'=>'c_denominacion',											
											'readonly'=>((!empty($this->data)) && ($this->data['Denominacion']['clasprd1']!=''))?false:true,												
											'before'=>$this->Form->input('Denominacion.activo_clasprd1', array(
																								'div'=>false,
																								'label'=>false,
																								'value'=>true,
																								'type'=>'checkbox'
																							)
											)
										)
					);
					echo $this->Form->input('Denominacion.clasprd2', array(
											'label'=>'Clasificación Nº 2',
											'rel'=>'c_denominacion',
											'readonly'=>((!empty($this->data)) && ($this->data['Denominacion']['clasprd2']!=''))?false:true,												
											'class'=>'validate[custom[onlyLetterSp]]',
											'before'=>$this->Form->input('Denominacion.activo_clasprd2', array(
																								'div'=>false,
																								'label'=>false,
																								'value'=>true,
																								'type'=>'checkbox'
																							)
											)
										)
					);
					echo $this->Form->input('Denominacion.clasprd3', array(
											'label'=>'Clasificación Nº 3',
											'rel'=>'c_denominacion',											
											'readonly'=>((!empty($this->data)) && ($this->data['Denominacion']['clasprd3']!=''))?false:true,												
											'class'=>'validate[custom[onlyLetterSp]]',
											'before'=>$this->Form->input('Denominacion.activo_clasprd3', array(
																								'div'=>false,
																								'label'=>false,
																								'value'=>true,
																								'type'=>'checkbox'
																							)
											)
										)
					);
					echo $this->Form->input('Denominacion.clasprd4', array(
											'label'=>'Clasificación Nº 4',
											'class'=>'validate[custom[onlyLetterSp]]',
											'rel'=>'c_denominacion',
											'readonly'=>((!empty($this->data)) && ($this->data['Denominacion']['clasprd4']!=''))?false:true,												
											'before'=>$this->Form->input('Denominacion.activo_clasprd4', array(
																								'div'=>false,
																								'label'=>false,
																								'value'=>true,
																								'type'=>'checkbox'
																							)
											)
										)
					);
					echo $this->Form->input('Denominacion.clasprd5', array(
											'label'=>'Clasificación Nº 5',
											'class'=>'validate[custom[onlyLetterSp]]',
											'rel'=>'c_denominacion',
											'readonly'=>((!empty($this->data)) && ($this->data['Denominacion']['clasprd5']!=''))?false:true,												
											'before'=>$this->Form->input('Denominacion.activo_clasprd5', array(
																								'div'=>false,
																								'label'=>false,
																								'value'=>true,
																								'type'=>'checkbox'
																							)
											)
										)
					);

					echo $this->Form->input('FAlmacenaje.tipo_factor_id', array(
											'label'=>'Tipo Factor',
											'class'=>'validate[required]',
											'empty'=>'[Seleccionar]',
											'options'=>$tipos_factores,
											'before'=>'<h3>Datos Factor de Almacenaje</h3>',
											'after'=>'<span>*</span>'
										)
					);
					echo $this->Form->input('FAlmacenaje.valor', array(
											'label'=>'Valor',
											'class'=>'validate[required, custom[number]]',
											'type'=>'text',
											'after'=>'<span>*</span><span class="nota">Sólo números</span></span>'
										)
					);
					echo $this->Form->input('FDistribucion.tipo_factor_id', array(
											'label'=>'Tipo Factor',
											'class'=>'validate[required]',
											'empty'=>'[Seleccionar]',
											'options'=>$tipos_factores,
											'before'=>'<h3>Datos Factor de Distribución</h3>',
											'after'=>'<span>*</span>'
										)
					);
					echo $this->Form->input('FDistribucion.valor', array(
											'label'=>'Valor',
											'class'=>'validate[required, custom[number]]',
											'type'=>'text',
											'after'=>'<span>*</span><span class="nota">Sólo números</span></span>'
										)
					);
				
				if(!empty($this->data))
				{
					echo $this->Form->input('Denominacion.id', array(
											'label'=>false,
											'div'=>false,
											'value'=>$this->CustomFunctions->encode($this->data['Denominacion']['id']),
											'type'=>'hidden'
										)
					);
					echo $this->Form->input('FAlmacenaje.id', array(
											'label'=>false,
											'value'=>$this->CustomFunctions->encode($this->data['FAlmacenaje']['id']),
											'div'=>false,
											'type'=>'hidden'
										)
					);
					echo $this->Form->input('FDistribucion.id', array(
											'label'=>false,
											'div'=>false,
											'value'=>$this->CustomFunctions->encode($this->data['FDistribucion']['id']),											
											'type'=>'hidden'
										)
					);
					
				}
				echo $this->Form->end((empty($this->data))?'Crear':'Actualizar');
			?>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
