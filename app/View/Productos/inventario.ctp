<?php
echo $this->Html->css(
					array(
								'jquery.fancybox',
								'validationEngine.jquery',
								'jquery.ui.base',
								'jquery.ui.theme',
								'others'
							),
							 array('inline'=>false)
					);

echo $this->Html->script(
					array(
							'jquery.ui.core',
							'jquery.ui.widget',
							'jquery.ui.position',
							'jquery.ui.menu',
							'jquery.ui.autocomplete',
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
							'url/get_headers',
							'preventdoublesubmission.jquery.js',
							'jquery.custom.js'
						),
					 array('inline'=>false)	
				);
$this->Html->scriptBlock('
	$(document).ready(function(){
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
			SetPedidosObjects();
			
	
			
	});

', 
	 array('inline'=>false)
);
if(!$this->Session->read('Auth.User.copiar'))
{
$this->Html->scriptBlock('
	//below javascript is used for Disabling right-click on HTML page
	document.oncontextmenu=new Function("return false");//Disabling right-click
	//below javascript is used for Disabling text selection in web page
	document.onselectstart=new Function ("return false"); //Disabling text selection in web page
	if (window.sidebar){
		document.onmousedown=new Function("return false"); 
		document.onclick=new Function("return true") ; 
		//Disable Cut into HTML form using Javascript 
		document.oncut=new Function("return false"); 
		//Disable Copy into HTML form using Javascript 
		document.oncopy=new Function("return false"); 
		//Disable Paste into HTML form using Javascript  
		document.onpaste=new Function("return false"); 
	}
', 
 array('inline'=>false));

}
$JsonClasific =  array(
	'activo_clasprd1'=>$this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd1'),
	'nombre_clasprd1'=>ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd1'),'utf-8')),	
	'activo_clasprd2'=>$this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd2'),
	'nombre_clasprd2'=>ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd2'),'utf-8')),	
	'activo_clasprd3'=>$this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd3'),
	'nombre_clasprd3'=>ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd3'),'utf-8')),	
	'activo_clasprd4'=>$this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd4'),
	'nombre_clasprd4'=>ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd4'),'utf-8')),	
	'activo_clasprd5'=>$this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd5'),
	'nombre_clasprd5'=>ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd5'),'utf-8')),	
	'aprueba_pedidos'=>$this->Session->read('Auth.User.aprueba_pedidos'),
	'rol'=>$this->Session->read('Auth.User.rol_id')
);
$this->Html->addCrumb('Listado de Productos', '/users/add');
?>
<!-- INICIO DE TABLA DINÁMICA -->

<div class="row">
<div class="col-md-12">
  <div class="box">
    <div class="box-title">
      <h3><i class="icon-table"></i> Productos</h3>
    </div>
    <div class="box-content" >
      <div class="btn-toolbar pull-right clearfix">
        <div class="btn-group"> 
        <a class="btn btn-circle  btn-info  " title="Aplicar filtros a la búsqueda" onclick="onFilter($(this), 'cerrar',event);" href="#"><i class="icon-link"></i></a>
         <?php 
		 if(count($data) > 0)
		 {
		 	echo $this->Html->link('<i class="icon-file-text"></i>',array_merge(array('action'=>'reporte_pdf'),$arguments),array('class'=>'btn btn-circle  btn-info  ', 'target'=>'__blank', 'escape'=>false));			 
		 	echo $this->Html->link('<i class="icon-table"></i>',array_merge(array('action'=>'reporte_excel'),$arguments),array('class'=>'btn btn-circle  btn-info  ', 'escape'=>false));	  
		 }else{
		 	echo $this->Html->link('<i class="icon-file-text"></i>','#',array('class'=>'btn btn-circle  btn-info  ', 'title'=>'Sin data para emitir reporte', 'escape'=>false));			 
		 	echo $this->Html->link('<i class="icon-table"></i>','#',array('class'=>'btn btn-circle  btn-info  ', 'title'=>'Sin data para emitir reporte', 'escape'=>false));	  
		 }
		 if($this->Session->read('Auth.User.realiza_pedidos')) { 
		 ?>
	         <a class="btn btn-circle  btn-info  " title="Guardar pedido" onclick="showUpOrder($(this), 'cerrar',event);" href="#" ><i class="icon-save"></i></a> 
        <?php } ?>
         </div>
      </div>
      <br/>
      <br/>
      <div class="clearfix"></div>
 	
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
                    <td><?php echo $this->Form->input('Producto.ncli', array(
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
                  <?php 
				  
					}if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd1'))
					{
				  
				   ?>
                  <tr>
                  	<td  style="width:20%;text-align:left"><b><?php echo ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd1'),'utf-8')); ?></b></td>
                    <td><?php echo $this->Form->input('Producto.ClasPrd1', array(
											'label'=>false,
											'div'=>false,
											'options'=>$this->CustomFunctions->cleanFields($clasprd1, true),
											'empty'=>'[Todos]',
											'style'=>'width:100%'
										)
								);					
						?>
                        </td>
                  </tr>

                  <?php 
				  
					}if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd2'))
					{
				  
				   ?>
                  <tr>
                  	<td  style="width:20%;text-align:left"><b><?php echo ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd2'),'utf-8')); ?></b></td>
                    <td><?php echo $this->Form->input('Producto.ClasPrd2', array(
											'label'=>false,
											'div'=>false,
											'options'=>$this->CustomFunctions->cleanFields($clasprd2, true),
											'empty'=>'[Todos]',
											'style'=>'width:100%'
										)
								);					
						?>
                        </td>
                  </tr>
                  <?php } if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd3'))
								{
					 ?>
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b><?php   echo ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd3'),'utf-8')); ?></b></td>
                    <td><?php echo $this->Form->input('Producto.ClasPrd3', array(
											'label'=>false,
											'div'=>false,
											'options'=>$this->CustomFunctions->cleanFields($clasprd3, true),
											'empty'=>'[Todos]',
											'style'=>'width:100%'
										)
								);					
						?>
                        </td>
                  </tr>
                  <?php } if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd4'))
								{
					 ?>
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b><?php  echo ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd4'),'utf-8')); ?></b></td>
                    <td><?php echo $this->Form->input('Producto.ClasPrd4', array(
											'label'=>false,
											'div'=>false,
											'options'=>$this->CustomFunctions->cleanFields($clasprd4, true),
											'empty'=>'[Todos]',
											'style'=>'width:100%'
										)
								);					
						?>
                        </td>
                  </tr>
                  
                  <?php } if($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.activo_clasprd5'))
								{
					 ?>
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b><?php  echo ucwords(mb_strtolower($this->Session->read('Auth.User.Cliente.'.$indexUser.'.Denominacion.clasprd5'),'utf-8')); ?></b></td>
                    <td><?php echo $this->Form->input('Producto.ClasPrd5', array(
											'label'=>false,
											'div'=>false,
											'options'=>$this->CustomFunctions->cleanFields($clasprd5, true),
											'empty'=>'[Todos]',
											'style'=>'width:100%'
										)
								);					
						?>
                        </td>
                  </tr>
                  <?php } ?>
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b>Descripción</b></td>
                    <td><?php echo $this->Form->input('Producto.nombre', array(
											'label'=>false,
											'div'=>false,
											'style'=>'width:100%',
											'class'=>'validate[custom[onlyLetterNumber]]',
											'type'=>'text'
										)
								);					
						?>
                        </td>
                  </tr>
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b>Código</b></td>
                    <td><?php echo $this->Form->input('Producto.codi', array(
											'label'=>false,
											'div'=>false,
											'style'=>'width:100%',
											'class'=>'validate[custom[onlyLetterNumber]]',
											'type'=>'text'
										)
								);					
						?>
                        </td>
                  </tr>
                 <?php 	 
				 
				 
				 	if(($this->Session->read('Auth.User.rol_id')==1)&&($this->Session->read('Auth.User.aprueba_pedidos'))) { 
				 
				 ?> 
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b>Estatus</b></td>
                    <td><?php 
							echo $this->Form->input('Producto.nestprd', array(
											'label'=>false,
											'div'=>false,
											'style'=>'width:100%',
											'empty'=>'[Seleccionar]',
											'options'=>$estatus
										)
								);					
						?>
                        </td>
                  </tr>
                  <?php }elseif(($this->Session->read('Auth.User.rol_id')==2) || ($this->Session->read('Auth.User.rol_id')==3)){ ?>
                  
                      <tr>
                        <td align="left" style="width:20%;text-align:left"><b>Estatus</b></td>
                        <td><?php 
                                echo $this->Form->input('Producto.nestprd', array(
                                                'label'=>false,
                                                'div'=>false,
                                                'style'=>'width:100%',
                                                'empty'=>'[Seleccionar]',
                                                'options'=>$estatus
                                            )
                                    );					
                            ?>
                            </td>
                      </tr>
     
                  <?php } ?>
                  <tr>
                  	<td align="left" style="width:20%;text-align:left"><b>Rango de Movilización</b></td>
                    <td><?php echo $this->Form->input('Producto.rango', array(
											'label'=>false,
											'div'=>false,
											'options'=>array(91 =>'< 3 meses', 183=>'< 6 meses', 365=>'< 1 año', 1826=>'> 1 año'),
											'empty'=>'[Seleccionar]',
											'style'=>'width:100%'
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
 <?php if($this->Session->read('Auth.User.realiza_pedidos')) { ?>
       <div id="order-list">
        <div class="content">
			<div class="box bordered-box red-border" style="margin-bottom:0;">
            	<div class="box-header red-background">
                	<div class="box-title">
                    	<h3>Nueva orden de pedido</h3>
                    </div>
                </div>
                <div class="box-content box-no-padding">
                <?php	echo $this->Form->create('Pedido', array(
											'id' => 'OrderListForm',
											'url' => array('controller' => 'pedidos', 'action' => 'crear'),	
											'inputDefaults' => array(
												'label' => false,
												'div' => false
											)
						));
					?>	
                	<table id="order-list-container" class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                    	<thead>
                        	<tr>
                            	<th colspan="4" style="background:#ddd; color:#333; text-align:left">Datos del pedido</th>
                            </tr>
              				<!--<tr>
                            	<th style="vertical-align:middle;" rel="estado">Estado</th>
                                <th colspan="4">
                                	<?php
										/*echo $this->Form->input('Pedido.id_zona', array(
											'label'=>false,
											'class'=>false,
											'empty'=>'[Seleccionar]',
											'options'=>$zonas,
											'onchange'=>'loadList(\''.$this->CustomFunctions->encode($this->Html->url('/', true)).'\', \'productos/get_zonas/\', $(this).val(), '.$ncliLogedInn.', $(\'#PedidoIdSubzona\'))'
											)
										);*/									
									?>
                                </th>
                            </tr>
              				<tr>
                            	<th  style="vertical-align:middle;" rel="ciudad">Ciudad (Destino)</th>
                                <th colspan="4">
                                	<?php
										/*echo $this->Form->input('Pedido.id_subzona', array(
											'label'=>false,
											'class'=>false,
											'empty'=>'[Seleccionar]',
											'type'=>'select',
											'onchange'=>'loadList(\''.$this->CustomFunctions->encode($this->Html->url('/', true)).'\', \'productos/get_destinatarios/\', $(this).val(), '.$ncliLogedInn.', $(\'#PedidoIdDestinatario\'))'
											)
										);*/									
									?>
                                </th>
                            </tr>-->
              				<tr>
                            	<th  style="vertical-align:middle;" rel="destinatario">Destinatario</th>
                                <th colspan="4">
                                	<?php
										echo $this->Form->input('Pedido.iniciales_destinatario', array(
											 'label'=>false,
											 'class'=>false,
											 'empty'=>'[Seleccionar]',
											 'options'=>$iniciales_destinatarios,
											 'before'=>'<p>',
											 'after'=>'</p>',
											 'onchange'=>'CargaListaDestino(\''.$this->CustomFunctions->encode($this->Html->url('/', true)).'\', \'productos/get_destinatarios\', $(this).val(),'.$ncliLogedInn.',$(\'#PedidoIdDestinatario\'))'
											)
										);

										echo $this->Form->input('Pedido.id_destinatario', array(
											 'label'=>false,
											 'class'=>false,
											 'empty'=>'[Seleccionar]',
											 'options'=>array(),
											 'before'=>'<p>',
											 'after'=>'</p>',
											 'onchange'=>'loadInfo($(this))'
											)
										);	
										echo $this->Form->input('Pedido.id_subzona', array(
											'label'=>false,
											'class'=>false,
											'type'=>'hidden',
											)
										);	
										echo $this->Form->input('Pedido.cod_destino', array(
											'label'=>false,
											'class'=>false,
											'type'=>'hidden',
											)
										);																																											
									?>
                                </th>
                           
                             <tr>
                            	<th  style="vertical-align:middle;" rel="t_viaje">Tipo de Envío</th>
                                <th colspan="3">
                                	<?php
										echo $this->Form->input('Pedido.tipo_viaje', array(
											'label'=>false,
											'class'=>false,
											'empty'=>'[Seleccionar]',
											'options'=>array(1=>"Normal",2=>"Express")
											)
										);									
									?>
                                </th>
                            </tr> 
                             <tr>
                            	<th style="vertical-align:middle;">Observación</th>
                                <th colspan="3">
                                 <?php
										echo $this->Form->input('Pedido.observaciones', array(
											'label'=>false,
											'class'=>false,
											'cols'=>30,
											'rows'=>6,
											'type'=>'textarea'
											)
										);									
									?>

                                </th>
                             </tr>                                                       
                             <tr>
                             	<th colspan="4" style="background:#ddd; color:#333; text-align:left">Productos asociados a nueva orden</th>
                             </tr>
                             <tr>
                             	<th>Código</th>
                                <th>Descripción</th>
                                <th>Solicitados</th>
                                <th>Acciones</th>
                              </tr>
                          </thead>
                  <tbody> 
                  <tr rel="ini">
                  	<td colspan="4"> La lista no posee producto(s) añadido(s) hasta el momento.</td>
                  </tr>
                   
        			</tbody>
                    <tfoot>
                    <tr>
                  	<td colspan="4">
                  		<div class="btn-toolbar pull-right clearfix">
                        	<div class="btn-group"> 
                            	<a class="btn btn-circle  btn-danger  " title="Cancelar todas" href="#" onclick="cleanLocalStorage(1);" ><i class="icon-remove"></i></a> 
                                <a class="btn btn-circle  btn-success  " title="Confirmar pedido" href="#" onclick="return submitOrderListForm();" ><i class="icon-save"></i></a>
                            </div>
                        </div>
                       </td>
                     </tr>      
                    </tfoot>
              </table>
              		<?php echo $this->Form->end(); ?>
        	     </div>       
        	</div>
      	 </div>
      </div>
      <?php  } ?>      
      <div class="table-responsive" <?php echo (!count($data)> 0)?'style="height:1500px;"':NULL; ?>>
        <div class="scrollable-area"> 
          <!-- FIN DE TABLA DINÁMICA -->
   		<?php	echo $this->Session->flash(); ?>
		<div class="alert alert-info alert-dismissable">
                            <a class="close" data-dismiss="alert" href="#">×</a>
	                         <h4>
    	                        <i class="icon-info-sign"></i>
        	                    Info
            	              </h4>
                		       <?php echo $this->Paginator->counter('Página {:page} de {:pages}, Mostrando <b>{:current}</b> registros de un <b> total de {:count}</b>'); ?>
                               <div>
                               	<b>Nota:</b> <b><?php echo (count($actualizacion) > 0 )?'Estos datos fueron actualizados al '.$actualizacion['ArchivoCsv']['fecha']:'El inventario aún no posee registro de actualización'; ?></b>
                               </div>
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
          <table class="table table-hover table-striped" rel="inventario" style="margin-bottom:0;">
            <thead>
              <tr>
                <th> Código </th>
                <th> Descripción </th>
                <th> Imagen </th>
                <th> <span class=" " title="Disponible en Inventario">D. Inventario</span> </th>
                <th width="150"> <span class=" " title="Disponible para Pedidos">D. Por Solicitar</span> </th>
                <th width="150"> <span class=" " title="Cantidad a Solicitar">A Solicitar</span> </th>
                <th width="100"> Acciones </th>
              </tr>
            </thead>
            <tbody>
              <?php 
			  
					if(count($data) > 0 )
					{
					    for($i = 0; $i < count($data); $i++) :
							$disponible = $data[$i]['Existencia']['unidades'];
							if( (isset($data[$i]['PedidosProducto'])) && (count($data[$i]['PedidosProducto']) > 0)){
								 $disponible = $disponible - $this->CustomFunctions->getSumSolicitados($data[$i]['PedidosProducto'], $data[$i]['Existencia']['nestprd']);
							}
				?>
              <tr id="<?php echo $data[$i]['PConsolidado']['id'].$i; ?>">
                <td><?php echo $data[$i]['PConsolidado']['codi']; ?></td>
                <td style="text-align:left;" width="350"><span><?php echo ucfirst(strtolower($data[$i]['PConsolidado']['nombre'])); ?></span>
                  <p><b>Peso:</b> <?php echo $data[$i]['PConsolidado']['PESO']; ?> gr. <br /> <b>Volumen:</b> <?php echo str_replace('.',',',$data[$i]['PConsolidado']['VOLUMEN']); ?> m<sup>3</sup>.<br>
                    <b>Medidas:</b> <?php echo $data[$i]['PConsolidado']['Ancho']; ?> X <?php echo $data[$i]['PConsolidado']['Largo']; ?> X <?php echo $data[$i]['PConsolidado']['Profundidad']; ?> cm.<sup title="Ancho x Largo x Profundidad">anc. x lar. x Prof.</sup><br>
                    <b>Prensentación:</b> <?php echo (is_null($data[$i]['PConsolidado']['presentacion']))?'n/a':$data[$i]['PConsolidado']['presentacion']; ?>.</p></td>
                <td ><?php 
                            
                            echo $this->Html->link(
                                        $this->Html->image(
															'http://unocd.com/inventario/imagenes3pl/Fotosprd/'.$data[$i]['PConsolidado']['codi'].'.JPG', 
															array('style'=>'border:2px #ddd solid;', 'width'=>100, 
															'onError'=>'this.onerror=null;checkImageExists("http://unocd.com/inventario/imagenes3pl/Fotosprd/'.$data[$i]['PConsolidado']['codi'].'.jpg", $(this));')),
                                        					'http://unocd.com/inventario/imagenes3pl/Fotosprd/'.$data[$i]['PConsolidado']['codi'].'.JPG',
                                        array(
                                                'escapeTitle' => false, 
                                                'title' => $data[$i]['PConsolidado']['nombre'],
                                                'class'=>'fancybox'
                                            )
                                    );
                             ?></td>
                <td width="150"><span style="padding:.5em; display:inline-block; width:60px;"><?php echo (is_null($data[$i]['Existencia']['unidades']))?"0":$data[$i]['Existencia']['unidades']; ?></span><span class="label <?php  if ($data[$i]['Existencia']['nestprd'] == 1) echo 'label-success'; elseif($data[$i]['Existencia']['nestprd'] == 2) echo 'label-yellow'; elseif($data[$i]['Existencia']['nestprd'] == 3) echo 'label-warning'; elseif($data[$i]['Existencia']['nestprd'] == 4) echo 'label-lime'; ?>" title="<?php if ($data[$i]['Existencia']['nestprd'] == 1) echo 'Disponible'; elseif($data[$i]['Existencia']['nestprd'] == 2) echo 'Retenido'; elseif($data[$i]['Existencia']['nestprd'] == 3) echo 'Dañado(s)'; elseif($data[$i]['Existencia']['nestprd'] == 4) echo 'Retenido Promocional'; ?>"><?php if ($data[$i]['Existencia']['nestprd'] == 1) echo 'Disponible'; elseif($data[$i]['Existencia']['nestprd'] == 2) echo 'Retenido'; elseif($data[$i]['Existencia']['nestprd'] == 3) echo 'Dañado(s)'; elseif($data[$i]['Existencia']['nestprd'] == 4) echo 'Promocional'; ?></span></td>
                <td width="150"><span style="padding:.5em; width:60px;"><?php echo $disponible; ?></span><span class="label label-info">Por Solicitar</span></td>
                <?php if($this->Session->read('Auth.User.realiza_pedidos')) { ?>
                <td><?php echo $this->Form->input('Producto.'.$data[$i]['PConsolidado']['id'].$i, 
															array(
																		'label'=>false,
																		'div'=>false,
																		'disponible_solicitado'=>$disponible,
																		'disabled'=>(is_null($data[$i]['Existencia']['unidades'])?'disabled':NULL)
															)
											);
								?></td>
                            <?php }else{ ?>
				                <td><span class="label label-warning">Acción no Permitida</span></td>
                            <?php } ?>
                <td width="100"><div class="btn-group">
              	  	<?php if($this->Session->read('Auth.User.realiza_pedidos')) { 
					?>
                     	<a class="btn btn-circle btn-success  " title="Añadir pedido"  href="#" rel="<?php echo $data[$i]['PConsolidado']['id'].$i; ?>" onclick='addItemToNewPedido($("#Producto<?php echo $data[$i]['PConsolidado']['id'].$i; ?>"), $(this),<?php echo json_encode(array('id'=>$data[$i]['PConsolidado']['id'].$i,'codi'=>$data[$i]['PConsolidado']['codi'], 'nombre'=>ucfirst(strtolower($data[$i]['PConsolidado']['nombre'])), 'peso'=>$data[$i]['PConsolidado']['PESO'], 'volumen'=>$data[$i]['PConsolidado']['VOLUMEN'], 'ancho'=>$data[$i]['PConsolidado']['Ancho'], 'largo'=>$data[$i]['PConsolidado']['Largo'], 'profundidad'=>$data[$i]['PConsolidado']['Profundidad'], 'presentacion'=>(is_null($data[$i]['PConsolidado']['presentacion']))?'n/a':$data[$i]['PConsolidado']['presentacion'], 'nestprd'=>$data[$i]['Existencia']['nestprd'], 'ncli'=>$this->Session->read('Auth.User.Cliente.'.$indexUser.'.ncli')) ); ?>, event);'><i class="icon-plus"></i></a> 
                     <?php }else{ ?>
                     	<a class="btn btn-circle   " title="Añadir pedido - Producto no disponible para pedidos"  href="#" rel="<?php echo $data[$i]['PConsolidado']['id']; ?>"><i class="icon-plus"></i></a> 
					 <?php 
					 
								}
				 ?>
                     <a class="btn btn-circle btn-primary  " title="Detalle del producto" href="#" onClick='showDetails("<?php echo $data[$i]['PConsolidado']['id'].$i; ?>", "<?php echo $data[$i]['PConsolidado']['codi']; ?>", "<?php echo $this->CustomFunctions->encode($this->Html->url('/', true)); ?>",  "<?php echo $this->CustomFunctions->encode('productos/get_info/'); ?>","<?php echo $ncliLogedInn; ?>", <?php echo json_encode($JsonClasific); ?> , event)' info="<?php echo $data[$i]['PConsolidado']['id'].$i; ?>"><i class="icon-info"></i></a> </div></td>
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
          </table>          <div class="paging">
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