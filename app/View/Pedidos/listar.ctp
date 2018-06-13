<?php
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
							'jquery.ui.core',
							'jquery.ui.widget',
							'jquery.ui.datepicker',
							'jquery.ui.datepicker_es',							
							'math/base_convert',
							'strings/chr',
							'strings/strlen',
							'strings/substr',
							'strings/ord',
							'strings/str_pad',
							'url/base64_decode',
							'url/base64_encode',
							'jquery.fancybox',
							'jquery.validationEngine', 
							'jquery.validationEngine-es', 
							'jquery.simplemodal',
							'preventdoublesubmission.jquery.js',
							'jquery.custom.js'
						),
					 array('inline'=>false)	
				);
$this->Html->scriptBlock('
	$(document).ready(function(){
		
		TabDetallePanel();		
		
		$(".fancybox").fancybox({
				wrapCSS    : \'fancybox-custom\',
				closeClick : true,

				openEffect : \'none\',

				helpers : {
					title : {
						type : \'inside\'
					},
					overlay : {
						css : {
							\'background\' : \'rgba(238,238,238,0.85)\'
						}
					}
				}
			});
			$("#submitButtom").click(function() {
				  $("#FilterForm").submit();
  				  $("#FilterForm").data("submitted", true);
  				  $("#FilterForm").preventDoubleSubmission();
			});
			$("#FilterForm").validationEngine();
			
			  $( "#from" ).datepicker({
				  defaultDate: "+1w",
				  changeMonth: true,
				  numberOfMonths: 1,
				  onClose: function( selectedDate ) {
					$( "#to" ).datepicker( "option", "minDate", selectedDate );
				  }
				});
				$( "#to" ).datepicker({
				  defaultDate: "+1w",
				  changeMonth: true,
				  numberOfMonths: 1,
				  onClose: function( selectedDate ) {
					$( "#from" ).datepicker( "option", "maxDate", selectedDate );
				  }
				});
		});
', 
						 array('inline'=>false)
					);

$this->Html->addCrumb(
					'Listado de Pedidos', 
					'/pedidos/listar'
);
$header_l = 2;
$header_r = 3;
if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd1'))
$header_l++;
if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd2'))
$header_l++;

if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd3'))
$header_l++;

if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd4'))
$header_l++;

if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd5'))
$header_l++;

?>
<!-- INICIO DE TABLA DINÁMICA -->

<div class="row">
<div class="col-md-12">
  <div class="box">
    <div class="box-title">
      <h3><i class="icon-table"></i> Pedidos</h3>
    </div>
    <div class="box-content">
      <div class="btn-toolbar pull-right clearfix">
        <div class="btn-group">  
        	<a class="btn btn-circle  btn-info  " title="Aplicar filtros a la búsqueda" onclick="onFilter($(this), 'cerrar',event);" href="#"><i class="icon-link"></i></a>
           
           <?php 
		   		if(count($data) > 0)
		   			echo $this->Html->link('<i class="icon-table"></i>',array_merge(array('action'=>'reporte_excel'),$arguments),array('class'=>'btn btn-circle  btn-info  ', 'escape'=>false));  
		   			else
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
			echo $this->Form->create('Producto', array(
											'id' => 'FilterForm',	
											'inputDefaults' => array(
												'label' => false,
												'div' => false
											)
						));
						


			?>
              <table class="table table-bordered table-hover" style="margin-bottom:0;">
                <tbody>
                 <?php 
				 	if((count($this->Session->read('Auth.User.Cliente')) > 0) && ($this->Session->read('Auth.User.rol_id') != 1)){
				 ?> 
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b>Clientes Asociados</b></td>
                    <td><?php echo $this->Form->input('Pedido.ncli', array(
											'label'=>false,
											'div'=>false,
											'options'=>$this->CustomFunctions->getClientList($this->Session->read('Auth.User.Cliente')),
											'empty'=>'[Seleccionar]',
											'selected'=>($nCliSelected)?$nCliSelected:NULL,
											'style'=>'width:100%'
										)
								);					
						?>
                        </td>
                  </tr>
				<?php } ?>                
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b>Número de Pedido</b></td>
                    <td><?php echo $this->Form->input('Pedido.id_pedido', array(
											'label'=>false,
											'div'=>false,
											'style'=>'width:100%',
											'class'=>'validate[custom[onlyNumberSp]]',
											'type'=>'text'
										)
								);					
						?>
                        </td>
                  </tr>
                  <?php 
				  
				  	if((
					
					($this->Session->read('Auth.User.rol_id')==1) && ($this->Session->read('Auth.User.aprueba_pedidos'))
					
					)
					
					||
					($this->Session->read('Auth.User.rol_id') > 1)
					)
				  	{
				  ?>
                  <tr>
                  	<td  style="width:20%;text-align:left"><b>Solicitante</b></td>
                    <td><?php echo $this->Form->input('Pedido.id_solicitante', array(
											'label'=>false,
											'div'=>false,
											'options'=>$solicitantes,
											'empty'=>'[Todos]',
											'style'=>'width:100%'
										)
								);					
						?>
                        </td>
                  </tr>
                  <?php } ?>
                  <tr>
                  	<td  style="width:20%;text-align:left"><b>Estatus</b></td>
                    <td><?php echo $this->Form->input('Pedido.id_status', array(
											'label'=>false,
											'div'=>false,
											'options'=>$estatus,
											'empty'=>'[Todos]',
											'style'=>'width:100%'
										)
								);					
						?>
                        </td>
                  </tr>
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b>Fecha Solicitada (Desde)</b></td>
                    <td><?php echo $this->Form->input('Pedido.f_desde', array(
											'label'=>false,
											'div'=>false,
											'id'=>'from',
											'style'=>'width:100%',
											'readOnly'=>'readOnly'
										)
								);					
						?>
                        </td>
                  </tr>
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b>Fecha Solicitada (Hasta)</b></td>
                    <td><?php echo $this->Form->input('Pedido.f_hasta', array(
											'label'=>false,
											'div'=>false,
											'id'=>'to',
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
      <div class="table-responsive" style="height:1500px;" >
        <div class="scrollable-area"> 
          <!-- FIN DE TABLA DINÁMICA -->
          <?php
		   if(count($data) > 0 ) { 
		   
		   echo $this->Session->flash(); ?>
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
          <div id="r-ok" class="alert alert-success alert-dismissable " style="display:none"> 
				<a class="close" data-dismiss="alert" href="#">×</a> <h4> <i class="icon-info-sign"></i> Info </h4>
				El Pedido con el número de Orden <b rel="pk-pedido"></b> ha sido procesado satisfactoriamente.
		  </div>
          
          <div id="r-n-ok" class="alert alert-danger alert-dismissable " style="display:none"> 
				<a class="close" data-dismiss="alert" href="#">×</a> <h4> <i class="icon-info-sign"></i> Info </h4>
				Han ocurrido errores al intentar de procesar los datos del pedido <b rel="pk-pedido-error"></b>. 
		  </div>
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
                <th> Solicitante </th>
                <th> Fecha de Solicitud </th>
                <th> Fecha de Proceso </th>
                <th> Estatus </th>
                <th width="200"> Acciones </th>
              </tr>
            </thead>
            <tbody>
              <?php 
					if(count($data) > 0 )
					{
						for($i = 0; $i < count($data); $i++) :
	 						  $w_index = (count($data[$i]['Webtracking'])-1);  
				?>
	
                 
                <tr>
				<td><?php echo $data[$i]['Pedido']['id_pedido']; ?></td>                    
					<td><?php echo $data[$i]['Solicitante']['apellido'].' '.$data[$i]['Solicitante']['nombre']; ?></td>                    
                 	<td><?php echo $data[$i]['Pedido']['f_solicitud']; ?></td>
                 	<td><?php echo ($data[$i]['Pedido']['fecha_proceso']==NULL)?'Aún por Procesar':$data[$i]['Pedido']['fecha_proceso']; ?></td>
                    <td>
                    <span id="iElement<?php echo $this->CustomFunctions->encode($data[$i]['Pedido']['id_pedido']); ?>"  class="<?php 
							
									if(!count($data[$i]['Webtracking']) > 0 )
									{
										if($data[$i]['Pedido']['estado']== 1) 
											echo 'label label-yellow'; 
										elseif($data[$i]['Pedido']['estado']== 2) 
											echo 'label label-success'; 
										elseif($data[$i]['Pedido']['estado']== 3) 
											echo 'label label-important'; else echo 'label label-info';    
									}else
										echo 'label '.$this->CustomFunctions->getEstatusTagColor($data[$i]['Webtracking'], $w_index);   ?>"><?php 	if(!count($data[$i]['Webtracking']) > 0 )
											echo $data[$i]['Estatu']['descripcion'];  
										else
											echo $this->CustomFunctions->getWebtrackingStatus($data[$i]['Webtracking'][$w_index]['stat_pedido'],$data[$i]['Webtracking'][$w_index]['stat_transf']);	  ?></span>
                    </td>                    
                 	<td width="200">
                       <div class="btn-group">
		                     <a  class="btn btn-circle  btn-primary  " title="Ver Detalle del Pedido" href="#" onclick="showUpPedidoDetalle($('<?php echo '#detalle-'.$this->CustomFunctions->encode($data[$i]['Pedido']['id_pedido']); ?>'), event)"><i class="icon-list-alt"></i></a>
                             <a id="cTrigger<?php echo $this->CustomFunctions->encode($data[$i]['Pedido']['id_pedido']); ?>" class="btn btn-circle  <?php echo ($data[$i]['Pedido']['estado']== 1)?'btn-danger':NULL; ?>    " title="Cancelar<?php echo ($data[$i]['Pedido']['estado']== 4)?'- El Pedido ha sido finalizado por lo tanto esta función ya no está disponible':NULL; ?>" href="#" <?php echo ($data[$i]['Pedido']['estado']== 1)?'onclick="setStatusForOrder(\''.$this->CustomFunctions->encode($this->Html->url('/', true)).'\', \'pedidos/edit/\', \''.$this->CustomFunctions->encode($data[$i]['Pedido']['id_pedido']).'\', \''.$this->CustomFunctions->encode(3).'\', '.$this->Session->read('Auth.User.Cliente.'.$indexUser.'.ncli').',  $(this), event, \''.$this->CustomFunctions->encode(4).'\');"' : NULL; ?>><i class="icon-minus"></i></a>

                             <?php if($this->Session->read('Auth.User.aprueba_pedidos')) { ?>
                             <a id="aTrigger<?php echo $this->CustomFunctions->encode($data[$i]['Pedido']['id_pedido']); ?>" class="btn btn-circle  <?php echo ($data[$i]['Pedido']['estado']!= 1)?NULL:'btn-success'; ?>  " title="Aprobar<?php echo ($data[$i]['Pedido']['estado']== 4)?'- El Pedido ha sido finalizado por lo tanto esta función ya no está disponible':NULL; ?>" href="#" <?php echo ($data[$i]['Pedido']['estado']== 1)?'onclick="setStatusForOrder(\''.$this->CustomFunctions->encode($this->Html->url('/', true)).'\', \'pedidos/edit/\', \''.$this->CustomFunctions->encode($data[$i]['Pedido']['id_pedido']).'\', \''.$this->CustomFunctions->encode(2).'\', '.$this->Session->read('Auth.User.Cliente.'.$indexUser.'.ncli').',  $(this), event,\''.$this->CustomFunctions->encode(4).'\' );"' : NULL; ?>><i class="icon-ok-sign"></i></a>
                             <?php }if($this->Session->read('Auth.User.finaliza_pedidos')) { ?>
                             <a id="fTrigger<?php echo $this->CustomFunctions->encode($data[$i]['Pedido']['id_pedido']); ?>" class="btn btn-circle  <?php echo ($data[$i]['Pedido']['estado']== 2)?'btn-yellow':NULL; ?>  " title="Finalizar <?php if(($data[$i]['Pedido']['estado'] == 1)||($data[$i]['Pedido']['estado'] == 3)) echo '- Esta funcion estará disponible sólo cuando el pedido haya sido aprobado'; elseif($data[$i]['Pedido']['estado'] == 2) echo '- Finalizar pedido'; elseif($data[$i]['Pedido']['estado'] == 4) echo '- Pedido finalizado'; ?>" href="#" <?php echo ($data[$i]['Pedido']['estado']== 2)?'onclick="setStatusForOrder(\''.$this->CustomFunctions->encode($this->Html->url('/', true)).'\', \'pedidos/edit/\', \''.$this->CustomFunctions->encode($data[$i]['Pedido']['id_pedido']).'\', \''.$this->CustomFunctions->encode(4).'\', '.$this->Session->read('Auth.User.Cliente.'.$indexUser.'.ncli').',  $(this), event, \''.$this->CustomFunctions->encode(4).'\');"' : NULL; ?> ><i class="icon-ok"></i></a>
                      		<?php } ?>
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
          
          <?php
					if(count($data) > 0 )
					{
						for($i = 0; $i < count($data); $i++) :		
						  $w_index = (count($data[$i]['Webtracking'])-1);  
		   ?>
           
                 <div id="detalle-<?php echo $this->CustomFunctions->encode($data[$i]['Pedido']['id_pedido']); ?>" class="win-detalle">
                   <table  cellpadding="0" cellspacing="0" width="100%">
						<thead>
                               <tr class="info">
                              	<th colspan="3" class="left">
                                		<span>Info. del Pedido</span> 
                                </th>
 
                              	<th colspan="4" class="right">
                                		<span>Orden #:</span> 
                                        <span><?php echo $data[$i]['Pedido']['id_pedido']; ?></span>
                                </th>
                              </tr>
                        </thead> 
                        <tbody>
                        	<tr class="delimiter">
                            	<td  class="cell_caption" width="140">SOLICITANTE:</td>
                                <td   width="340"><?php echo $this->CustomFunctions->cleanFields($data[$i]['Solicitante']['apellido'].' '.$data[$i]['Solicitante']['nombre'], false); ?></td>
                            	<td  class="cell_caption" width="140">APROBADO POR:</td>
                                <td   width="340"><?php echo (is_null($data[$i]['Aprobador']['id']))?'Aún por Procesar':$this->CustomFunctions->cleanFields($data[$i]['Aprobador']['apellido'].' '.$data[$i]['Aprobador']['nombre'], false); ?></td>
                            </tr>
							<tr class="delimiter">
                            	<td  class="cell_caption" width="160">FECHA DE SOLICITUD:</td>
                                <td   width="320"><?php echo $data[$i]['Pedido']['f_solicitud']; ?></td>
                            	<td  class="cell_caption" width="160">FECHA  DE PROCESO:</td>
                                <td   width="320"><?php
								 	if(!count($data[$i]['Webtracking']) > 0 )	
										echo ($data[$i]['Pedido']['fecha_proceso']==NULL)?'Aún por Procesar':$data[$i]['Pedido']['fecha_proceso']; 
								 	 else
									 	echo  $data[$i]['Webtracking'][$w_index]['fecha'];
									 
								 ?></td>
                            </tr> 
		                   	<tr class="delimiter">
                            	<td class="cell_caption" width="140">ESTADO:</td>
                                <td  width="340"><?php echo $this->CustomFunctions->cleanFields($data[$i]['Subzona']['Zona']['nombre'], false);  ?></td>
                            	<td class="cell_caption" width="140">CIUDAD (DESTINO):</td>
                                <td width="340"><?php echo $this->CustomFunctions->cleanFields($data[$i]['Subzona']['nombre'], false);  ?></td>
                            </tr> 
		                   	<tr class="delimiter">
                            	<td class="cell_caption" width="140">TIPO DE ENVIO:</td>
                                <td width="340" ><?php echo $data[$i]['Pedido']['tipo_viaje'];  ?></td>
                            	<td class="cell_caption" width="140">ESTATUS:</td>
                                <td width="340"  style="color:#FFF;" class="<?php 
									if(!count($data[$i]['Webtracking']) > 0 )
									{
										if($data[$i]['Pedido']['estado']== 1) 
											echo 'label-yellow'; 
										elseif($data[$i]['Pedido']['estado']== 2) 
											echo 'label-success'; 
										elseif($data[$i]['Pedido']['estado']== 3) 
											echo 'label-important'; else echo '  label-info';    
									}else
										echo $this->CustomFunctions->getEstatusTagColor($data[$i]['Webtracking'], $w_index);
									?>"><?php 
										if(!count($data[$i]['Webtracking']) > 0 )
											echo $data[$i]['Estatu']['descripcion'];  
										else
											echo $this->CustomFunctions->getWebtrackingStatus($data[$i]['Webtracking'][$w_index]['stat_pedido'],$data[$i]['Webtracking'][$w_index]['stat_transf']);	
									?>
                                    </td>
                            </tr> 
							<tr class="delimiter">
                            	<td class="cell_caption" colspan="4">DESTINATARIO:</td>
                            </tr>
                        	<tr>
                            	<td colspan="4">
								<?php 
											$destinatario  = $data[$i]['Destinatario']['nombre'];
											if($data[$i]['Destinatario']['direccion']!='')
												$destinatario.='/'.$data[$i]['Destinatario']['direccion'];	
											if($data[$i]['Destinatario']['telefonos']!='')
												$destinatario.='/'.$data[$i]['Destinatario']['telefonos'];	
											echo $this->CustomFunctions->cleanFields($destinatario, false) 
									?>
                                    </td>
                            </tr>
                        	<tr class="delimiter">
                            	<td class="cell_caption" colspan="4">OBSERVACIONES:</td>
                            </tr>
                        	<tr>
                            	<td colspan="4"><?php 
									$r_data = $this->CustomFunctions->cleanFields($data[$i]['Pedido']['observaciones'], false);  
									echo ((strlen($r_data)==0)||(strlen($r_data)==1))?'NO DISPONE':$r_data;
									?></td>
                            </tr>                                                                              	
                        </tbody>                  
                   </table>
                    
                    <table cellpadding="0" cellspacing="0" width="100%">
                    	<thead>
                              <tr class="info">
                              	<th colspan="<?php echo $header_l; ?>" class="left">
                                		<span>Productos Asociados</span> 
                                </th>
                                <th colspan="<?php echo $header_r; ?>" class="right" >
                                		<span>Artículos:</span> 
                                        <span><?php echo count($data[$i]['PedidosProducto']); ?></span>
                                </th>
                              </tr>
                              <tr>
                                <th> # </th>
                                <th> Descripción </th>
                                <th> Estado </th>
                               <?php if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd1')) { ?> 
                                <th> <?php 
											echo ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd1'),'utf-8')); ?> </th>
                                <?php } ?>
                               <?php if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd2')) { ?> 
                                <th> <?php 
											echo ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd2'),'utf-8')); ?> </th>
                                <?php } ?>
                               <?php if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd3')) { ?> 
                                <th> <?php 
											echo ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd3'),'utf-8')); ?> </th>
                                <?php } ?>
                               <?php if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd4')) { ?> 
                                <th> <?php 
											echo ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd4'),'utf-8')); ?> </th>
                                <?php } ?>
                               <?php if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd5')) { ?> 
                                <th> <?php 
											echo ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd5'),'utf-8')); ?> </th>
                                <?php } ?>

                                <th> Cantidad </th>
                                <th width="100"> Accion </th>
                              </tr>
                        </thead>
                    	<tbody>
 <?php echo $this->Form->create('Pedido', array('action' => 'update_pedido', 'id'=>'PedidoUpdatePedidoForm'.$i));   ?>
                    <input type="hidden" name="data[PedidosProducto][id_cliente]" id="PedidosProductoIdCliente<?php echo $i;?>"  />
                    <input type="hidden" name="data[PedidosProducto][id_pedido]" id="PedidosProductoIdPedido<?php echo $i;?>" />
                    <input type="hidden" name="data[PedidosProducto][id_solicitante]" id="PedidosProductoIdSolicitante<?php echo $i;?>"  />
                    <input type="hidden" name="data[PedidosProducto][id_producto]" id="PedidosProductoProductoCodi<?php echo $i;?>" />
					<input type="hidden" name="data[PedidosProducto][FormAccion]" id="PedidosProductoFormAccion<?php echo $i;?>" value="1" />
                    <input type="hidden" name="data[PedidosProducto][index]" id="PedidosProductoIndex<?php echo $i;?>"	 />
                    <input type="hidden" name="data[PedidosProducto][id]" id="PedidosProductoId<?php echo $i;?>"	 />
                        
						<?php
						    $color = '#F5F5F5';
							for($j = 0; $j < count($data[$i]['PedidosProducto']); $j++):
								$color = ($color == '#EBEBEB' )?'#F5F5F5':'#EBEBEB';
								?>
                                		<tr style="background:<?php echo $color;?>" id="<?php echo $this->CustomFunctions->encode('tr'.$data[$i]['PedidosProducto'][$j]['id']); ?>">
                                        	<td><?php echo $data[$i]['PedidosProducto'][$j]['Producto']['codi']; ?></td>
                                        	<td><?php echo $data[$i]['PedidosProducto'][$j]['Producto']['nombre']; ?></td>
                                            <td><span class="label <?php  if ($data[$i]['PedidosProducto'][$j]['nestprd'] == 1) echo 'label-success'; elseif($data[$i]['PedidosProducto'][$j]['nestprd'] == 2) echo 'label-yellow'; elseif($data[$i]['PedidosProducto'][$j]['nestprd'] == 3) echo 'label-warning'; elseif($data[$i]['PedidosProducto'][$j]['nestprd'] == 4) echo 'label-lime'; ?>" title="<?php if ($data[$i]['PedidosProducto'][$j]['nestprd'] == 1) echo 'Disponible'; elseif($data[$i]['PedidosProducto'][$j]['nestprd'] == 2) echo 'Retenido'; elseif($data[$i]['PedidosProducto'][$j]['nestprd'] == 3) echo 'Dañado(s)'; elseif($data[$i]['PedidosProducto'][$j]['nestprd'] == 4) echo 'Retenido Promocional'; ?>"><?php if ($data[$i]['PedidosProducto'][$j]['nestprd'] == 1) echo 'Disponible'; elseif($data[$i]['PedidosProducto'][$j]['nestprd'] == 2) echo 'Retenido'; elseif($data[$i]['PedidosProducto'][$j]['nestprd'] == 3) echo 'Dañado(s)'; elseif($data[$i]['PedidosProducto'][$j]['nestprd'] == 4) echo 'Promocional'; ?></span></td>
                                        	 <?php if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd1')) { ?> 
                                            <td><?php echo (empty($data[$i]['PedidosProducto'][$j]['Producto']['Clasprd1']['descripcion']))?'N/A':$data[$i]['PedidosProducto'][$j]['Producto']['Clasprd1']['descripcion']; ?></td>
                                        	<?php }  if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd2')) { ?>
                                            <td><?php echo (empty($data[$i]['PedidosProducto'][$j]['Producto']['Clasprd2']['descripcion']))?'N/A':$data[$i]['PedidosProducto'][$j]['Producto']['Clasprd2']['descripcion']; ?></td>
                                        	<?php }  if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd3')) { ?>
                                            <td><?php echo (empty($data[$i]['PedidosProducto'][$j]['Producto']['Clasprd3']['descripcion']))?'N/A':$data[$i]['PedidosProducto'][$j]['Producto']['Clasprd3']['descripcion']; ?></td>
                                        	<?php }  if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd4')) { ?>
                                            <td><?php echo (empty($data[$i]['PedidosProducto'][$j]['Producto']['Clasprd4']['descripcion']))?'N/A':$data[$i]['PedidosProducto'][$j]['Producto']['Clasprd4']['descripcion']; ?></td>
                                        	<?php }  if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd5')) { ?>
                                            <td><?php echo (empty($data[$i]['PedidosProducto'][$j]['Producto']['Clasprd5']['descripcion']))?'N/A':$data[$i]['PedidosProducto'][$j]['Producto']['Clasprd5']['descripcion']; ?></td>
											<?php } ?>
                                            <td><input type="text" name="data[PedidosProducto][cantidad][<?php echo $j; ?>]" id="<?php echo $this->CustomFunctions->encode($data[$i]['PedidosProducto'][$j]['id']); ?>" value="<?php echo $data[$i]['PedidosProducto'][$j]['cantidad']; ?>" <?php echo ($data[$i]['Pedido']['estado']!=1)? 'readonly="readonly"':NULL;  ?> /></td>
                                            <td width="100">
                                            	 <div class="btn-group">
                                                    <?php if($data[$i]['Pedido']['estado']== 1)
														  		{
														  ?>
                                                             <a class="btn btn-circle  btn-inverse  "  href="#" title="Editar cantidad solicitada" onclick="UpdatePedido('<?php  echo  $this->CustomFunctions->encode($this->Html->url('/', true)); ?>', '<?php echo  $this->CustomFunctions->encode( $data[$i]['PedidosProducto'][$j]['producto_id']); ?>','<?php echo  $this->CustomFunctions->encode( $data[$i]['PedidosProducto'][$j]['id']); ?>',$('<?php echo '#'.$this->CustomFunctions->encode($data[$i]['PedidosProducto'][$j]['id'])?>'), '<?php echo $data[$i]['PedidosProducto'][$j]['nestprd']; ?>', <?php echo $data[$i]['PedidosProducto'][$j]['id']; ?>,<?php echo $j; ?>, '<?php echo $this->CustomFunctions->encode($data[$i]['PedidosProducto'][$j]['Producto']['codi']); ?>','<?php echo $this->CustomFunctions->encode($data[$i]['Pedido']['cliente_id']); ?>','<?php echo $this->CustomFunctions->encode($data[$i]['Pedido']['id_pedido']); ?>','<?php echo $this->CustomFunctions->encode($data[$i]['Solicitante']['id']); ?>',<?php echo $i; ?>, 1, event);"><i class="icon-edit"></i></a>
															 <a class="btn btn-circle  btn-inverse  "  href="#" title="Eliminar producto"  onclick="return CancelPedido('<?php  echo  $this->CustomFunctions->encode($this->Html->url('/', true)); ?>', '<?php echo  $this->CustomFunctions->encode( $data[$i]['Pedido']['id_pedido']); ?>','<?php echo $this->CustomFunctions->encode($data[$i]['PedidosProducto'][$j]['id'])?>', $('<?php echo '#'.$this->CustomFunctions->encode('tr'.$data[$i]['PedidosProducto'][$j]['id'])?>'), <?php echo $data[$i]['PedidosProducto'][$j]['id']; ?>,<?php echo $j; ?>, '<?php echo $this->CustomFunctions->encode($data[$i]['PedidosProducto'][$j]['Producto']['codi']); ?>','<?php echo $this->CustomFunctions->encode($data[$i]['Pedido']['cliente_id']); ?>','<?php echo $this->CustomFunctions->encode($data[$i]['Pedido']['id_pedido']); ?>','<?php echo $this->CustomFunctions->encode($data[$i]['Solicitante']['id']); ?>',<?php echo $i; ?>, 2, event);"><i class="icon-minus-sign"></i></a>
                                                 		<?php }else{ ?>
                                                             <a class="btn btn-circle  btn-inverse  "  href="#" title="Editar cantidad solicitada - Función no disponible" ><i class="icon-edit"></i></a>
															 <a class="btn btn-circle  btn-inverse  "  href="#" title="Eliminar producto - Función no disponible"  ><i class="icon-minus-sign"></i></a>
                                                        <?php } ?>
                                                 </div>
                                            </td>
                                         </tr>
                                        
                                <?php
						   	endfor;
						echo $this->Form->end(); 
						?>
                        
                        </tbody>
                    </table>

                    <table cellpadding="0" cellspacing="0" width="100%">
                    	<thead>
                              <tr class="info">
                              	<th colspan="4"  class="left">
                                		INFORMACIÓN ADICIONAL
                                </th>
                              </tr>
							</thead> 
                       <tbody>
                       <?php if(count($data[$i]['Clifactura']) > 0)
					   		{
					   ?>
                        	<tr class="delimiter">
                            	<td  class="cell_caption" width="140">ENVIADO:</td>
                                <td   width="340"><?php echo $data[$i]['Clifactura']['FECHADES']; ?></td>
                            	<td  class="cell_caption" width="140">BULTOS / PESO:</td>
                                <td   width="340"><?php echo $data[$i]['Clifactura']['BULTOS'].' / '.$data[$i]['Clifactura']['PESO'].' (KG)'; ?></td>
                            </tr>
                           	<tr class="delimiter">
                            	<td class="cell_caption" colspan="4">OBSERVACIONES:</td>
                            </tr>
                        	<tr>
                            	<td colspan="4"><?php 
									$r_data = $this->CustomFunctions->cleanFields($data[$i]['Clifactura']['Observaciones'], false);  
									echo ((strlen($r_data)==0)||(strlen($r_data)==1))?'NO DISPONE':$r_data;
									?></td>
                            </tr>                                                                              	
							<tr class="delimiter">
                            	<td class="cell_caption" colspan="4">NOVEDADES:</td>
                            </tr>
                        	<tr>
                            	<td colspan="4" ><?php 
									$r_data = $this->CustomFunctions->cleanFields($data[$i]['Clifactura']['novedades'], false);  
									echo ((strlen($r_data)==0)||(strlen($r_data)==1))?'NO DISPONE':$r_data;
									?></td>
                            </tr>       
								<?php	if(($this->Session->read('Auth.User.rol_id')==2)||($this->Session->read('Auth.User.rol_id')==3))
										{
								?>
							<tr>
                            	<td colspan="4" >
                                		<?php
											if(!file_exists(realpath(realpath('files'.DS.'pedidos').DS.$data[$i]['Pedido']['id_pedido'].'.pdf')))
											{
												echo $this->Form->create(array(
													'type' => 'file',
													'url' =>'/pedidos/do_upload/'.$this->CustomFunctions->encode($data[$i]['Pedido']['id_pedido']),
													'inputDefaults' => array(
														'div' => "form-element"
													)
												));										
												echo $this->Form->input('file', array(
																			'label'=>'Archivo',
																			'type'=>'file'
																		)
												);
												echo $this->Form->end('Cargar');
											}else
														echo  $this->Html->tag('a', 'Descargar Nota de Entrega', array('href' => '/files/pedidos/'.$data[$i]['Pedido']['id_pedido'].'.pdf'));
										
										 ?>
                                 </td>
                            </tr>                                      
                            <?php 
									}else{
									if(file_exists(realpath(realpath('files'.DS.'pedidos').DS.$data[$i]['Pedido']['id_pedido'].'.pdf'))){
									?>
					<tr>
                            			<td colspan="4" ><?php echo  $this->Html->tag('a', 'Descargar Nota de Entrega', array('href' => '/files/pedidos/'.$data[$i])); ?></td>
									</tr>
									<?php
										}
									}
							}else {?>
                            <tr>
                            	<td colspan="4" >Información no disponible.-</td>
                            </tr>                                      
                            <?php }?>
                       </tbody>                        	
                    </table>

                    
                    <table cellpadding="0" cellspacing="0" width="100%">
                    	<thead>
                              <tr class="info">
                              	<th colspan="3"  class="left">
                                		Detalle del Despacho
                                </th>
                              </tr>
                              <tr>
                                <th> FECHA DE EVENTO </th>
                                <th> ESTATUS </th>
                                <th> UBICACIÓN </th>
                              </tr>
							</thead> 
                       <tbody>
                       	<?php 
						 if(count($data[$i]['Webtracking']) > 0)
						 {
						    $color = '#F5F5F5';
							for($k = 0; $k < count($data[$i]['Webtracking']); $k++){
								$color = ($color == '#EBEBEB' )?'#F5F5F5':'#EBEBEB';
						?>
                                		<tr style="background:<?php echo $color;?>">
                                        	<td><?php echo $data[$i]['Webtracking'][$k]['fecha']; ?></td>
                                        	<td><?php echo $this->CustomFunctions->getWebtrackingStatus($data[$i]['Webtracking'][$k]['stat_pedido'],$data[$i]['Webtracking'][$k]['stat_transf']); ?></td>
                                        	<td><?php echo $this->CustomFunctions->getWebtrackingUbicacion($data[$i]['Webtracking'][$k]['stat_pedido'],$data[$i]['Webtracking'][$k]['stat_transf'], $data[$i]['Client']['nombre']); ?></td>
                                        </tr>
                        <?php } 
						 }else{
						?>
                            <tr>
                            	<td colspan="3" >Información no disponible.-</td>
                            </tr>                                      
                        <?php
						 }
						?>
                       </tbody>                        	
                    </table>
                 </div>
          <?php 
		  		endfor;
					}
		  ?>
        </div>
      </div>
    </div>
  </div>
</div>