# sipecu_bolillero
Proyecto del bolillero virtual utilizado para sorteos. La clase del bolillero se encuentra en `sipecu_bolillero/src/Cespi/SipecuBundle/Util/Bolillero.php`


## Ejemplo de uso
Sortear 10 números (del 1 al 10):

```php
$bolillero = new \Cespi\SipecuBundle\Util\Bolillero(10);
$sorteados = $bolillero->sortear();
var_dump($sorteados);
```
`$sorteados` devolverá: (al azar)
```
array(10) { [0]=> int(2) [1]=> int(6) [2]=> int(3) [3]=> int(7) [4]=> int(4) [5]=> int(9) [6]=> int(8) [7]=> int(5) [8]=> int(10) [9]=> int(1) } 
```

El hash de validación del sorteo se podría formar de la siguiente manera:
```php
$momento = $sorteo->getMomento()->getTimestamp(); // El timestamp del mometo que finaliza y se guarda el sorteo
sha1($momento.';'.$bolillero->getSemilla().';'.implode(',', $bolillero->getNumerosSorteados()));
```

# Tests

## De Unidad

Para correr los tests de unidad:

```
php lib/phpunit-4.8.10.phar --bootstrap test/autoload.php test/unit/Bolillero
```

## Generar salida a testear distribución de aleatoriedad

Se provee de un script para generar la salida a ser utilizada como entrada en
sistemas que permiten testear la distribución de números generada en forma
aleatoria:

```
php test/generator/generate-numbers.php -h
```

El comando anterior mostrará ayuda de cómo utilizarlo. Si no se utilizan
parámetros, entonces se producen 100 lanzamientos con semilla (cero) de cinco
números cada sorteo

### Generar salidas

* Rango 10 (0..9): 2.000.000 de sorteos
* Rango 30 (0..29): 10.000.000 de sorteos
* Rango 280 (0..279): 10.000.000 de sorteos
* Rango 780 (0..779): 5.000.000 de sorteos
* Rango 1500 (0..1499): 1.000.000 de sorteos

```
php test/generator/generate-numbers.php -n 10 -c 2000000 > res_10_2.000.000.txt
php test/generator/generate-numbers.php -n 30 -c 10000000 > res_30_10.000.000.txt
php test/generator/generate-numbers.php -n 280 -c 10000000 > res_280_10.000.000.txt
php test/generator/generate-numbers.php -n 780 -c 5000000 > res_780_5.000.000.txt
php test/generator/generate-numbers.php -n 1500 -c 1000000 > res_1500_1.000.000.txt
```

## Reproducir sorteo

Se puede reproducir el sorteo conociendo los valores que lo configuran. Estos son el UNIX timestamp (segundos del momento del sorteo), los microsegundos y el número de sorteo máximo (o cantidad de números a sortear).
Para reproducirlo, está la herramienta `reproducir_sorteo.php`. Uso:

```
php test/reproductor/reproducir_sorteo.php [-h] -s <NUM> -u <NUM> -n <NUM> [-b <32|64>]
```
Donde:
  * -h: muestra la ayuda
  * -s número: segundos del momento del sorteo
  * -u número: microsegundos del momento del sorteo
  * -n número: especifica la cantidad de numeros a sortear.
  * -b 32 o 64: Bits de la versión de php. Por defecto 32 (versión utilizada en el servidor).
  
IMPORTANTE: La versión de PHP en donde se corre el sorteo es <= 5.6 (de 32 bits). Puede cambiar con versiones mayores a 7.0.

### Ejemplo
Por ejemplo, para verificar un sorteo de 9 números, que se realice el día 28 de Octubre a las 16:44:01 (GMT, sería menos 3 horas según la zona horaria de Buenos Aires, Argentina) su timestamp será 1446050641 y a los 57433 microsegundos (una millonésima de segundo). Debería correrse:

```
php test/reproductor/reproducir_sorteo.php -s 1446050641 -u 57433 -n 9
```
Esto nos devolverá:
```
* Números sorteados:

1: 2, 2: 4, 3: 5, 4: 1, 5: 3, 6: 6, 7: 9, 8: 7, 9: 8

* Semilla de 32bits: -422996327
* Hash (sha1): 5f3942c3e33702a1117b4624ab50632f09e1e6a0
* Generador de hash: 1446050641;-422996327;2,4,5,1,3,6,9,7,8
```
En primera instancia vemos los números sorteados en el órden en que fueron saliendo, indicados como `órden: número` , la semilla utilizada para iniciar el algoritmo, el hash generado y por último desde qué se genera. La función sha1 de "1446050641;-422996327;2,4,5,1,3,6,9,7,8" nos devolverá 5f3942c3e33702a1117b4624ab50632f09e1e6a0.

Con los mismos parámetros que figuran en la auditoría, se deberían obtener los mismos resultados y el mismo hash.
Es importante tener en cuenta que la versión de PHP en donde se realiza el sorteo es de 32 bits y siempre los bits de la versión en donde se realice la reproducción del sorteo, deberá ser igual o mayor a la del servidor.

