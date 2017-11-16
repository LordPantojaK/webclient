<html>
<head>
</head>
<body>
<?php
 $ar=fopen("archivocontrol.txt","r") or
    die("No se pudo abrir el archivo");
	  while (!feof($ar))
  {
    
    $linea=fgets($ar);
    
  }
  fclose($ar);
  //echo $linea;
  echo '';
  echo '';
  $estado1= substr($linea,5,1);
  //echo $estado1;
  $estado2= substr($linea,11,1);
  //echo $estado2;
  $estado3= substr($linea,17,1);
  //echo $estado3;
  $estado4= substr($linea,23,1);
  //echo $estado4;
  $est1="Desactivada";
  $est2="Desactivada";
  $est3="Desactivada";
  $est4="Desactivada";
  if($estado1==1)
  {$est1="Activada";}
  if($estado2==1)
  {$est2="Activada";}
  if($estado3==1)
  {$est3="Activada";}
  if($estado4==1)
  {$est4="Activada";}
?> 
