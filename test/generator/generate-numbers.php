<?php

require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'autoload.php';

$count = 100;
$numbers = 4;
$seed = 0;

function help() {
  global $argv, $count, $numbers, $seed;
  $format = <<<EOT
Uso:
 %s [-h] -n <NUM> -c <NUM> -v <NUM>

Donde:
  -h: muestra esta ayuda
  -n <NUM> especifica la cantidad de numeros a sortear. Si se omite se utiliza %d
  -c <NUM> especifica la cantidad de sorteos a realizar. Si se omite se utiliza %d
  -s <NUM> especifica un valor externo a utilizar para generar la semilla. Si se omite se utiliza %d

EOT;
  printf($format, $argv[0], $count, $numbers, $seed);
}

$options = getopt("s:c:n:h::");

foreach($options as $opt => $value) {
  switch($opt) {
    case 'h':
      help();
      exit(0);
    case 'c':
      $count = intval($value);
      break;
    case 'n':
      $numbers = intval($value);
      break;
    case 's':
      $seed = intval($value);
      break;
    default:
      printf("Opción errónea => %s\n\n", $opt);
      help();
      exit(1);
  }
}

for(; $count; $count--) {
  $generator = new Cespi\SipecuBundle\Util\Bolillero($seed, $numbers);
  printf("%s\n", implode(" ", $generator->sortear()));
}
