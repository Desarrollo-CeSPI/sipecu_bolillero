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

    public function __construct()
    {
        $this->numeros = array();
        $this->sorteado = false;
        $this->numerosSorteados = array();
        $this->momento = null;
        $this->generados = false;
    }

    public function agregarNumero($unNumero)
    {
        if (!is_int($unNumero))
        {
            throw new \Exception('El valor recibido debe ser un numero.');
        }
        if ($this->generados)
        {
            throw new \Exception('No se puede agregar un numero a un conjunto de numeros generados.');
        }
        $this->numeros[] = $unNumero;
    }

    public function generarNumerosHasta($mayorNumero)
    {
        if ($mayorNumero < 1 or ! is_int($mayorNumero))
        {
            throw new \Exception('El "mayor numero" (valor ' . $mayorNumero . ') debe ser un numero mayor o igual a 1.');
        }
        if (!empty($this->numeros))
        {
            throw new \Exception('No pueden generarse los numeros porque ya existen algunos en el bolillero.');
        }
        $this->numeros = range(1, $mayorNumero);
        $this->generados = true;
    }

    private function agregarNumeroSorteado($unNumero)
    {
        $this->numerosSorteados[] = $unNumero;
    }

    public function sortear()
    {
        if ($this->getSorteado())
        {
            throw new \Exception('Ya se realizó un sorteo en este bolillero');
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
            throw new Exception('No hay numeros en el bolillero');
        }
        $cantidadDeNumeros = count($this->numeros); //Cantidad de bolillas restantes
        $posicion = mt_rand(0, $cantidadDeNumeros - 1); //Elijo una posición al azar (Mersenne Twister: http://php.net/manual/es/function.mt-rand.php)
        $numeroObtenido = $this->numeros[$posicion]; // Obtengo el número de la bolilla de esa posición
        unset($this->numeros[$posicion]); // Quito esa "bolilla" del bolillero
        $this->numeros = array_values($this->numeros); // Reacomodo las posiciones de los numeros restantes
        $this->agregarNumeroSorteado($numeroObtenido); // Guardo el resultado
        if (1 === $cantidadDeNumeros)
        {
            $this->finSorteo();
        }

        return $numeroObtenido;
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
    }

    private function marcarSorteado()
    {
        $this->sorteado = true;
    }

    private function marcarMomento()
    {
        $this->momento = time();
    }

}
