<?php
$link = mysql_connect('localhost','unocdcom','$UNOcd2013')
    or die('No se pudo conectar: ' . mysql_error());
echo '<p>Conectado a la <b>Base de Datos</b> Satisfactoriamente</p>';
mysql_select_db('unocdcom_pchat1') or die('<p><b>No se pudo seleccionar la base de datos</b></p>');
if(!mysql_query("CALL unocdcom_pchat1.sp_actualiza_clasprd3;"))
 echo '<p>El Stored procedure "pchat1.sp_actualiza_clasprd3" : ' . mysql_error().'</b>';
else
  echo '<p>El Stored procedure "pchat1.sp_actualiza_clasprd3" fue ejecutado satisfactoriamente!</p>';
 if($link) 
  mysql_close($link);
?>
