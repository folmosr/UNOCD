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
					'Tus Notificaciones', 
					'/notificaciones/listar'
);
?>
<!-- INICIO DE TABLA DINÁMICA -->

<div class="row">
<div class="col-md-12">
  <div class="box">
    <div class="box-title">
      <h3><i class="icon-table"></i> Notificaciones</h3>
    </div>
    <div class="box-content">
      <div class="clearfix"></div>
      <div class="table-responsive" style="height:1500px;">
        <div class="scrollable-area"> 
          <!-- FIN DE TABLA DINÁMICA -->
          <table class="table table-hover table-striped">
            <tbody>
              <?php 
					if(count($data) > 0 )
					{
						for($i = 0; $i < count($data); $i++) :
				?>
				<tr>
					<td style="text-align:left;">
						<a href="#" class="btn btn-circle btn-info"> <i class="icon-comment white"></i></a>
                        <span style="padding:1.5em;"><?php echo $data[$i]['Notificacion']['msj']; ?></span>
                    </td>                    
                    <td style="vertical-align:bottom; text-align:right;"> <span style="font-weight:bold; color:#333; font-size:10px">Visto por <span style="color:#999"><?php echo  ucwords(strtolower($data[$i]['Usuario']['full_name'])); ?></span> hace <span style="color:#999"><?php echo $this->CustomFunctions->secs_to_h($data[$i]['Notificacion']['creacion']); ?></span></span></td>
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
        </div>
      </div>
    </div>
  </div>
</div>