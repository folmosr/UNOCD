/*
* Funciones generales
*/
// convert an ascii string to its hex representation
function AsciiToHex(ascii)
{
  var hex = '';
  for(i = 0; i < strlen(ascii); i++)
         hex+= str_pad(base_convert(ord(ascii[i]), 10, 16), 2, '0', 'STR_PAD_LEFT');
      return hex;
 }

// convert a hex string to ascii, prepend with '0' if input is not an even number
// of characters in length   
function HexToAscii(hex)
{
      var ascii = ''; 
      if (strlen(hex) % 2 == 1)
         hex = '0'+hex;
      
	  for(i = 0; i < strlen(hex); i += 2)
         ascii+= chr(base_convert(substr(hex, i, 2), 16, 10));
   
      return ascii;
 }

/* Ajax config */
$.ajaxSetup({
           type: "GET",
           dataType: "xml",
           contentType: "application/x-www-form-urlencoded",
           timeout:10000,
           async:true
});


/**
*
*/
function setStatusForOrder(base_url, controller, id_pedido, id_status, cliente, trigger, e, sAux)
{
	 
	 var css    = trigger.attr('class');
	 var action = trigger.attr('onclick');
	 var label = $('#iElement'+id_pedido).parent();
	 var labelText = $('#iElement'+id_pedido).text();
	 var labelClass = $('#iElement'+id_pedido).attr('class');
	 var cTrigger = $('#cTrigger'+id_pedido);
	 var aTrigger = $('#aTrigger'+id_pedido); 
	 var fTrigger = $('#fTrigger'+id_pedido);
	 e.preventDefault();
	 $.ajax({
			  url : base64_decode(HexToAscii(base_url))+controller+id_pedido+'/'+id_status+'/'+cliente,
              dataType: "text",
			  beforeSend:function(){
					label.html('<img src="'+base64_decode(HexToAscii(base_url))+'img/loader.gif" border="0" /><p style="text-align:center"><b>Cargando...</b></p>');
					cTrigger.removeClass().addClass('btn btn-circle');
					aTrigger.removeClass().addClass('btn btn-circle');
					fTrigger.removeClass().addClass('btn btn-circle');
					cTrigger.removeAttr('title onclick');
					aTrigger.removeAttr('title onclick');
					fTrigger.removeAttr('title onclick');
					
					if(parseInt(base64_decode(HexToAscii(base_url)))== 4)
						trigger.attr('title', 'Finalizando Pedido');
						else if(parseInt(base64_decode(HexToAscii(base_url)))== 3)
							trigger.attr('title', 'Cancelando Pedido');
						else if(parseInt(base64_decode(HexToAscii(base_url)))== 2)	
							trigger.attr('title', 'Aprobando Pedido');
													
			  },
     		  success:function(content){
     		  			var Xml = content.split('<!')[0];
					var r = parseInt($(Xml).find('respuesta').text());
					if(r ==1){
							var ePedido = parseInt($(Xml).find('id_status').text());

							if(ePedido == 4){
								cTrigger.attr('title', 'Cancelar - El Pedido ha sido finalizado por lo tanto esta función ya no está disponible');
								aTrigger.attr('title', 'Aprobar - El Pedido ha sido finalizado por lo tanto esta función ya no está disponible');
								fTrigger.attr('title', 'Finalizar - Pedido finalizado');
								label.html('<span id="iElement'+id_pedido+'" class="label label-info">Finalizado</span>');			
							}else if(ePedido == 3){
								cTrigger.attr('title', 'Cancelar - Pedido Cancelado');
								aTrigger.attr('title', 'Aprobar  - Pedido Cancelado');
								fTrigger.attr('title', 'Finalizar - Esta funcion estará disponible sólo cuando el pedido haya sido aprobado');
								label.html('<span id="iElement'+id_pedido+'" class="label label-important">Cancelado</span>');			
							}else{
								cTrigger.attr('title', 'Cancelar - Pedido Aprobado');
								aTrigger.attr('title', 'Aprobar  - Pedido Aprobado');
								fTrigger.attr('title', 'Finalizar - Finalizar pedido');
								fTrigger.addClass('btn-yellow');
								fTrigger.attr('onclick', 'setStatusForOrder(\''+base_url+'\', \''+controller+'\', \''+id_pedido+'\', \''+sAux+'\', '+cliente+', $(this), event, \''+sAux+'\')');
								label.html('<span id="iElement'+id_pedido+'" class="label label-success">Aprobado</span>');			
							}
							$('b[rel=pk-pedido]').text(base64_decode(HexToAscii(id_pedido)));
							$('b[rel=pk-pedido]').parent().removeAttr('style');
						}else if(r == 0){
							$('b[rel=pk-n-pedido]').text(base64_decode(HexToAscii(id_pedido)));
							$('b[rel=pk-n-pedido]').parent().removeAttr('style');
					}
			  },
    		  error: function(jqXHR, textStatus, errorThrown) {
						alert(errorThrown);
						trigger.attr('class', css);
						trigger.attr('onclick', action);
						label.html('<span id="iElement'+id_pedido+'" class="'+labelClass+'">'+labelText+'</span>');			

				}
	  });	

}



/*
* verifica existencia de imagen 
* de producto
*/
function checkImageExists(image_url, image)
{
	$.ajax({
		url: image_url,
		type: "HEAD",
		crossDomain: true,
		success: function() {  
		 	image.attr('src',image_url);
		 	image.parent().attr('href',image_url);
		 },
		error: function () { 
			image.attr('src','http://unocd.com/inventario/imagenes3pl/Fotosprd/noimage.png'); 
			}
	});
		
}
/**
* todo lo relacionado
* con la busqueda de informacion 
* de producto
*/
function showDetails(element, row,  base_url, controller, index, json_clasific, evnt)
{
	 evnt.preventDefault();
	 $.ajax({
			  url : base64_decode(HexToAscii(base_url))+base64_decode(HexToAscii(controller))+row+'/'+index,
              dataType: "xml",
			  beforeSend:function(){
					var html = '<img src="'+base64_decode(HexToAscii(base_url))+'img/loader2.gif" border="0" /><p style="text-align:center"><b>Cargando...</b></p>';
					$('#'+element).after('<tr/>').next().attr('rel',element).append('<td colspan="8"/>').children('td').append('<div/>').children().addClass('detail-info').html(html);
			  },
     		  success:function(Xml){
					show_info($(Xml), element, row, base_url, controller, index, json_clasific); 
							
			  },
    		  error: function(jqXHR, textStatus, errorThrown) {
						alert(errorThrown);
				}
	  });	

}

function show_info(Xml, element, row,  base_url, controller, index, clasific)
{
	var d = (parseInt(Xml.find('anti').text())> 0)?parseInt(Xml.find('anti').text()):'n/a';
	var button = $('a[info='+element+']');
	var html = '<div style="float:left; margin-left:20px">' ;
		if(parseInt(clasific.activo_clasprd1)==1)
			html += '<p> <i class="icon-tags"></i> <span class="value">'+Xml.find('cla1').text()+'</span> '+clasific.nombre_clasprd1+'</p>';
		if(parseInt(clasific.activo_clasprd2)==1)
			html += '<p> <i class="icon-barcode"></i> <span class="value">'+Xml.find('cla2').text()+'</span> '+clasific.nombre_clasprd2+'</p>';
		if(parseInt(clasific.activo_clasprd3)==1)
			html += '<p> <i class="icon-list"></i> <span class="value">'+Xml.find('cla3').text()+'</span>  '+clasific.nombre_clasprd3+'</p>';
		if(parseInt(clasific.activo_clasprd4)==1)
			html += '<p> <i class="icon-list"></i> <span class="value">'+Xml.find('cla4').text()+'</span> '+clasific.nombre_clasprd4+'</p>';
		if(parseInt(clasific.activo_clasprd5)==1)
			html += '<p> <i class="icon-list"></i> <span class="value">'+Xml.find('cla5').text()+'</span> '+clasific.nombre_clasprd5+'</p>';
		html += '</div><div style="float:left; margin-left:20px">';
		if((clasific.aprueba_pedidos) || ((parseInt(clasific.rol) == 2 ) || (parseInt(clasific.rol) == 3 ))){
			html+='<p> <i class="icon-tags"></i> <span class="value">'+Xml.find('calma').text()+'</span> Costo de Almacenamiento </p>';
			html+= '<p> <i class="icon-certificate"></i> <span class="value">'+Xml.find('costo').text()+'</span>  Costo Unitario </p>';
		}else{
			html += '<p> <i class="icon-barcode"></i> <span class="value">'+Xml.find('dcaja').text()+'</span> Display por Cajas</p>';
			html += '<p> <i class="icon-tasks"></i> <span class="value">'+Xml.find('udisp').text()+'</span> Unidades por Display</p>';
		}
		html += '<p> <i class="icon-tasks"></i> <span class="value">'+Xml.find('disp').text()+'</span> Unidades Disponibles</p>';
		html += '<p> <i class="icon-tasks"></i> <span class="value">'+Xml.find('mala').text()+'</span> Unidades Dañadas  </p>';
		html += '<p> <i class="icon-tasks"></i> <span class="value">'+Xml.find('rete').text()+'</span> Unidades Retenidas</p></div>';		
		html += '<div style="float:left; margin-left:20px"><p> <i class="icon-tasks"></i> <span class="value">'+Xml.find('prom').text()+'</span> Unidades Promocionales</p>';
		html += '<p> <i class="icon-tasks"></i> <span class="value">'+Xml.find('solis').text()+'</span> Unidades Solicitadas  </p>';
		html += '<p> <i class="icon-tasks"></i> <span class="value">'+Xml.find('tota').text()+'</span> Unidades Totales</p>';
		if((clasific.aprueba_pedidos) || ((parseInt(clasific.rol) == 2 ) || (parseInt(clasific.rol) == 3 ))){
			html += '<p> <i class="icon-barcode"></i> <span class="value">'+Xml.find('dcaja').text()+'</span> Display por Cajas</p>';
			html += '<p> <i class="icon-tasks"></i> <span class="value">'+Xml.find('udisp').text()+'</span> Unidades por Display</p></div><div style="float:left; margin-left:20px;margin-top:0px;">';
			html += '<p> <i class="icon-time"></i> <span class="value">'+Xml.find('fcrea').text()+'</span> Fecha de Creación</p>';
			html += '<p> <i class="icon-time"></i> <span class="value">'+Xml.find('ufechae').text()+'</span> Última Fecha de Entrada</p>';
			html += '<p> <i class="icon-time"></i> <span class="value">'+d+'</span> Días no Movilizados</p></div>';
		}else{
			html +='<p> <i class="icon-time"></i> <span class="value">'+Xml.find('fcrea').text()+'</span> Fecha de Creación</p>';
			html += '<p> <i class="icon-time"></i> <span class="value">'+Xml.find('ufechae').text()+'</span> Última Fecha de Entrada</p>';
			html += '<p> <i class="icon-time"></i> <span class="value">'+d+'</span> Días no Movilizados</p></div>';
		}
		html += '<div style="clear:both"></div>';			
		button.attr('class', 'btn btn-circle btn-bordered btn-primary  ');	
		button.attr('title', 'Dejar de mostrar detalle del Producto');
		button.attr('info', element);	
		button.attr("onClick", 'reSetElement("'+element+'","'+row+'","'+base_url+'","'+controller+'", "'+index+'", '+JSON.stringify(clasific)+',  event)');
		$('tr[rel='+element+'] > td > div').html(html);
}



function reSetElement(element, row, base_url, controller, index, clasific,  evnt)
{
	evnt.preventDefault();
	var button = $('a[info='+element+']');
	$('tr[rel='+element+']').remove();
	button.attr('class', 'btn btn-circle btn-primary  ');
	button.attr('title', 'Detalle del Producto');
	button.removeAttr('onClick');
	button.attr('onclick', 'showDetails("'+element+'","'+row+'","'+base_url+'","'+controller+'", "'+index+'", '+JSON.stringify(clasific)+' , event)');
	button = null;
}


/*
* todo los relacionado con
* los pedidos
*
*/
function showUpOrder(ob, ac, evnt)
{
  evnt.preventDefault();
  if (ac == 'cerrar')
  {	
		ob.attr('class', 'btn btn-circle btn-danger  ');
		ob.attr('title', 'Cerrar panel de pedidos');
		ob.attr('onclick', 'showUpOrder($(this), \'normal\', event)');
		$('#order-list').show( "slow");
		loadPedidosList();
  }else{
		ob.attr('class', 'btn btn-circle btn-info  ');
		ob.attr('title', 'Guardar Pedido');
		ob.attr('onclick', 'showUpOrder($(this), \'cerrar\',  event)');
 		closeFilterWin('#OrderListForm', '#order-list', evnt);
	  }
 
}

function loadPedidosList()
{
  if(localStorage.Pedido)
  {	
	  var collection = JSON.parse(localStorage.Pedido);
	  var orderContainer  = $('#order-list-container > tbody');
	  var orderContainerFChild  = orderContainer.find("tr"); //>:first-child
	 // if(orderContainerFChild.attr('rel') ==  'ini')
	  orderContainerFChild.remove();
	  if(collection.length > 0)
	  {
		  for(i = 0; i < collection.length; i++){
			  var Item = collection[i];
			orderContainer.append('<tr><td>'+Item.producto.codi+'</td><td style="text-align:left"><span>'+Item.producto.nombre+'</span><p><b>Peso:</b> '+Item.producto.peso+' gr. <b>Volumen:</b>  '+Item.producto.volumen+' m<sup>3</sup>.<br><b>Medidas:</b> '+Item.producto.ancho+' X '+Item.producto.largo+' X '+Item.producto.profundidad+' cm.<sup>anc. x lar. x Prof.</sup><br><b>Prensentación:</b> '+Item.producto.presentacion+'.</p></td><td><input type="text" name="data[PedidoProductos][cantidad][]" rel="PedidosProducto" id="PedidoProductosCantidad'+Item.producto.id+'" value="'+Item.solicitado+'" disponible_solicitado="'+Item.disponible_solicitado+'" style="text-align:center" /><input type="hidden" name="data[PedidoProductos][ProductoId][]" value="'+Item.producto.codi+'" /><input type="hidden" name="data[PedidoProductos][nestprd][]" value="'+Item.producto.nestprd+'" /><input type="hidden" name="data[PedidoProductos][ncli][]" value="'+Item.producto.ncli+'" /></td><td style="text-align:center" ><a class="btn btn-circle btn-danger"   rel="'+Item.producto.id+'" href="#" onclick=\'removeItemFromOrderList($(this), '+JSON.stringify(Item.producto)+',event);\' title="Cancelar esta solicitud de producto"><i class="icon-minus"></i></a></td>');		
		  }
	  }else
		orderContainer.append('<tr rel="ini"><td colspan="4"> La lista no posee producto(s) añadido(s) hasta el momento.</td></tr>');
  }else{
	  //var orderContainer 		  = $('#order-list-container > tbody');
	 // var orderContainerFChild  = orderContainer.find("tr"); //>:first-child
	  //orderContainerFChild.remove();
     orderContainer.append('<tr rel="ini"><td colspan="4"> La lista no posee producto(s) añadido(s) hasta el momento.</td></tr>');
  }
}

function SetPedidosObjects()
{
 if(localStorage.Pedido){
  var html = '<i class="icon-minus"></i>';
  var collection = JSON.parse(localStorage.Pedido);
  if(collection.length > 0)
  {
	  for(i = 0; i < collection.length; i++){
		  var input = $('#'+collection[i].input);
		  var linkButtom = $('a[rel='+collection[i].linkButtom+']');
		  if((typeof input != 'undefined') && (typeof linkButtom != 'undefined')){
			linkButtom.html(html);
			linkButtom.removeAttr('onclick');
			linkButtom.attr('class', 'btn btn-circle btn-danger  ');
			linkButtom.attr('onclick', 'removeIntemFromNewPedido('+JSON.stringify(collection[i].producto)+', event)');
			linkButtom.attr('title', 'Descartar pedido');
			input.val(collection[i].solicitado);
			input.attr('disabled', 'disabled');
		  }
	  }
  }
  }
}


function addItemToNewPedido(pcantidad, parent, prdInfo, evnt)
{
	if(checkValue(pcantidad))
	{
		var Item =  {
					input:pcantidad.attr('id'),
					linkButtom:parent.attr('rel'),
					id:prdInfo.id,
					producto:prdInfo,
					solicitado:pcantidad.val(),
					disponible_solicitado:pcantidad.attr('disponible_solicitado')
			};
			
		if(!setItemInStorage(Item))	{
			pcantidad.validationEngine('showPrompt', 'Este producto no puede ser agregado al pedido ya que su estatus difiere del resto de productos agregados', 'error');		
			pcantidad.val(null);
			return false;
		}else{
				var html = '<i class="icon-minus"></i>';
				parent.html(html);
				parent.removeAttr('onclick');
				parent.attr('class', 'btn btn-circle btn-danger  ');
				parent.attr('onclick', 'removeIntemFromNewPedido('+JSON.stringify(prdInfo)+', event)');
				parent.attr('title', 'Descartar pedido');
				pcantidad.attr('disabled', 'disabled');
		}
	}
	loadPedidosList();
	evnt.preventDefault();
}

function setItemInStorage(itemObject)
{
	var collection = [];
	if(localStorage.Pedido == null){
		collection[0] = itemObject;
		localStorage.Pedido = JSON.stringify(collection);
		
	}else{
		collection = JSON.parse(localStorage.Pedido);
		if(checkCollectionType(collection, itemObject))
		{
			collection.push(itemObject);
			localStorage.Pedido = JSON.stringify(collection);
		}else{
		 	return false;
		}
	}
	
	return true;

}

function checkCollectionType(collection, itemObject)
{
	for(i = 0; i < collection.length; i++)
	{
		if(collection[i].producto.nestprd != itemObject.producto.nestprd)
			return false;
	}
	
	return true;
}

function cleanLocalStorage(flag)
{
	
  localStorage.removeItem("Pedido");
  if(flag > 0){
	location.reload();  
  	loadPedidosList();
  }
}

function removeIntemFromNewPedido(prdInfo, evnt)
{
	removeItemFromLocalStorage(prdInfo.id);
	loadPedidosList();
	var pcantidad = $('#Producto'+prdInfo.id);
	var parent = $('a[rel='+prdInfo.id+']');
	var html = '<i class="icon-plus"></i>';
	parent.html(html);
	parent.removeAttr('onclick');
	parent.attr('class', 'btn btn-circle btn-success  ');
	parent.attr('title', 'Añadir pedido');
	parent.attr('onclick', 'addItemToNewPedido($("#Producto'+prdInfo.id+'"), $("a[rel='+prdInfo.id+']"), '+JSON.stringify(prdInfo)+', event)');
	pcantidad.val(null);
	pcantidad.removeAttr('disabled');
	evnt.preventDefault();
}

function removeItemFromLocalStorage(id){
  var collection = JSON.parse(localStorage.Pedido);
  var index = null;
  for(i = 0; i < collection.length; i++){
	 if(collection[i].id == id){
	 	index = i;
		break; 
	 }
  }
  collection.splice(index, 1);
  localStorage.Pedido = JSON.stringify(collection);
}

function removeItemFromOrderList(element,prdInfo,evnt)
{
	var itemContainer = element.parent().parent();
	var elementsContainer = itemContainer.parent();
	itemContainer.remove();
	if(!elementsContainer.children('tr').length > 0)
		elementsContainer.append('<tr rel="ini"><td colspan="4"> La lista no posee producto(s) añadido(s) hasta el momento.</td></tr>');
	removeIntemFromNewPedido(prdInfo, evnt);
	//removeItemFromLocalStorage(prdInfo.id);
}

function submitOrderListForm()
{
   var formElement = $('#OrderListForm');
   var sZona = $('#PedidoIdSubzona');
   var destino = $('#PedidoIdDestinatario');
   var tViaje = $('#PedidoTipoViaje');
   var itemsType = formElement.find('input[rel="TipoPedidosProducto"]');
   var elementsForm = formElement.find('input[rel="PedidosProducto"]');
   if(!elementsForm.length>0)
   {
		formElement.validationEngine('showPrompt', 'Primero realice una solicitud de pedido valida', 'error', 'bottomLeft');		
		return false;
   }else{
   		elementsForm.each(function()
		{
			if(!checkValue($(this)))
				return false;
		}
	  );
		
		if(!parseInt(sZona.val())){
	 		destino.validationEngine('showPrompt', 'Campo obligatorio', 'error', 'topLeft');
			return false;	
		}
		if(!parseInt(destino.val())){
	 		destino.validationEngine('showPrompt', 'Campo obligatorio', 'error', 'topLeft');
			return false;	
		}
		if(!parseInt(tViaje.val())){
	 		tViaje.validationEngine('showPrompt', 'Campo obligatorio', 'error', 'topLeft');
			return false;	
		}
		checkItemListType(itemsType);
		formElement.submit();
	  	formElement.data("submitted", true);
		formElement.preventDoubleSubmission();
		cleanLocalStorage(0);
   }
}


function checkItemListType(itemsType)
{
	itemsType.each(function()
	{  	var type = parseInt($(this).val());
		if(type == 3){
			switchDestinos(true);	
			return;
		}else{
			switchDestinos(false);	
			return;
		}
		
	  }
  );
}


function switchDestinos(flag)
{
	if(flag)
			//$('#PedidoIdDestinatario option:not([value=14662])').each(function(){ $(this).attr('disabled', 'disabled'); });
			$('#PedidoIdDestinatario option[value=14662]').attr('selected', 'selected');
		else if(!flag)
			$('#PedidoIdDestinatario option:first').attr('selected','selected');
			//$('#PedidoIdDestinatario option:not([value=14662])').each(function(){ $(this).removeAttr('disabled'); });
}


function checkValue(obValidate)
{
	 if(obValidate.val().length == 0)
	 {
		obValidate.validationEngine('showPrompt', 'Coloque la cantidad a solicitar', 'error');		
		return false;
	 }
	else if(!parseInt(obValidate.val()))
	{
		obValidate.validationEngine('showPrompt', 'Formato de valor no aceptado', 'error');		
		return false;
	}else if (!parseInt(obValidate.val()) >0 )
	{
		obValidate.validationEngine('showPrompt', 'valor no valido, el valor mínimo debe ser uno (1)', 'error');		
		return false;
	}else if ((!parseInt(obValidate.attr('disponible_solicitado'))) || (parseInt(obValidate.val()) > parseInt(obValidate.attr('disponible_solicitado'))))
	{
		obValidate.validationEngine('showPrompt', 'El valor con el que intenta realizar la solicitud no puede ser mayor al de la columna "Disponible por Solicitar"', 'error');		
		return false;
	}
	return true
}

function onFilter(ob, ac, evnt)
{
	evnt.preventDefault();
  if (ac == 'cerrar')
  {	
		ob.attr('class', 'btn btn-circle btn-danger  ');
		ob.attr('title', 'Cerrar panel de filtros');
		ob.attr('onclick', 'onFilter($(this), \'normal\', event)');
		$('#filter').show( "slow");
  }else{
		ob.attr('class', 'btn btn-circle btn-info  ');
		ob.attr('title', 'Aplicar filtros a la búsqueda');
		ob.attr('onclick', 'onFilter($(this), \'cerrar\',  event)');
 		closeFilterWin('#FilterForm', '#filter', evnt);
	  }
}

function closeFilterWin(elementFormID, elementID, evnt)
{
	evnt.preventDefault();
	$(elementID+' > table > tbody > tr > td > *').each(function()
	{  			var type = this.type || this.tagName.toLowerCase();
				if(type == 'select-one')
						this.selectedIndex = 0;
					else if(type == 'text')
							this.value = null;
		}
		);
	$(elementID).hide('slow');
}

function showUpPedidoDetalle(element, evnt)
{
	evnt.preventDefault();
	element.modal();
}

function SendDataPedido(id,index, producto, cliente, pedido, solicitante, topIndex, accion)
{	
    var formElement = $('#PedidoUpdatePedidoForm'+topIndex);
	var indexElement = $('#PedidosProductoIndex'+topIndex);
	var accionElement = $('#PedidosProductoFormAccion'+topIndex);
	var productoElement = $('#PedidosProductoId'+topIndex);
	var cantidadElement = $('#PedidosProductoProductoCodi'+topIndex);
	
	var clienteElement = $('#PedidosProductoIdCliente'+topIndex);
	var pedidoElement = $('#PedidosProductoIdPedido'+topIndex);
	var solicitanteElement = $('#PedidosProductoIdSolicitante'+topIndex);
	
	indexElement.val(index);
	productoElement.val(id);
	accionElement.val(accion);
	cantidadElement.val(producto);
	
	clienteElement.val(cliente);
	pedidoElement.val(pedido);
	solicitanteElement.val(solicitante);
	
	formElement.submit();
	formElement.data('submitted', true);
	formElement.preventDoubleSubmission();
}

function UpdatePedido(base_url, prd, pd, element, nestprd, id, index, producto, cliente, pedido, solicitante, topIndex,accion, evnt)
{
	 evnt.preventDefault();
	 var cantidad = element.val();
	 if((!isNaN(parseInt(cantidad))) && (cantidad > 0))
	 {
		 $.ajax({
				  url : base64_decode(HexToAscii(base_url))+'pedidos/update_solicitados/'+prd+'/'+pd+'/'+cantidad+'/'+nestprd,
				  dataType: "xml",
				  timeout: 80000,			  
				  beforeSend:function(){
						element.attr('readonly', 'readonly');
						element.validationEngine('showPrompt', 'Cargando...', 'load');		
				  },
				  success:function(Xml){
					var	r =  parseInt($(Xml).find('respuesta').text());
					if(r == 1)
							SendDataPedido(id,index, producto, cliente, pedido, solicitante, topIndex,accion);
					
					if(r == 0){
								element.validationEngine('showPrompt', 'El valor que solicita supera el limite establecido ('+$(Xml).find('respuesta').attr('limite')+')', 'error');		
								element.removeAttr('readonly');
					}
					
				  },
				  error: function(jqXHR, textStatus, errorThrown) {
						element.validationEngine('showPrompt', 'Error en el proceso', 'error');		
					}
		  });	
	 }else{
	 	element.validationEngine('showPrompt', 'Valor no valido', 'error');
	 }
}


function CancelPedido(base_url, prd, pd,  container, id, index, producto, cliente, pedido, solicitante,topIndex, accion, evnt)
{
	 evnt.preventDefault();
	 if(confirm('Realmente desea elimiar esta solicitud?'))
	 {
	 
	   SendDataPedido(id,index, producto, cliente, pedido, solicitante,topIndex, accion);
	 }else
	   return false;
}

function loadInfo(element)
{
	var sZoneElement = $('#PedidoIdSubzona');
	var CodigoElement = $('#PedidoCodDestino');
	var selected = element.find(':selected');
	var szone = selected.data('szone');
	var snombre = selected.data('sznombre');
	var znombre = selected.data('znombre');
	var dr = selected.data('addr');
	var ph = selected.data('phon');
	var mail = selected.data('mail');
	var codigo = selected.data('codigo');
	element.nextAll().remove();
	sZoneElement.val(null);
	CodigoElement.val(null);
	if(!(typeof szone === 'undefined') && !(typeof dr === 'undefined') && !(typeof ph === 'undefined') && !(typeof mail === 'undefined') && !(typeof snombre === 'undefined') && !(typeof znombre === 'undefined')){
		sZoneElement.val(szone);
		CodigoElement.val(base64_decode(HexToAscii(codigo)));
		if(mail == '')
			mail = 'No Dispone';
		if(ph == '')
			ph = 'No Dispone';	
		element.after('<div class="alert alert-info alert-dismissable" style="text-align:left; margin-top:5px; font-weight:normal;"><a class="close" data-dismiss="alert" href="#">×</a><h4><i class="icon-info-sign"></i> Info</h4> <div><b>Estado:</b> <span>'+znombre+'</div><div><b>Ciudad(Destino):</b> <span>'+snombre+'</div><div><b>Dirección:</b> <span>'+dr+'</div><div><b>Teléfono(s):</b> <span>'+ph+'</div><div><b>Email:</b> <span>'+mail+'</span></div></div>');	
	}
}


function CargaListaDestino(base_url, request, val, ncli, control)
{
	  $.ajax({
			  url :base64_decode(HexToAscii(base_url))+request+'/'+val+'/'+ncli+'/',
              dataType: "xml",
			  beforeSend:function(){
					 control.empty();
					 control.append($('<option>', {
									value: null,
									text: '[Cargando...]'
					 }));
		   	  },
     		  success:function(Xml){
     		  		
				 control.empty();
			  	 control.append($('<option>', {
								value: null,
								text: '[Seleccionar]'
				 }));
				 $(Xml).find('Element').each(function(){
						control.append($("<option></option>")
										 .attr("value",$(this).attr('key'))
										 .attr("data-szone",$(this).attr('nsuz'))
										 .attr("data-codigo",AsciiToHex(base64_encode($(this).attr('codigo'))))
										 .attr("data-sznombre",$(this).attr('nsuz_nombre'))
										 .attr("data-znombre",$(this).attr('nzon_nombre'))
										 .attr("data-addr",$(this).attr('address'))
										 .attr("data-phon",$(this).attr('phone'))
										 .attr("data-mail",$(this).attr('email'))										 
										 .text($(this).text())); 
							});
			  },
    		  error: function(jqXHR, textStatus, errorThrown) {
					 control.empty();
					 control.append($('<option>', {
									value: null,
									text: '[Seleccionar]'
					 }));
				}
	  });
}



function mark(area)
{
	$('div[rel='+area+'] > div.checkbox > input[type=checkbox]').each(function(){ 
			$(this).prop('checked', true);
		}
	);
}

function unmark(area)
{
	$('div[rel='+area+'] > div.checkbox > input[type=checkbox]').each(function(){ 
			$(this).prop('checked', false);
		}
	);
}

function validateCheckBox(area, minimun)
{
	var c = 0;
	$('div[rel='+area+'] > div.checkbox > input[type=checkbox]').each(function(){ 
		if($(this).prop('checked'))
			c++;
	});
	if(!c > 0){
		$('div[rel='+area+']').validationEngine('showPrompt', '* Debe seleccionar al menos una (1) opción', 'checkbox', 'topLeft', true);	
		return false;
	}else{
		return true;
	}
}

function activaClasificacion()
{
	var elements = $(".form-element input[type=checkbox]");
	elements.each(
			function(){
				$(this).click(
					function(){
						  var t = $(this).parent().children("input[type=text]");
						  if($(this).prop('checked'))
						  {
								
							  t.attr('class', 'validate[required, custom[onlyLetterSp]]');
							  t.prop('readonly', false);
							
							}else{
							  t.prop('readonly', true);
							  t.val(null);
							  t.removeAttr('class');
							
						}
					}
			);
		}
	);
}

/*

*/
function searchCliente(base_url,dd)
{
	var msmg = 	$('span.nota_validacion');
	if(parseInt(dd.val())){
		 window.location = base64_decode(HexToAscii(base_url))+'cliente:'+AsciiToHex(base64_encode(dd.val()));
	}else
		msmg.removeAttr('style');
}

	function validateForm(form)
	{
		if(form.validationEngine("validate"))		
			form.preventDoubleSubmission();
			else
				return false;
	}
	function validateFormWCheckBox(form)
	{
		if(form.validationEngine("validate"))		{
			if(validateCheckBox("clientes",1))	
				form.preventDoubleSubmission();
				else
					return false;
		}else
				return false;
	}

function getNotificaciones(base_url, controller,index)
{
	 $.ajax({
			  url : base64_decode(HexToAscii(base_url))+base64_decode(HexToAscii(controller))+index,
              dataType: "xml",
     		  success:function(Xml){
				 var	r =  parseInt($(Xml).find('cantidad').text());	
				 if(r > 0){
				 	$('span[rel=notificacion]').attr('class', 'badge badge-important');
					$('span[rel=notificacion]').text(r);
				 }else{
				 	$('span[rel=notificacion]').attr('class', 'badge badge-alert');
					$('span[rel=notificacion]').text(0);
					 }
					 
			  },
    		  error: function(jqXHR, textStatus, errorThrown) {
						alert(errorThrown);
				}
	  });	

}
/*
*
*/
function marcar_notificacion(requestURI)
{
	$.ajax({
			  url : base64_decode(HexToAscii(requestURI)),
              dataType: "xml",
     		  success:function(Xml){
				 var	r =  parseInt($(Xml).find('respuesta').text());	
				 if(r == 0)
					alert('Ha ocurrido un error en la petición');
					else{
						 $('span[rel="notificacion"]').html('0');
						 $('span[rel="notificacion"]').removeClass('badge-important');
						 $('span[rel="notificacion"]').removeClass('badge-alert'); 
					}
			  },
    		  error: function(jqXHR, textStatus, errorThrown) {
						alert(errorThrown);
				}
	  });
}
/*
*
*/
function TabDetallePanel()
{
	var Panels = $('.win-detalle table');
	Panels.each(function(){
			$('thead', $(this)).click(function(){
					$('tr', $(this)).eq(1).toggle();
					$('tbody', $(this).parent()).toggle();					
			});
	});	
}