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
    private $hash = null;
    private $semilla = null;
    private $valorExterno = null;

    public function __construct($valorExterno, $mayorNumero = null)
    {
        $this->numeros = array();
        $this->sorteado = false;
        $this->numerosSorteados = array();
        $this->momento = null;
        $this->valorExterno = $valorExterno;
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
        $this->numeros = range(1, $mayorNumero);
    }
    
    private function generarSemilla()
    {
        if (null === $this->getValorExterno())
        {
            throw new \LogicException('Falta un valor externo para generar la semilla.');
        }
        $this->semilla = (microtime(true) * getmypid()) ^  (lcg_value() * 1000000);
        mt_srand($this->semilla + $this->valorExterno);
    }
    
    public function getSemilla()
    {
        return $this->semilla;
    }
    
    public function getValorExterno()
    {
        return $this->valorExterno;
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

    public function getMomento()
    {

        return $this->momento;
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
        $this->marcarMomento();
        $this->generarHash();
    }
    
    private function generarHash()
    {
        $this->hash = sha1($this->getMomento().';'.$this->getSemilla().';'.$this->getValorExterno().';'.implode(',', $this->getNumerosSorteados()));
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
        $this->momento = microtime(true);
    }
    
    public function __toString()
    {
        return implode(' ', $this->getNumerosSorteados());
    }

}
