<html>

// Con Hostinger acceder como http://www.guscir.hol.es/salidas.php
<head>
<link rel=stylesheet type="text/css" href=estilo.css>
</head>
<body>
<?php
 $ar=fopen("archivocontrol.txt","r") or
    die("No se pudo abrir el archivo");
	//$linea=""; Otra manera
  while (!feof($ar))
  {
    // $linea=$linea.fgets($ar)."<br>"; // va concatenando linea a linea y agrega un salto a cada linea
    //echo $linea;
    $linea=fgets($ar);
    //echo $linea;
  }
  fclose($ar);
  // En $linea tengo el estado de las salidas en una cadena debo procesarla
  $estado1= substr($linea,5,1);
  //echo $estado1;
  $estado2= substr($linea,11,1);
  //echo $estado2;
  $estado3= substr($linea,17,1);
  //echo $estado3;
  $estado4= substr($linea,23,1);
  //echo $estado4;
  $idcambio=0;
  $cambio="No realizado";
  $cantidad= strlen($linea);
  //echo $cantidad;
  
  if($cantidad>24)  // hay datos de confirmacion
  {
    $confirma=substr($linea,24,1);
	//echo $confirma;
	if($confirma=="R") // Cambio realizado actualizado por el cliente
	  {$cambio="Cambio realizado";
	  $idcambio=1;
	  $fecha= substr($linea,34); // capta la fecha y hora del cambio efectuado por el cliente
	  }	  
  }
?>
<p><h3>CONTROL DE SALIDAS</h3></p>
<form name="salidas" method="post" action="ArchivoControl.php">
 <table border=1 bordercolor=blue > 
  <tr>
  <?php  if ($estado1==1) 
    {  
    echo "<td class=col><h4>SALIDA_1</h4><input type=\"checkbox\" name=\"sal1\" value=\"1\" checked ></td>";
	}
	else{
	 echo "<td class=col><h4>SALIDA_1</h4><input type=\"checkbox\" name=\"sal1\" value=\"1\"  ></td>";
	}
        if ($estado2==1) 
    {  
    echo "<td class=col><h4>SALIDA_2</h4><input type=\"checkbox\" name=\"sal2\" value=\"1\" checked ></td>";
	}
	else{
	 echo "<td class=col><h4>SALIDA_2</h4><input type=\"checkbox\" name=\"sal2\" value=\"1\"  ></td>";
	}
	if ($estado3==1) 
    {  
    echo "<td class=col><h4>SALIDA_3</h4><input type=\"checkbox\" name=\"sal3\" value=\"1\" checked ></td>";
	}
	else{
	 echo "<td class=col><h4>SALIDA_3</h4><input type=\"checkbox\" name=\"sal3\" value=\"1\"  ></td>";
	}
	if ($estado4==1) 
    {  
    echo "<td class=col><h4>SALIDA-4</h4><input type=\"checkbox\" name=\"sal4\" value=\"1\" checked ></td>";
	}
	else{
	 echo "<td class=col><h4>SALIDA_4</h4><input type=\"checkbox\" name=\"sal4\" value=\"1\"  ></td>";
	}
	?>

  </tr>
  <tr></tr><tr></tr>
 </table>
 <br>
 <?php
     if($idcambio==0)
	 {
	   echo $cambio;
	 }
	 else{
	   echo $cambio." ".$fecha;
	 }
 ?>
 <br>
 <br>
<input type="submit" name="botonModificar" value="Aceptar"> 
<br><br>
</form>
<form name="estadoActual" method="post" action="LecturaArchivo.php">
<input type="submit" name="botonLeer" value="LeerEstado"> 
</form>
</body>
</html> 
