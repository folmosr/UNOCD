<?php
echo $this->Html->css(
					array(
								'jquery.ui.datepicker',
								'jquery.ui.theme',
								'validationEngine.jquery',
							),
							 array('inline'=>false)
					);

echo $this->Html->script(
					array(
							'jquery.ui.core',
							'jquery.ui.widget',
							'jquery.ui.datepicker',
							'jquery.ui.datepicker_es',							
							'jquery.validationEngine', 
							'jquery.validationEngine-es',
							'preventdoublesubmission.jquery.js',
							'jquery.custom.js'
						),
					 array('inline'=>false)	
				);
$this->Html->scriptBlock('
	$(document).ready(function(){
			$("#submitButtom").click(function() {
				  $("#FilterForm").submit();
  				  $("#FilterForm").data("submitted", true);
  				  $("#FilterForm").preventDoubleSubmission();
			});
			$("#FilterForm").validationEngine({promptPosition : "topLeft"});
			  $( "#from" ).datepicker({
				  defaultDate: "+1w",
				  changeMonth: true,
				  numberOfMonths: 3,
				  onClose: function( selectedDate ) {
					$( "#to" ).datepicker( "option", "minDate", selectedDate );
				  }
				});
				$( "#to" ).datepicker({
				  defaultDate: "+1w",
				  changeMonth: true,
				  numberOfMonths: 3,
				  onClose: function( selectedDate ) {
					$( "#from" ).datepicker( "option", "maxDate", selectedDate );
				  }
				});

		});
', 
						 array('inline'=>false)
					);

$this->Html->addCrumb(
					'Listado de Coordinadores', 
					'/coordinadores/listar'
);
?>
<!-- INICIO DE TABLA DINÁMICA -->

<div class="row">
<div class="col-md-12">
  <div class="box">
    <div class="box-title">
      <h3><i class="icon-table"></i> Coordinadores Registrados</h3>
    </div>
    <div class="box-content">
      <div class="btn-toolbar pull-right clearfix">
        <div class="btn-group">  
        	<a class="btn btn-circle  btn-info  " title="Aplicar filtros a la búsqueda" onclick="onFilter($(this), 'cerrar',event);" href="#"><i class="icon-link"></i></a>
		</div>
      </div>
      <br/>
      <br/>
      <div class="clearfix"></div>
      <div id="order-list">
        <div class="content"></div>
      </div>
      <div id="filter" >
        <div class="content">
          <div class="box bordered-box green-border" style="margin-bottom:0;">
            <div class="box-header green-background">
              <div class="box-title">
                <h3>Filtro de Búsqueda</h3>
              </div>
            </div>
            <div class="box-content box-no-padding">
            <?php 
			echo $this->Form->create('Usuario', array(
											'id' => 'FilterForm',	
											'inputDefaults' => array(
												'label' => false,
												'div' => false
											)
						));
			?>
              <table class="table table-bordered table-hover" style="margin-bottom:0;">
                <tbody>
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b>Cédula de Identidad</b></td>
                    <td><?php 				
							echo $this->Form->input('cedula', array(
													'label'=>false,
													'class'=>'validate[custom[integer], minSize[5]], maxSize[8]]',
													'style'=>'width:92%',
													'before'=>$this->Form->input('nacionalidad_id',
													 array(
														 'label'=>false,
														 'div'=>false,
														 'after'=>' - ',
														 'options'=>array('V'=>'V', 'E'=>'E'),
														 'style'=>'width:6%'
														)
													)
												)
							);
			
						?>
                        </td>
                  </tr>
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b>Correo Eléctronico</b></td>
                    <td><?php echo $this->Form->input('Usuario.correo', array(
											'label'=>false,
											'type'=>'text',
											'validate[custom[email]]',
											'div'=>false,
											'style'=>'width:100%'
										)
								);					
						?>
                        </td>
                  </tr>
                  <tr>
                  	<td  style="width:20%;text-align:left"><b>Apellido/Nombre</b></td>
                    <td><?php echo $this->Form->input('Usuario.nombre', array(
											'label'=>false,
											'div'=>false,
											'class'=>'validate[custom[onlyLetterSp]]',
											'style'=>'width:100%'
										)
								);					
						?>
                        </td>
                  </tr>
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b>Fecha de Registro (desde)</b></td>
                    <td><?php echo $this->Form->input('Usuario.f_ini', array(
											'label'=>false,
											'id'=>'from',
											'type'=>'text',
											'div'=>false,
											'style'=>'width:100%',
											'readOnly'=>'readOnly'
										)
								);					
						?>
                        </td>
                  </tr>
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b>Fecha de Registro (Hasta)</b></td>
                    <td><?php echo $this->Form->input('Usuario.f_fin', array(
											'label'=>false,
											'id'=>'to',
											'type'=>'text',
											'div'=>false,
											'style'=>'width:100%',
											'readOnly'=>'readOnly'
										)
								);					
						?>
                        </td>
                  </tr>
                  <tr>
                    <td colspan="4"><div class="btn-toolbar pull-right clearfix">
                        <div class="btn-group">
	                         <a class="btn btn-circle  btn-success  " title="Consultar" href="#" id="submitButtom"><i class="icon-check"></i></a>	
                             </div>
                      </div></td>
                  </tr>
                </tbody>
              </table>
              <?php             
			 echo  $this->Form->end();
			  ?>
            </div>
          </div>
        </div>
      </div>
      <div class="table-responsive" style="height:1500px;">
        <div class="scrollable-area"> 
          <!-- FIN DE TABLA DINÁMICA -->
          <?php if(count($data) > 0 ) { ?>
						<div class="alert alert-info alert-dismissable">
                            <a class="close" data-dismiss="alert" href="#">×</a>
	                         <h4>
    	                        <i class="icon-info-sign"></i>
        	                    Info
            	              </h4>
                		       <?php echo $this->Paginator->counter('Página {:page} de {:pages}, Mostrando <b>{:current}</b> registros de un <b> total de {:count}</b>'); ?>
                        </div>
          <?php } ?>
          <br />
          <?php 
		  	echo $this->Session->flash();
		  ?>
          <div class="paging">
        	<?php
            	echo $this->Paginator->first("  |<  ");
				if($this->Paginator->hasNext()){
					echo $this->Paginator->next(" > ");
				}
				echo $this->Paginator->numbers(array('modulus' => 8));
				if($this->Paginator->hasPrev()){
					echo $this->Paginator->prev(" < ");
				}
        		echo $this->Paginator->last("  >|  ");

			  ?>
           </div>
          <table class="table table-hover table-striped" style="margin-bottom:0;">
            <thead>
              <tr>
                <th> # </th>
                <th> Apellido / Nombre </th>
                <th> Correo Eléctronico </th>
                <th> Estado </th>
                <th> Acciones </th>
              </tr>
            </thead>
            <tbody>
              <?php 
					if(count($data) > 0 )
					{
						for($i = 0; $i < count($data); $i++) :
				?>
				<tr>
					<td><?php echo $data[$i]['Usuario']['id']; ?></td>                    
					<td style="text-align:left"><?php echo $data[$i]['Usuario']['apellido']. ' '.$data[$i]['Usuario']['nombre']; ?></td>                    
                 	<td style="text-align:left"><?php echo $data[$i]['Usuario']['correo']; ?></td>
                    <td valign="middle">
								 <?php if($data[$i]['Usuario']['status']) { ?>
                            	   <span class="label label-success">Activo</span>
                       <?php }else{?>
                                       <span class=" label label-important">No Activo</span> 
                                       <?php }?>

                    </td>
                 	<td>
                       <div class="btn-group">
                      		<a href="<?php  if($data[$i]['Usuario']['status'])  echo '/unocdcom/administradores/editar/'.$this->CustomFunctions->encode($data[$i]['Usuario']['id']); else echo '#'; ?>" class="btn btn-circle <?php if($data[$i]['Usuario']['status']) echo 'btn-success'; ?>" title="Actualizar Datos de Usuario <?php if(!$data[$i]['Usuario']['status']) echo '- active al usuario para poder realizar esta acción sobre los datos del mismo'; ?>"><i class="icon-ok-sign"></i></a>                       
                            <a href="/unocdcom/administradores/q_on/<?php echo $this->CustomFunctions->encode($data[$i]['Usuario']['id']); ?>" class="btn btn-circle <?php  echo ($data[$i]['Usuario']['status'])?'btn-danger':'btn-success'; ?>" title="Desactivar Usuario"><i class="icon-user"></i></a>
                      </div>
                    </td>
                 </tr>
              <?php 
						endfor;
					}else{
						
				?>
                <tr>
                	<td colspan="7">
                   	<?php echo '<p>Su búsqueda no produjo ningún tipo de resultado</p>'; ?>          					
                    </td>
                </tr>
                <?php 
					} 
					?>
            </tbody>
          </table>
          <div class="paging">
        	<?php
            	echo $this->Paginator->first("  |<  ");
				if($this->Paginator->hasNext()){
					echo $this->Paginator->next(" > ");
				}
				echo $this->Paginator->numbers(array('modulus' => 8));
				if($this->Paginator->hasPrev()){
					echo $this->Paginator->prev(" < ");
				}
        		echo $this->Paginator->last("  >|  ");

			  ?>
           </div>
          
        </div>
      </div>
    </div>
  </div>
</div>