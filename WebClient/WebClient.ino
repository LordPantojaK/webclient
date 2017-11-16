/*
  Web client
  Peticiones GET a Hosting gratuito 
  Modifica el estado de 4 salidas digitales en forma diferida
 */

#include <SPI.h>
#include <Ethernet.h>

// Enter a MAC address for your controller below.
// Newer Ethernet shields have a MAC address printed on a sticker on the shield

byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
char server[] ="www.guscir.hol.es";
// Set the static IP address to use if the DHCP fails to assign
IPAddress Myip(192, 168, 1, 177);

// Initialize the Ethernet client library
// with the IP address and port of the server
// that you want to connect to (port 80 is default for HTTP):
EthernetClient client;

unsigned long start=millis();
unsigned long periodoConexion=30000; // 30 segundos cada 1/2 minuto el Cliente llama al server
boolean flagCon=0; // Flag de conexión de conexión válida servidor
boolean flagMod=0; // Flag de modificación de salidas digitales realizadas
String cadConsulta= "GET /requerimiento.php?consulta=1 HTTP/1.1";
String cadRespuesta="GET /requerimiento.php?consulta=2 HTTP/1.1";
String cadena="";
boolean httpRequest (String cad );  // Función que se encarga de conectar al servidor Hosting y devuelve true o false si se conecto o no
void respuesta(); // Funcion de esperar la respuesta y recibir la cadena HTML completa que responde el servidor
void modificarSalida(String cade); // funcion que procesa el estado de las salidas, las separa y actua sobre las salidas
void setup() {
  // Open serial communications and wait for port to open:
  Serial.begin(9600);

      // start the Ethernet connection: Primero intentamos por DCHP sino vamos con Myip que es fija
  if (Ethernet.begin(mac) == 0) {
    Serial.println("Failed to configure Ethernet using DHCP");
    // no point in carrying on, so do nothing forevermore:
    // try to congifure using IP address instead of DHCP:
    Ethernet.begin(mac, Myip);
  }
  // give the Ethernet shield a second to initialize:
  delay(1000);
  cadena = cadConsulta;
}// setup

void loop()
{
  // if there are incoming bytes available
  // from the server, read them and print them:
    if(millis()-start>=periodoConexion)
     {
       start=millis(); 
       if(flagMod==1) // hay que enviar un GET consulta=2 para notificar que se hizo el cambio
       {  cadena = cadRespuesta;
          Serial.println("Enviando confirmación cambio realizado");
          Serial.println(cadena);
          flagMod=0; // Se pone a 0 para que no vuelva a notificar y de ahora en mas solo envíe GET con consulta=1
       } // El proximo Get notificará al servidor la modificación a la página y el usuario asi podrá saber
       else { 
              cadena = cadConsulta;
              Serial.println("Enviando connsulta");
              Serial.println(cadena);
            } 
       if(httpRequest(cadena)==1)
         {
           respuesta();
         }
     
    }// millis
  }

boolean httpRequest(String cad)
{
  flagCon=0; // lo ponemos por default a 0=false
  Serial.println("connecting...");
  // if you get a connection, report back via serial:
  if (client.connect(server, 80)) { // CONECTAMOS AL SERVER
    Serial.println("connected");
    flagCon=1;
    // Make a HTTP request:
    //client.println("GET /requerimiento.php?consulta=1 HTTP/1.1"); Es de esta forma
     client.println(cad);
    //client.println("GET /search?q=arduino HTTP/1.1");
    //client.println("Host: www.google.com");
    client.println("Host: www.guscir.hol.es");
    client.println("Connection: close"); // este es como un fin de consulta de GET
    client.println();
  }
  else {
    // kf you didn't get a connection to the server:
    Serial.println("connection failed");
    Serial.println("desconectando");
    client.stop(); 
    flagCon=0;
       }
 delay(2000); // damos tiempo  
 return flagCon;
}

void respuesta() // lee la respuesta del servidor que lo recibimos igual que la función serial
{ char c;
  String rx ; // Cadena de datos recibidos
  String subx ; // subcadena filtrada
 // Leemos la respuesta html del servidor, aca viene todo
  while (client.available()>0) 
  {
    
    c = client.read();
    rx+=c; // Concatenamos
    Serial.print(c);// A su vez vamos viendo en el monitor todo lo que recibimos caracter por vez
    
  } // while 
  client.stop(); // Detenemos el cliente , es decir cerramos la conexión del cliente cuando termina la respuesta
  // Si no se hace esto y se vuelve a intentar conectar da FALLA
  // ANALIZAMOS LA CADENA DE RESPUESTA
  //**********************************
  //int longitud= rx.length();  // tomamos longitud de la cadena
  int pos; // Posición de inicio de subcadena sal1=  , por GET consulta=1
  int posok; // Posicion de inicio de subcadena okey por GET consulta=2
  pos=rx.indexOf("sal1="); // Tomo la posición del inicio de la subcadena 
  posok=rx.indexOf("okey");
  //Serial.print("posicion de sal :");
  //Serial.println(pos);
  // Lo que me interesa son los 24 caracteres a partir de la posición detectada que incluye la N de no realizado y la R de realizado
  if(pos!=-1) // indica que encontro subcadena sal1=
  {
     subx=rx.substring(pos,pos+25); // el parametro TO es no inclusivo, por eso es 25 y no pos+24
     Serial.print("La subcadena filtrada de interes es :");
     Serial.println(subx);
  
     if (subx.charAt(24)=='R') //NO Hay que modificar las salidas ya que es la R de realizado
     {
      Serial.println("NO HAY QUE MODIFICAR NADA !!!!!!!");
      flagMod=0;
    
     }
      if (subx.charAt(24)=='N') //  Hay que modificar las salidas ya que es la N de no realizado
     {
       Serial.println("HAY QUE MODIFICAR lAS SALIDAS DIGITALES");
       modificarSalida(subx); // funcion que procesa el estado de las salidas, las separa
     }
  }// pos
  if(posok!=-1) // indica que encontro subcadena okey en respuesta de consulta=2
  {
    Serial.println ("EL SERVIDOR ACUSO RECIBO DEL CAMBIO");
  }
}

void modificarSalida(String cade)
{
  /* En esta función debemos obtener los valores de las 4 salidas, 0 o 1 
     y luego de acuerdo a dicho estado modificar los pines digitales en nuestra aplicación
     que se han obviado y solo se realizará el procesamiento dejando a su ellección la aplicación
  */
  int salida1,salida2,salida3,salida4;
  salida1=cade.charAt(5)-'0';
  salida2=cade.charAt(11)-'0';
  salida3=cade.charAt(17)-'0';
  salida4=cade.charAt(23)-'0';
  Serial.print("SALIDA 1: ");
  Serial.println(salida1);
  Serial.print("SALIDA 2: ");
  Serial.println(salida2);
  Serial.print("SALIDA 3: ");
  Serial.println(salida3);
  Serial.print("SALIDA 4: ");
  Serial.println(salida4);
  flagMod=1;
  
}
