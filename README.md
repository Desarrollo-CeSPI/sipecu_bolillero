# sipecu_bolillero
Proyecto del bolillero virtual utilizado para sorteos

## Ejemplo de uso
```php
$bolillero = new \Cespi\SipecuBundle\Util\Bolillero();
$bolillero->generarNumerosHasta(100);
$sorteados = $bolillero->sortear();
var_dump($sorteados);
```
