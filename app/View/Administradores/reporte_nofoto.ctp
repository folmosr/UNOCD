<?php
$c = 0;
echo $this->Html->css(
					array(
								'jquery.fancybox',
								'jquery.ui.datepicker',
								'jquery.ui.theme',
								'validationEngine.jquery',
								'jquery.simplemodal'
							),
							 array('inline'=>false)
					);

echo $this->Html->script(
					array(
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
			$("#FilterForm").validationEngine();
			
		});
', 
						 array('inline'=>false)
					);

$this->Html->addCrumb(
					'Repor de productois sin foto asociada', 
					'/administradores/reporte_nofoto/'
);
?>
<!-- INICIO DE TABLA DINÁMICA -->

<div class="row">
<div class="col-md-12">
<div class="box">
<div class="box-title">
  <h3><i class="icon-table"></i> Productos sin foto asociada</h3>
</div>
<div class="box-content">
<div class="btn-toolbar pull-right clearfix">
  <div class="btn-group"> <a class="btn btn-circle  btn-info  " title="Aplicar filtros a la búsqueda" onclick="onFilter($(this), 'cerrar',event);" href="#"><i class="icon-link"></i></a>
    <?php 
		 if(!$data['init_set'])
		 {
		   		if(count($data) > 1)
		   			echo $this->Html->link('<i class="icon-table"></i>',array('action'=>'reporte_excel/'.$this->CustomFunctions->encode($nCliSelected)),array('class'=>'btn btn-circle  btn-info  ', 'escape'=>false));  
		   			else
			   			echo $this->Html->link('<i class="icon-table"></i>','#',array('class'=>'btn btn-circle  btn-info  ', 'title'=>'Sin data para emitir reporte', 'escape'=>false));  						
		 }else
			   			echo $this->Html->link('<i class="icon-table"></i>','#',array('class'=>'btn btn-circle  btn-info  ', 'title'=>'Sin data para emitir reporte', 'escape'=>false));  						
		   ?>
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
			echo $this->Form->create('Administrador', array(
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
              <td align="left" style="width:20%;text-align:left"><b>Clientes Asociados</b></td>
              <td><?php echo $this->Form->input('PConsolidado.ncli', array(
											'label'=>false,
											'div'=>false,
											'options'=>$clientes,
											'empty'=>'[Seleccionar]',
											'selected'=>($nCliSelected)?$nCliSelected:NULL,
											'style'=>'width:100%'
										)
								);					
						?></td>
            </tr>
            <tr>
              <td colspan="4"><div class="btn-toolbar pull-right clearfix">
                  <div class="btn-group"> <a class="btn btn-circle  btn-success  " title="Consultar" href="#" id="submitButtom"><i class="icon-check"></i></a> </div>
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
<div class="table-responsive" <?php (!(count($data)-1)>100)?'style="height:1500px;"':NULL; ?> >
<div class="scrollable-area">
<!-- FIN DE TABLA DINÁMICA -->
 <?php
		   if(count($data) > 1 ) { ?>
						<div class="alert alert-info alert-dismissable">
                            <a class="close" data-dismiss="alert" href="#">×</a>
	                         <h4>
    	                        <i class="icon-info-sign"></i>
        	                    Info
            	              </h4>
                		       <?php echo "Total de registros consultados: <b>".(count($data)-1).'</b>'; ?>
                        </div>
          <?php } ?>
          <br />
<table class="table table-hover table-striped" style="margin-bottom:0;">
  <thead>
    <tr>
      <th style="text-align:left"> Código </th>
    </tr>
  </thead>
  <tbody>
    <?php 
 if(!$data['init_set'])
 {
	if(count($data) > 1 ){

		for($i = 0; $i < (count($data)-1); $i++) :
			if (@getimagesize('http://unocd.com/inventario/imagenes3pl/Fotosprd/'.$data[$i]['PConsolidado']['codi'].'.jpg') == false) {
				if(@getimagesize('http://unocd.com/inventario/imagenes3pl/Fotosprd/'.$data[$i]['PConsolidado']['codi'].'.JPG') == false){
				?>
    <tr>
      <td style="text-align:left"><?php echo $data[$i]['PConsolidado']['codi']; ?></td>
    </tr>
    <?php 
    							$c++;
    						}
    					}
						endfor;
					}else{
						
				?>
    <tr>
      <td ><?php echo '<p>Su búsqueda no produjo ningún tipo de resultado</p>'; ?></td>
    </tr>
    <?php 
					} 
	 }else{
					?>
    <tr>
      <td ><p>Seleccione un cliente para iniciar la búsqueda</p></td>
    </tr>
                    <?php }?>
  </tbody>
</table>
<?php if($c > 0) { ?>
<div class="alert alert-info alert-dismissable">
                            <a class="close" data-dismiss="alert" href="#">×</a>
	                         <h4>
    	                        <i class="icon-info-sign"></i>
        	                    Info
            	              </h4>
                		       <?php echo 'Total de <b>códigos</b> sin foto asociada: <b>'.$c.'</b>'; ?>
                        </div>
<?php } ?>
</div>
</div>
</div>
</div>
</div>
</div>