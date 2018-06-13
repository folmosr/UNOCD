<?php
if($module=='registre')
	{
?>
<div>
	<p>Estimado Sr(a).<b><?php echo $data['Usuario']['apellido'].' '.$data['Usuario']['nombre']; ?></b></p>
    <p>Su registro como <b>Usuario</b> al <b>Sistema de Administración de Inventario(UNOCD)</b> ha sido procesada satisfactoriamente. A continuación los datos de acceso:</p>
     <blockquote>
       <ul> 
        <li><b>Nombre de Usuario (Cuenta de Correo Eléctronico):</b> <?php echo $data['Usuario']['correo']; ?></li>
        <li><b>Contraseña:</b> <?php echo $data['Usuario']['rpass']; ?></li>
      </ul>
     </blockquote>   
	<p>
    	<b>NOTA:</b> 
         Una vez que entre al sistema podrá cambiar su contraseña en el panel de actualización de perfil.
    </p>
    <p>
    	<h3>
    		Antes de interactuar con el sistema le invitamos a ver <b>"Tutorial de Acceso al Sistema"</b> haciendo clic en el sisquiente enlace
    	 	<a href="http://unocd.com/unocdcom/tutoriales/acceso/index.html">Tutorial de Acceso al Sistema</a>
    	</h3>
    </p>
    
    <p>
    	<b>
        	<span><img src="cid:logo_unocd" border="0" width="190" height="95"  /></span>
            
            <br />
            <span>ALMACENADORA Y DISTRIBUIDORA UNOCD C.A.</span>
            <br />
            <span>Zona Industrial Paracotos, Edo. Miranda - Teléfonos: (0212) 3911431 - (0212)3911254</span>
         </b>
        <br />
        <a href="mailto:info@unocd.com"> info@unocd.com</a>
        <br />
        <a href="http://unocd.com/">UNOCD.COM</a>
    </p>
</div>
<?php }elseif($module=='recovery'){?>
<div>
	<p>Estimado Sr(a).<b><?php echo strtoupper($data['Usuario']['apellido']).' '.strtoupper($data['Usuario']['nombre']); ?></b></p>
    <h1>Recuperación de Datos</h1>
    <p>Para recuperar sus datos de inicio de sesión, ve a esta <?php echo $this->Html->link('Página',  array(
																																'controller' => 'usuarios',
																																'action' => 'secure',
																																'full_base' => true,
																																$this->CustomFunctions->encode($data['Usuario']['correo']),
																																$this->CustomFunctions->encode($data['Usuario']['id']),
    																														)
													); 
	?>.</p>
    <p>
    	<b>
        	<span><img src="cid:logo_unocd" border="0" width="190" height="95"  /></span>
            
            <br />
            <span>ALMACENADORA Y DISTRIBUIDORA UNOCD C.A.</span>
            <br />
            <span>Zona Industrial Paracotos, Edo. Miranda - Teléfonos: (0212) 3911431 - (0212)3911254</span>
         </b>
        <br />
        <a href="mailto:info@unocd.com"> info@unocd.com</a>
        <br />
        <a href="http://unocd.com/">UNOCD.COM</a>
    </p>
</div>
<?php
 }else{
	  ?>
<div>
  	<p>Estimado Sr(a).<b><?php echo strtoupper($data['Usuario']['apellido']).' '.strtoupper($data['Usuario']['nombre']); ?></b></p>
    <p>Has reestablecido tu contraseña satisfactoriamente.</p>
    <p><b>Recuerda:</b> Este Cambio fue realizado el <?php echo date('d/m/Y H:i a'); // Vuelve a la localidad regional anterior ?></p>
    <p>
    	<b>
        	<span><img src="cid:logo_unocd" border="0" width="190" height="95"  /></span>
            
            <br />
            <span>ALMACENADORA Y DISTRIBUIDORA UNOCD C.A.</span>
            <br />
            <span>Zona Industrial Paracotos, Edo. Miranda - Teléfonos: (0212) 3911431 - (0212)3911254</span>
         </b>
        <br />
        <a href="mailto:info@unocd.com"> info@unocd.com</a>
        <br />
        <a href="http://unocd.com/">UNOCD.COM</a>
    </p>
</div>
<?php } ?>