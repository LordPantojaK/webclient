ArchivoControl.php

<html>
<head>
</head>
<body>
<?php
  $estado1=0;
  $estado2=0;
  $estado3=0;
  $estado4=0;
  if (isset($_REQUEST['sal1'])) 
  {
   $estado1=$_REQUEST['sal1'];
  }
   if (isset($_REQUEST['sal2'])) 
  {
   $estado2=$_REQUEST['sal2'];
  }
   if (isset($_REQUEST['sal3'])) 
  {
   $estado3=$_REQUEST['sal3'];
  }
   if (isset($_REQUEST['sal4'])) 
  {
   $estado4=$_REQUEST['sal4'];
  }
  $cadena="sal1=".$estado1."sal2=".$estado2."sal3=".$estado3."sal4=".$estado4."NO REALIZADO";
  // El servidor adjunta NO REALIZADO para que el cliente sepa que es una nueva modificacion en las salidas
  //echo $cadena;
  
  $ar=fopen("archivocontrol.txt","w") or
    die("Problemas en la creacion");
  fputs($ar,$cadena);
  //fputs($ar,"br");
  fclose($ar);
  echo "
";
  echo "
";
  echo "Los datos se cargaron correctamente.";
  echo "
";
  echo "
";
  ?>

Volver 
