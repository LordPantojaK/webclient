</head>
<body>
<?php

// http://www.guscir.hol.es/requerimiento.php?consulta=2  se llama de esta manera 
// No necesariamente habra que pedir con una variable, puede que solo el servidor responda sin necesidad de pasarle una variable, según sea la necesidad
//  http://www.guscir.hol.es/requerimiento.php
 
 $ar=fopen("archivocontrol.txt","r") or
    die("No se pudo abrir el archivo");
  while (!feof($ar))
  {
        $linea=fgets($ar);
    
  }
  fclose($ar);
 
  if(!empty($_GET['consulta']) && $_GET['consulta']==1) // El cliente pide consulta de las salidas, su estado
  {
  echo $linea;
  }
  if(!empty($_GET['consulta']) && $_GET['consulta']==2) // El cliente quiere logear algo o confirmar algo en el server, en este caso validar el cambio de las salidas 
  // en el cliente de acuerdo al nuevo estado de las salidas. Aqui podemos mandar un Mail o actualizar un objeto o elemento en la pagina
  // para que al entrar nuevamente el usuario se de cuenta que su cambio se realizó, en este caso el cliente reflejo el cambio de las salidas
  {
    $linea=substr($linea,0,24); // Captura el estado de las salidas para filtrar el NO REALIZADO
    $ar=fopen("archivocontrol.txt","w") or
    die("Problemas en la creacion");
	date_default_timezone_set('America/Argentina/Cordoba');  // Actualiza a zona horaria America ya que sino devuelve hora España
$time =  date("Y-m-d | h:N:s |", time() ) ;  
  fputs($ar,$linea."REALIZADO"."-".$time); // Reescribe archivo con la indicación de realizado
  //fputs($ar,"br");
  
  fclose($ar);
echo "okey";
  }
?> 
