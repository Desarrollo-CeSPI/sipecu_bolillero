# sipecu_bolillero
Proyecto del bolillero virtual utilizado para sorteos


## Ejemplo de uso
Sortear 10 números (del 1 al 10):

```php
$bolillero = new \Cespi\SipecuBundle\Util\Bolillero(10);
$sorteados = $bolillero->sortear();
var_dump($sorteados);
```
$sorteados devolverá: (al azar)
```
array(10) { [0]=> int(2) [1]=> int(6) [2]=> int(3) [3]=> int(7) [4]=> int(4) [5]=> int(9) [6]=> int(8) [7]=> int(5) [8]=> int(10) [9]=> int(1) } 
```




