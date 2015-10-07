<?php

namespace Cespi\SipecuBundle\Util;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Bolillero
 *
 * @author lucianoc
 */
class Bolillero
{

    private $numeros = array();
    private $sorteado = false;
    private $numerosSorteados = array();
    private $momento = null;
    private $generados = false;
    private $hash = null;

    public function __construct($mayorNumero = null)
    {
        $this->numeros = array();
        $this->sorteado = false;
        $this->numerosSorteados = array();
        $this->momento = null;
        $this->generados = false;
        if (null !== $mayorNumero)
        {
            $this->generarNumerosHasta($mayorNumero);
        }
    }

    public function generarNumerosHasta($mayorNumero)
    {
        if (!is_int($mayorNumero) or $mayorNumero < 1)
        {
            throw new \InvalidArgumentException('El "mayor numero" (valor ' . $mayorNumero . ') debe ser un numero mayor o igual a 1.');
        }
        if (!empty($this->numeros))
        {
            throw new \LogicException('No pueden generarse los numeros porque ya existen algunos en el bolillero.');
        }
        $this->numeros = range(1, $mayorNumero);
        $this->generados = true;
    }
    
    public function agregarNumero($unNumero)
    {
        if (!is_int($unNumero))
        {
            throw new \InvalidArgumentException('El valor recibido debe ser un numero.');
        }
        if ($this->generados)
        {
            throw new \LogicException('No se puede agregar un numero a un conjunto de numeros generados.');
        }
        if (in_array($unNumero, $this->numeros))
        {
            throw new \LogicException('El numero ingresado ya se encuentra en el bolillero.');
        }
        $this->numeros[] = $unNumero;
    }

    private function agregarNumeroSorteado($unNumero)
    {
        $this->numerosSorteados[] = $unNumero;
    }

    public function sortear()
    {
        if ($this->getSorteado())
        {
            throw new \LogicException('Ya se realizó un sorteo en este bolillero');
        }
        while ($this->quedanNumeros()) {
            $this->sacarNumero();
        }

        return $this->getNumerosSorteados();
    }

    public function quedanNumeros()
    {

        return (!empty($this->numeros));
    }

    public function sacarNumero()
    {
        if (!$this->quedanNumeros())
        {
            throw new UnderflowException('No hay numeros en el bolillero');
        }
        $cantidadDeNumeros = count($this->numeros); //Cantidad de bolillas restantes
        $posicion = mt_rand(0, $cantidadDeNumeros - 1); //Giro el bolillero: Elijo una posición al azar (Mersenne Twister: http://php.net/manual/es/function.mt-rand.php)
        $numeroObtenido = $this->numeros[$posicion]; //Obtengo el número de la bolilla de esa posición
        $this->quitarNumero($posicion); //Quito ese número ("bolilla") del bolillero
        $this->agregarNumeroSorteado($numeroObtenido); //Guardo el resultado a la lista de numeros sorteados
        if (1 === $cantidadDeNumeros)
        {
            $this->finSorteo();
        }

        return $numeroObtenido;
    }

    private function quitarNumero($posicion)
    {
        unset($this->numeros[$posicion]); // Quito esa "bolilla" del bolillero
        $this->numeros = array_values($this->numeros); // Reacomodo el hueco que generó el número que saqué
    }
    
    public function getNumerosSorteados()
    {

        return $this->numerosSorteados;
    }

    public function getMomento()
    {

        return $this->momento;
    }

    public function getSorteado()
    {

        return $this->sorteado;
    }

    private function finSorteo()
    {
        $this->marcarSorteado();
        $this->marcarMomento();
        $this->generarHash();
    }
    
    private function generarHash()
    {
        $this->hash = sha1($this->getMomento().';'.implode(',', $this->getNumerosSorteados())); //http://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string/13733588#13733588
    }
    
    public function getHash()
    {
        if (!$this->getSorteado())
        {
            throw new \LogicException('No se puede obtener el token si no se finalizó el sorteo.');
        }
        return $this->hash;
    }

    private function marcarSorteado()
    {
        $this->sorteado = true;
    }

    private function marcarMomento()
    {
        $this->momento = time();
    }
    
    public function __toString()
    {
        return implode(',', $this->getNumerosSorteados());
    }

}
