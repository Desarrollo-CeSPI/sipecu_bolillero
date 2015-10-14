<?php

require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'autoload.php';

$count = 100;
$numbers = 4;

function help() {
  global $argv, $count, $numbers;
  $format = <<<EOT
Uso:
 %s [-h] -n <NUM> -c <NUM> -v <NUM>

Donde:
  -h: muestra esta ayuda
  -n <NUM> especifica la cantidad de numeros a sortear. Si se omite se utiliza %d
  -c <NUM> especifica la cantidad de sorteos a realizar. Si se omite se utiliza %d

EOT;
  printf($format, $argv[0], $count, $numbers);
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
    default:
      printf("Opción errónea => %s\n\n", $opt);
      help();
      exit(1);
  }
}

for(; $count; $count--) {
  $generator = new Cespi\SipecuBundle\Util\Bolillero($numbers);
  printf("%s\n", implode(" ", array_map( function ($n) { return $n-1; }, $generator->sortear()) ));
}
