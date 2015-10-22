# sipecu_bolillero
Proyecto del bolillero virtual utilizado para sorteos


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
php test/generator/generate-numbers.php -n 10 -c 2000000 > res_20_2.000.000.txt
php test/generator/generate-numbers.php -n 30 -c 10000000 > res_30_10.000.000.txt
php test/generator/generate-numbers.php -n 280 -c 10000000 > res_280_10.000.000.txt
php test/generator/generate-numbers.php -n 780 -c 5000000 > res_280_5.000.000.txt
php test/generator/generate-numbers.php -n 1500 -c 1000000 > res_1500_1.000.000.txt
```
