<?php

class BolilleroTest extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage El "mayor numero" (valor 0) debe ser un numero mayor o igual a 1.
     **/
    public function testConstructorMayorNumeroInvalidoLanzaInvalidArgumentExceptionConSortear() {
      $bolillero = new Cespi\SipecuBundle\Util\Bolillero(0);
      $bolillero->sortear();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage No hay números para sortear en este bolillero
     **/
    public function testConstructorSinMayorNumeroLanzaLogicExceptionConSortear() {
      $bolillero = new Cespi\SipecuBundle\Util\Bolillero();
      $bolillero->sortear();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage No pueden generarse números porque ya existen en este bolillero.
     **/
    public function testGenerarNumerosHastaValidaQueNoHayaNumerosLanzaLogicExceptionConSortear() {
      $bolillero = new Cespi\SipecuBundle\Util\Bolillero(3);
      $bolillero->generarNumerosHasta(2);
      $bolillero->sortear();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage El "mayor numero" (valor -1) debe ser un numero mayor o igual a 1.
     **/
    public function testConstructorMayorNumeroNegativoLanzaInvalidArgumentExceptionConSortear() {
      $bolillero = new Cespi\SipecuBundle\Util\Bolillero(-1);
      $bolillero->sortear();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Ya se realizó un sorteo en este bolillero
     **/
    public function testSortearLanzaLogicExceptionSiYaSeSorteo() {
      $bolillero = new Cespi\SipecuBundle\Util\Bolillero(2);
      $bolillero->sortear();
      $bolillero->sortear();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage No pueden generarse números porque ya se realizó un sorteo en este bolillero.
     **/
    public function testGenerarNumerosHastaValidaQueElBolilleroNoSeHayaSorteado() {
      $bolillero = new Cespi\SipecuBundle\Util\Bolillero(2);
      $bolillero->sortear();
      $bolillero->generarNumerosHasta(2);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage El "mayor numero" (valor a) debe ser un numero mayor o igual a 1.
     **/
    public function testGenerarNumerosHastaValidaQueEsNumero() {
      $bolillero = new Cespi\SipecuBundle\Util\Bolillero();
      $bolillero->generarNumerosHasta('a');
      $bolillero->sortear();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage El "mayor numero" (valor -1) debe ser un numero mayor o igual a 1.
     **/
    public function testGenerarNumerosHastaValidaQueEsNumeroPositivo() {
      $bolillero = new Cespi\SipecuBundle\Util\Bolillero();
      $bolillero->generarNumerosHasta(-1);
      $bolillero->sortear();
    }




}

