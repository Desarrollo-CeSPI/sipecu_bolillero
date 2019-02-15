<?php
$bits= 32;

function help()
{
  global $argv, $bits;
  $format = <<<EOT
Uso:
 php %s [-h] -s <NUM> -u <NUM> -n <NUM> [-b <32|64>]

Donde:
  -h: muestra esta ayuda
  -s <NUM> segundos del momento del sorteo
  -u <NUM> microsegundos del momento del sorteo
  -n <NUM> especifica la cantidad de numeros a sortear.
  -b <32|64> Bits de la versión de php. Por defecto %s (versión utilizada en el servidor).
  
IMPORTANTE: La versión de PHP actual en donde se correrá el sorteo es de %s bits.

EOT;
  printf($format, $argv[0], $bits, $bits);
}

$opciones = getopt("hs:n:u:b:");

foreach($opciones as $opt => $valor)
{
  switch($opt) 
  {
    case 'h':
      help();
      exit(0);
    case 'n':
      $numeros = intval($valor);
      break;
    case 's':
      $segundos = intval($valor);
      break;
    case 'u':
      $microsegundos = intval($valor);
      break;
    case 'b':
      if (!in_array($valor, array('32', 64)))
      {
        printf("Valor de bits de PHP inválido => %s\n\n", $valor);
        help();
        exit(1);
      }
      $bits = intval($valor);
      break;
    default:
      printf("Opción errónea => %s\n\n", $opt);
      help();
      exit(1);
  }
}

foreach (array('s', 'n', 'u') as $opcion)
{
	if (!isset($opciones[$opcion]))
  {
    printf("Opción faltante => %s\n\n", $opcion);
    help();
    exit(1);
  }
}

require_once("BolilleroReproductor.php");

//El tamaño de la semilla es de 32 bits (implementación estándar MT19937 https://en.wikipedia.org/wiki/Mersenne_Twister) por lo que se utilizarán los últimos 32 bits.
$semilla = (int) (((int) $segundos * 1000000) + $microsegundos);
if ($bits == 32) //En versiones de PHP de 32 bits (es decir si el web en donde se corrió el sorteo fue de 32 bits)
{
  if (PHP_INT_SIZE > 4) //Verifico el tamaño de los enteros de la versión en la cual se está corriendo la prueba
  {  
    //Corrección en verificación de 64 bits
    $semilla_tmp = $semilla & 0x00000000FFFFFFFF;
    if (strlen(decbin($semilla_tmp)) == 32)
    {
      $semilla = $semilla | 0xFFFFFFFF00000000;
    }
    else
    {
      $semilla = $semilla_tmp;  
    }
  }
}
else //En versiones de PHP de 64 bits (es decir si el web en donde se corrió el sorteo fue de 64 bits)
{
  if (PHP_INT_SIZE < 8) //Verifico el tamaño de los enteros de la versión en la cual se está corriendo la prueba
  {  
    printf("Es necesaria una versión de PHP de %s bits\n\n", $bits);
    exit(1);
  }
}
$bolillero = new BolilleroReproductor($semilla, $numeros);
$numeros_sorteados = $bolillero->sortear();
$generador_hash = $segundos.';'.$semilla.';'.implode(',', $numeros_sorteados);

$resultado = <<<EOT
* Números sorteados:

%s

* Semilla de %sbits: %s
* Hash (sha1): %s
* Generador de hash: %s

EOT;

foreach($numeros_sorteados as $k => $v)
{
  $numeros_sorteados[$k] = ($k+1).": ".$v;
}

printf($resultado, implode(', ',$numeros_sorteados), $bits, $semilla, sha1($generador_hash), $generador_hash);

