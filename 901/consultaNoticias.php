<?php
	session_start();
	include('conexion.php');
	?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Enlace UPVM</title>
        <!-- Bootstrap core CSS -->
    	
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
        
    <script>
	$(document).ready(function() {
		$("#mes").on('change',function(e){
			$("#contenido").load('consultaNoticias.php?mesActual='+$('#mes').val());
    });
    	$("#btnResumen").on('click',function(e){
		$("#contenido").load('resumenNoticias.php?mesActual='+$('#mes').val());
    });
	});
    </script>
    <style>
		div.container {
			width: 96%;
			max-width:960px;
			margin: 0 auto;
			}
			img {
			width: 100%;
			height:auto;
		}
		</style>
        </head>
        <body>
        	<div id=contenido class="divPanel page-content">
        <?php
			$miFecha=date_default_timezone_set('UTC');
			if(empty($_REQUEST[mesActual]))
			{
				if(empty($_SESSION[mesActual]))
				{
					$fecha=strftime("%Y-%m");
					$_SESSION[mesActual]=$fecha;
					$mesN=explode("-",$fecha);
					$_SESSION[mesN]=$mesN[1];
					$_SESSION[anioN]=$mesN[0];
				}
			}
			else
			{
				$_SESSION[mesActual]=$_REQUEST[mesActual];
				$fecha=$_REQUEST[mesActual];
				$mesN=explode("-",$fecha);
				$_SESSION[mesN]=$mesN[1];
				$_SESSION[anioN]=$mesN[0];
			}
			$fecha=$_SESSION[mesActual];
			
			setlocale(LC_TIME,"es_MX.UTF-8");
			setlocale(LC_TIME,'spanish');
			mysql_query('SET lc_time_names = es_ES');
			  
	$sql="Select id, titulo, contenido,DATE_FORMAT(fecha,'%d - %b - %Y') AS fecha, autor, imagen1, fechaPublicacion,tipoNoticia, publicar from noticias where publicar='si' and MONTH(fechaPublicacion)='$_SESSION[mesN]' and YEAR(fechaPublicacion)='$_SESSION[anioN]' order by fechaPublicacion desc";
	$datos=mysql_query($sql,$conexion) or die ("Error de SQL");
?>
<table border=0 class="table" cellpadding="10">
<tr>
<td>
consultar por mes:<br/> &nbsp;<input type="month" id="mes" name="mes" step="1" min="2013-12" value=<?php echo $fecha; ?>  >
</td>
<td align="right">
<br/>
<input type="button" id="btnResumen" value="Ver resumen" class="btn-info" />
</td>
</tr>
</table>
<?php
	//$fecha-strftime("%m-%Y");
	$vacio=mysql_fetch_array($datos);
	if(empty($vacio[titulo]))
	{
		echo "<center><font color='red'><b>No hay noticias en el mes seleccionado </b></font></center>";
	}
	
	$datos=mysql_query($sql,$conexion) or die ("Error de SQL");
	echo "<table border=0 cellpadding='20'>";
	while($reg=mysql_fetch_array($datos))
		{
			echo "<tr>";
			$titulo = mb_strtoupper($reg[titulo], 'UTF-8');
			echo "<td><b><font color='green' size='4'>$titulo </font></b><br/>";
			echo "Publicado: ".$reg[fecha]."</td>";
			echo "</tr>";
			if($reg[imagen1]!=='')
			{
				echo "<tr>";
				echo "<td><div>
				<img src=../img/".str_replace(" ","%20",$reg[imagen1])." align=center>
				</div>
				</td>";
			echo "</tr>";
			}
			echo "<tr>";
			echo "<td align=justify> $reg[contenido] <br/></td>";
			echo "</tr>";
		}	//fin del while
		echo "</table>";
	mysql_free_result($datos);
	mysql_close($conexion);
?>