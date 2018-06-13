<?php if (is_null($modulo))
{ ?>
<div>
	<p>El usuario <b><?php  echo $this->Session->read('Auth.User.apellido').' '.$this->Session->read('Auth.User.nombre') ; ?></b>, ha <?php if($this->data['Estatu']['id']==1) echo 'generado'; elseif($this->data['Estatu']['id']==2) echo 'aprobado'; elseif($this->data['Estatu']['id']==3 ) echo 'cancelado'; elseif($this->data['Estatu']['id']==4) echo 'finalizado'; ?> la Orden # <b><?php echo $this->data['Pedido']['id_pedido']; ?></b></p>
    <p>
    	Comentario:
    	<br />
        Esta requisición se procesará en 4 días hábiles posterior a la fecha de aprobación.
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
<?php }elseif($modulo==1){ ?>
<div>
	<p>El usuario <b><?php  echo $this->Session->read('Auth.User.apellido').' '.$this->Session->read('Auth.User.nombre') ; ?></b>, ha actualizado la cantidad solicitada para el Producto con código: <?php echo $producto; ?></b></p>
    <p>
    	Comentario:
    	<br />
        Esta requisición se procesará en 4 días hábiles posterior a la fecha de aprobación.
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

<?php }elseif($modulo==2){ ?>
<div>
	<p>El usuario <b><?php  echo $this->Session->read('Auth.User.apellido').' '.$this->Session->read('Auth.User.nombre') ; ?></b>, ha eliminado la solicitud para el Producto con código: <?php echo $producto; ?></b></p>
    <p>
    	Comentario:
    	<br />
        Esta requisición se procesará en 4 días hábiles posterior a la fecha de aprobación.
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
<?php }else{ ?>
<div>
	<p>El usuario <b><?php  echo $this->Session->read('Auth.User.apellido').' '.$this->Session->read('Auth.User.nombre') ; ?></b>, ha eliminado el Pedido N° <?php echo $pedido; ?> y todas las solicitudes relacionadas con el mismo </b></p>
    
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