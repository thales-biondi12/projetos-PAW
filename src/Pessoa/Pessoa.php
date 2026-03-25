<?php
declare(strict_types=1);

namespace src\Pessoa;

use InvalidArgumentException;

class Pessoa
{
    private string $nome;
    private float $altura;
    private float $peso;

    public function __construct() {}

    // Retorna o nome da pessoa.
    public function getNome(): string
    {
        $this->validarDados();
        return $this->nome;
    }

    // Atualiza o nome e valida o valor informado.
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
        $this->validarSeCompleto();
    }

    // Retorna a altura da pessoa.
    public function getAltura(): float
    {
        $this->validarDados();
        return $this->altura;
    }

    // Atualiza a altura e valida o valor informado.
    public function setAltura(float $altura): void
    {
        $this->altura = $altura;
        $this->validarSeCompleto();
    }

    // Retorna o peso da pessoa.
    public function getPeso(): float
    {
        $this->validarDados();
        return $this->peso;
    }

    // Atualiza o peso e valida o valor informado.
    public function setPeso(float $peso): void
    {
        $this->peso = $peso;
        $this->validarSeCompleto();
    }

    public function calcularIMC(): float
    {
        // Formula do IMC: peso dividido pela altura ao quadrado.
        $this->validarDados();
        return round($this->peso / ($this->altura * $this->altura), 2);
    }

    public function classificarIMC(): string
    {
        // Classifica o IMC em uma faixa de resultado.
        $imc = $this->calcularIMC();

        if ($imc < 18.5) {
            return 'Abaixo do peso';
        }

        if ($imc < 25) {
            return 'Peso normal';
        }

        if ($imc < 30) {
            return 'Sobrepeso';
        }

        return 'Obesidade';
    }

    private function validarSeCompleto(): void
    {
        if (isset($this->nome) && isset($this->altura) && isset($this->peso)) {
            $this->validarDados();
        }
    }

    private function validarDados(): void
    {
        // Impede nome vazio, altura invalida e peso invalido.
        if (!isset($this->nome) || !isset($this->altura) || !isset($this->peso)) {
            throw new InvalidArgumentException('Os dados da pessoa devem ser informados.');
        }

        if (trim($this->nome) === '') {
            throw new InvalidArgumentException('O nome deve ser informado.');
        }

        if ($this->altura <= 0) {
            throw new InvalidArgumentException('A altura deve ser maior que zero.');
        }

        if ($this->peso <= 0) {
            throw new InvalidArgumentException('O peso deve ser maior que zero.');
        }
    }
}
