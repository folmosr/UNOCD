<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
echo $this->Html->meta(
    'favicon.ico',
    $this->Html->url('/', true).'favicon.ico',
    array('type' => 'icon')
);
?>

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>UNOCD, C.A.</title>
<meta name="description" content="Sistema de administración de Inventarios - UNOCD">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
echo $this->Html->css(
					array(
								'custom', 
								'bootstrap.min', 
								'font-awesome.min', 
								'flaty',
								'flaty-responsive',
								'others'
							)
					);

echo $this->Html->script(
					array(
							'jquery.min',
							'bootstrap.min', 
							'jquery.nicescroll.min',
							'jquery.cookie',
							'flaty'
						)
				);

echo $this->fetch('css');
echo $this->fetch('script');	
			
?>
<script>
$(".tutorial").fancybox({
    fitToView: false, // to show videos in their own size
    content: '<span></span>', // create temp content
    scrolling: 'no', // don't show scrolling bars in fancybox
    afterLoad: function () {
      // get dimensions from data attributes
      var $width = $(this.element).data('width'); 
      var $height = $(this.element).data('height');
      // replace temp content
      this.content = "<embed src='/unocdcom/js/jwplayer.swf?file=" + this.href + "&autostart=true&amp;wmode=opaque' type='application/x-shockwave-flash' width='" + $width + "' height='" + $height + "' wmode='opaque' allowfullscreen='true' allowscriptaccess='always'></embed>"; 
    }
  });
</script>
</head><body >
</div>
<div id="navbar" class="navbar">
  <button type="button" class="navbar-toggle navbar-btn collapsed" data-toggle="collapse" data-target="#sidebar"> <span class="icon-reorder"></span> </button>
  <a class="navbar-brand" href="#"> <small> <i class="icon-desktop"></i> UnoCD </small> </a>
  <ul class="nav flaty-nav pull-right">
      <?php if (count($this->Session->read('Auth.User')) > 0) {
				if($notificacionesVars)
				{
		?>
    <li class="hidden-xs"> <a data-toggle="dropdown" class="dropdown-toggle" href="#" onclick="marcar_notificacion('<?php echo $this->CustomFunctions->encode($this->Html->url('/', true).'notificaciones/notify/cliente:'.$ncliLogedInn); ?>');" > <i class="icon-bell-alt anim-swing"></i> <span class="badge badge-important" rel="notificacion"><?php echo count($notificacionesVars); ?></span> </a>
      <ul class="dropdown-navbar dropdown-menu">
        <li class="nav-header"> <i class="icon-warning-sign"></i> <?php echo count($notificacionesVars); ?> Notificaciones </li>
        <?php for($i = 0; $i < count($notificacionesVars); $i++){ ?>
        <li class="notify"> <a href="/unocd/pedidos/listar/pedido:<?php echo $this->CustomFunctions->encode($notificacionesVars[$i]['Pedido']['id_pedido']); ?>"> <i class="icon-comment blue"></i>
          <p>
		  	<?php echo preg_replace("/<.+?\s(.+?)>(.+?)<\/.+?>/is", "<b>$2</b>", $notificacionesVars[$i]['Notificacion']['msj']); ?>
            <br /> 
            <span style="font-weight:bold; color:#ccc; font-size:10px">Visto hace <span style="color:#ccc"><?php echo $this->CustomFunctions->secs_to_h($notificacionesVars[$i]['Notificacion']['creacion']); ?></span></span>
           </p>
          </a></li>
        <?php } ?>
        <li class="more"> <a href="/unocd/notificaciones/listar/cliente:<?php echo $this->CustomFunctions->encode($ncliLogedInn);?>">Ver m&aacute;s</a> </li>
      </ul>
    </li>
    <?php  
		}else{
				?>
    <li class="hidden-xs"> <a   href="#" > <i class="icon-bell-alt"></i> <span class="badge badge-alert"  rel="notificacion">0</span> </a></li>
    <?php  }
	} ?>
  <!--  -->    
    <?php if (count($this->Session->read('Auth.User')) > 0) {?>
    <li class="user-profile"> <a data-toggle="dropdown" href="#" class="user-menu dropdown-toggle"> <span class="hidden-sm" id="user_info"> <?php echo $this->Session->read('Auth.User.apellido').' '.$this->Session->read('Auth.User.nombre') ;?> </span> <i class="icon-caret-down"></i> </a>
      <ul class="dropdown-menu dropdown-navbar" id="user_menu">
        <li class="nav-header"> </li>
        <li> <a href="/unocdcom/usuarios/profile/"> <i class="icon-user"></i> Editar perfil </a> </li>
        <!--<li> <a href="#"> <i class="icon-question"></i> Ayuda </a> </li>-->
        <li class="divider visible-sm"></li>
        <!--<li class="visible-sm"> <a href="#"> <i class="icon-tasks"></i> Tasks <span class="badge badge-warning">4</span> </a> </li>
        <li class="visible-sm"> <a href="#"> <i class="icon-bell-alt"></i> Notifications <span class="badge badge-important">8</span> </a> </li>
        <li class="visible-sm"> <a href="#"> <i class="icon-envelope"></i> Messages <span class="badge badge-success">5</span> </a> </li>
        <li class="divider"></li>-->
        <li> <a href="/unocdcom/usuarios/logout/"> <i class="icon-off"></i> Cerrar Sesión </a> </li>
      </ul>
    </li>
    <?php } ?>
  </ul>
</div>
<div class="container" id="main-container">
  <div id="sidebar" class="navbar-collapse collapse">
  <?php   if (count($this->Session->read('Auth.User')) > 0) { ?>
    <ul class="nav nav-list">
      <li> </li>
      <li <?php echo ($this->params['controller'] == 'productos')?'class="active"':NULL; ?> > <a href="/unocdcom/productos/inventario/"> <i class="icon-dashboard"></i> <span>Inventario</span> </a> </li>
      <li <?php echo ($this->params['controller'] == 'pedidos')?'class="active"':NULL; ?>> <a href="/unocdcom/pedidos/listar/"> <i class="icon-cloud-upload"></i> <span>Pedidos</span> </a> </li>
<?php if ($this->Session->read('Auth.User.rol_id') == 1) { ?>
<li> <a href="#" class="dropdown-toggle"> <i class="icon-info"></i> <span>Ayuda</span> <b class="arrow icon-angle-right"></b> </a>
        <ul class="submenu">
           <li> <a href="/unocdcom/videos/solicitud_de_pedidos.mp4" class="tutorial" data-width="800" data-height="520">Solicitud Pedidos</a> </li> 
          <li> <a href="/unocdcom/videos/Filtro_de_Busqueda.mp4" class="tutorial" data-width="800" data-height="520">Filtro de B&uacute;squeda</a> </li> 
          <li> <a href="/unocdcom/videos/descarga_de_informacion.mp4" class="tutorial" data-width="800" data-height="520">Descarga de Informaci&oacute;n</a> </li> 
          <li> <a href="/unocdcom/videos/verificacion_pedido.mp4" class="tutorial" data-width="800" data-height="520">Seguimiento Pedido</a> </li> 
        </ul>
      </li>          
      <?php } if(($this->Session->read('Auth.User.rol_id') == 2)||($this->Session->read('Auth.User.rol_id') == 3)) { ?>
 <li> <a href="#" class="dropdown-toggle"> <i class="icon-file-text"></i> <span>Reportes</span> <b class="arrow icon-angle-right"></b> </a>
        <ul class="submenu">
          <li> <?php echo $this->Html->link('Productos sin Foto','/administradores/reporte_nofoto/');?>  </li>
        </ul>
      </li>         
      <li > <a href="#" class="dropdown-toggle"> <i class="icon-edit"></i> <span>Administrar</span> <b class="arrow icon-angle-right"></b> </a>
        <ul class="submenu">
          <li> <?php echo $this->Html->link('Clientes','/clientes/crear/');?>  </li>
  	<li class="active"> <a href="#" class="dropdown-toggle"> <i class="icon-edit"></i> <span>Usuarios</span> <b class="arrow icon-angle-right"></b> </a>
       
        <ul class="submenu">
          <li> <?php echo $this->Html->link('Crear Usuarios','/usuarios/crear/');?>  </li>
          <li> <?php echo $this->Html->link('Gestionar Usuario','/usuarios/listar/');?>  </li>
        </ul>
      </li>  
   
          
	  <?php } if($this->Session->read('Auth.User.rol_id') == 2) { ?>
      
	  <li class="active"> <a href="#" class="dropdown-toggle"> <i class="icon-edit"></i> <span>Coordinadores</span> <b class="arrow icon-angle-right"></b> </a>
        <ul class="submenu">
          <li> <?php echo $this->Html->link('Crear Coordinador','/coordinadores/crear/');?>  </li>
          <li> <?php echo $this->Html->link('Gestionar Coordinador','/coordinadores/listar/');?>  </li>
        </ul>
      </li>
      <li class="active"> <a href="#" class="dropdown-toggle"> <i class="icon-edit"></i> <span>Administradores</span> <b class="arrow icon-angle-right"></b> </a>
        <ul class="submenu">
          <li> <?php echo $this->Html->link('Crear Administrador','/administradores/crear/');?>  </li>
          <li> <?php echo $this->Html->link('Gestionar Administrador','/administradores/listar/');?>  </li>
        </ul>
      </li>      
        
         <?php } ?> 
        </ul>
      </li>
      <?php  if(($this->Session->read('Auth.User.rol_id') == 2)||($this->Session->read('Auth.User.rol_id') == 3)) {  ?>
         <li> <a href="#" class="dropdown-toggle"> <i class="icon-info"></i> <span>Ayuda</span> <b class="arrow icon-angle-right"></b> </a>
        <ul class="submenu">
           <li> <a href="/unocdcom/videos/solicitud_de_pedidos.mp4" class="tutorial" data-width="800" data-height="520">Solicitud Pedidos</a> </li> 
           <li> <a href="/unocdcom/videos/Filtro_de_Busqueda.mp4" class="tutorial" data-width="800" data-height="520">Filtro de B&uacute;squeda</a> </li> 
           <li> <a href="/unocdcom/videos/descarga_de_informacion.mp4" class="tutorial" data-width="800" data-height="520">Descarga de Informaci&oacute;n</a> </li> 
           <li> <a href="/unocdcom/videos/verificacion_pedido.mp4" class="tutorial" data-width="800" data-height="520">Seguimiento Pedido</a> </li> 
        </ul>
      </li>
      <?php } ?>    
        </ul>
      </li>
    </ul>
    <?php } ?>
    <div id="sidebar-collapse" class="visible-lg"> <i class="icon-double-angle-left"></i> </div>
  </div>
  <div id="main-content">
    <div class="page-title">
      <div>
        <h1><i class="icon-file-alt"></i><?php echo (isset($title_for_mod))?$title_for_mod:NULL; ?></h1>
        <h4><?php echo (isset($subtitle_for_mod))?$subtitle_for_mod:NULL; ?></h4>
      </div>
    </div>
    <div id="breadcrumbs">
      <ul class="breadcrumb">
        <?php
				echo $this->Html->getCrumbs(' > ', array(
					'text' => '<i class="icon-home"></i><span>Home</span>',
					'url' => array('controller' => 'pages', 'action' => 'display', 'home'),
					'escape' => false
				));        
		?>
      </ul>
    </div>
    <?php echo $this->fetch('content'); ?> <?php echo $this->element('sql_dump'); ?>
    <footer>
      <p>UnoCD 2013 - Todos los derechos reservados.</p>
    </footer>
    <a id="btn-scrollup" class="btn btn-circle btn-lg" href="#"><i class="icon-chevron-up"></i></a> </div>
</div>

</html>