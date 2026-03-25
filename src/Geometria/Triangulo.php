<?php
declare(strict_types=1);

namespace src\Geometria;

use InvalidArgumentException;

class Triangulo
{
    private float $ladoA;
    private float $ladoB;
    private float $ladoC;

    public function __construct() {}

    // Define o valor do lado A.
    public function setLadoA(float $ladoA): void
    {
        $this->ladoA = $ladoA;
        $this->validarSeCompleto();
    }

    // Define o valor do lado B.
    public function setLadoB(float $ladoB): void
    {
        $this->ladoB = $ladoB;
        $this->validarSeCompleto();
    }

    // Define o valor do lado C.
    public function setLadoC(float $ladoC): void
    {
        $this->ladoC = $ladoC;
        $this->validarSeCompleto();
    }

    // Identifica se o triangulo e equilatero, isosceles ou escaleno.
    public function getTipo(): string
    {
        $this->validarLados();

        if ($this->ladoA === $this->ladoB && $this->ladoB === $this->ladoC) {
            return 'Equilatero';
        }

        if (
            $this->ladoA === $this->ladoB
            || $this->ladoA === $this->ladoC
            || $this->ladoB === $this->ladoC
        ) {
            return 'Isosceles';
        }

        return 'Escaleno';
    }

    // Soma os tres lados para obter o perimetro.
    public function calcularPerimetro(): float
    {
        $this->validarLados();

        return $this->ladoA + $this->ladoB + $this->ladoC;
    }

    public function calcularArea(): float
    {
        // Usa a formula de Heron para calcular a area.
        $this->validarLados();
        $semiperimetro = $this->calcularPerimetro() / 2;

        return sqrt(
            $semiperimetro
            * ($semiperimetro - $this->ladoA)
            * ($semiperimetro - $this->ladoB)
            * ($semiperimetro - $this->ladoC)
        );
    }

    private function validarSeCompleto(): void
    {
        // So valida quando os tres lados ja foram informados.
        if (isset($this->ladoA) && isset($this->ladoB) && isset($this->ladoC)) {
            $this->validarLados();
        }
    }

    // Verifica se os lados sao positivos e se respeitam a existencia do triangulo.
    private function validarLados(): void
    {
        if (!isset($this->ladoA) || !isset($this->ladoB) || !isset($this->ladoC)) {
            throw new InvalidArgumentException('Os tres lados devem ser informados.');
        }

        if ($this->ladoA <= 0 || $this->ladoB <= 0 || $this->ladoC <= 0) {
            throw new InvalidArgumentException('Todos os lados devem ser maiores que zero.');
        }

        if (
            $this->ladoA + $this->ladoB <= $this->ladoC
            || $this->ladoA + $this->ladoC <= $this->ladoB
            || $this->ladoB + $this->ladoC <= $this->ladoA
        ) {
            throw new InvalidArgumentException('Os lados informados nao formam um triangulo valido.');
        }
    }
}
