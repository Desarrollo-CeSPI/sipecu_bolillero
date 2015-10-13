# sipecu_bolillero
Proyecto del bolillero virtual utilizado para sorteos


## Ejemplo de uso
Sortear 10 números (del 1 al 10), con un valor externo 98765 (elegido al momento del sorteo):

```php
$bolillero = new \Cespi\SipecuBundle\Util\Bolillero(98765, 10);
$sorteados = $bolillero->sortear();
var_dump($sorteados);
```
`$sorteados` devolverá: (al azar)
```
array(10) { [0]=> int(2) [1]=> int(6) [2]=> int(3) [3]=> int(7) [4]=> int(4) [5]=> int(9) [6]=> int(8) [7]=> int(5) [8]=> int(10) [9]=> int(1) } 
```

Para auditoría:
```php
echo 'Ultimo sorteo: Momento: '.$bolillero->getMomento().' ['.$bolillero->getHash().'] Semilla: '.$bolillero->getSemilla().' Valor externo: '.$bolillero->getValorExterno();
```

Imprimirá (para otro ejemplo de sorteo):
```
Ultimo sorteo: Momento: 1444420060.0174 [7b02ca90c57f9ce544cfc45d78b82247858544b3] Semilla: 26832991562518 Valor externo: 29827
```

El hash se forma de la siguiente manera:
```php
sha1($this->getMomento().';'.$this->getSemilla().';'.$this->getValorExterno().';'.implode(',', $this->getNumerosSorteados()));
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
