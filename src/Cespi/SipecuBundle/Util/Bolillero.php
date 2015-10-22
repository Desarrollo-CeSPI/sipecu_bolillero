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
    private $semilla = null;

    public function __construct($mayorNumero = null)
    {
        $this->numeros = array();
        $this->sorteado = false;
        $this->numerosSorteados = array();
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
        if ($this->getSorteado())
        {
            throw new \LogicException('No pueden generarse números porque ya se realizó un sorteo en este bolillero.');
        }
        if (!empty($this->numeros))
        {
            throw new \LogicException('No pueden generarse números porque ya existen en este bolillero.');
        }
        $this->numeros = range(1, $mayorNumero);
    }
    
    private function generarSemilla()
    {
        $this->semilla = microtime(true); // microsegundos hasta la fecha (float: "segundos.microsegundos", para arquitecturas de 32 y 64 bits, ya que el tamaño del entero en microsegundos con 6 decimales de precisión no entra en 32bits, y el valor variaría dependiendo de la arquitectura)
        mt_srand($this->semilla); //El valor del parámetro entero está en 32 bits, por lo que tomará los últimos 32 bits del valor (de float)
    }
    
    public function getSemilla()
    {
        return $this->semilla;
    }
    
   
    public function sortear()
    {
        if ($this->getSorteado())
        {
            throw new \LogicException('Ya se realizó un sorteo en este bolillero');
        }
        if (empty($this->numeros))
        {
            throw new \LogicException('No hay números para sortear en este bolillero');
        }
        $this->inicioSorteo();
        $hasta = count($this->numerosSorteados) - 1;
        for ($i = 0; $i < $hasta; $i++)
        {
            $j = mt_rand($i, $hasta);
            self::intercambiar($this->numerosSorteados[$i], $this->numerosSorteados[$j]); //intercambio de valores
            }
        $this->finSorteo();

        return $this->getNumerosSorteados();
    }

    private static function intercambiar(&$x,&$y) {
        $tmp=$x;
        $x=$y;
        $y=$tmp;
    }
    
    public function getNumerosSorteados()
    {

        return $this->numerosSorteados;
    }

    public function getSorteado()
    {

        return $this->sorteado;
    }
    
    private function inicioSorteo()
    {
        $this->generarSemilla();
        $this->numerosSorteados = array_values($this->numeros);
    }
    
    private function finSorteo()
    {
        $this->marcarSorteado();
    }
    
    private function marcarSorteado()
    {
        $this->sorteado = true;
    }
    
    public function __toString()
    {
        return implode(' ', $this->getNumerosSorteados());
    }

}
