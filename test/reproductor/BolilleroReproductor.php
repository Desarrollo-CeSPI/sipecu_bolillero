<?php

/*
 * Sipecu Bolillero - Módulo que implementa el sorteo para la asignación de vacantes a los colegios de la UNLP.
 * Copyright (C) 2015 CeSPI - UNLP <sistemasacademicos@unlp.edu.ar>
 * 
 * Este archivo es parte de SIPECU.
 *
 * Bolillero.php es software libre:  puede redistribuirlo y/o modificarlo 
 * bajo los términos de la GNU General Public License v2.0 <http://www.gnu.org/licenses/gpl-2.0.html>.
 *
 * Versión: 1.0
 * 
 */

/**
 * Description of BolilleroReproductor
 *
 * @author lucianoc
 */
class BolilleroReproductor
{

    private $numeros = array();
    private $sorteado = false;
    private $numerosSorteados = array();
    private $semilla = null;
    private $strMicrotime = null;

    public function __construct($semilla, $mayorNumero = null)
    {
        $this->numeros = array();
        $this->sorteado = false;
        $this->numerosSorteados = array();
        $this->semilla = $semilla;
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
        mt_srand($this->semilla); //El valor del parámetro entero está en 32 bits, por lo que tomará los últimos 32 bits del valor (en 64bits)
    }
    
    public function getSemilla()
    {
        return $this->semilla;
    }

    public function getStrMicrotime()
    {
        return $this->strMicrotime;
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
