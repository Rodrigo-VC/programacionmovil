<?php
	$conexion=mysql_connect("localhost","root","12345678") or die ("Error de conexión");
	mysql_select_db("reservanatural",$conexion)or die("Error en la BD");
?>