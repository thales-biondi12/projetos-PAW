<?php
declare(strict_types=1);

namespace src\Funcionarios;

use InvalidArgumentException;

class Funcionario
{
    private string $nome;
    private float $valorHora;
    private float $valorHoraExtra;
    private float $qtdHoras;
    private float $qtdHorasExtras;

    public function __construct() {}

    // Define o nome do funcionario.
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
        $this->validarSeCompleto();
    }

    // Define o valor da hora normal.
    public function setValorHora(float $valorHora): void
    {
        $this->valorHora = $valorHora;
        $this->validarSeCompleto();
    }

    // Define o valor da hora extra.
    public function setValorHoraExtra(float $valorHoraExtra): void
    {
        $this->valorHoraExtra = $valorHoraExtra;
        $this->validarSeCompleto();
    }

    // Define a quantidade de horas normais.
    public function setQtdHoras(float $qtdHoras): void
    {
        $this->qtdHoras = $qtdHoras;
        $this->validarSeCompleto();
    }

    // Define a quantidade de horas extras.
    public function setQtdHorasExtras(float $qtdHorasExtras): void
    {
        $this->qtdHorasExtras = $qtdHorasExtras;
        $this->validarSeCompleto();
    }

    // Calcula o salario total do funcionario considerando horas normais e extras.
    public function CalcularSalario(): float
    {
        $this->validarDados();

        // Soma o valor das horas normais com o valor das horas extras.
        $salarioNormal = $this->valorHora * $this->qtdHoras;
        $salarioExtra = $this->valorHoraExtra * $this->qtdHorasExtras;

        return round($salarioNormal + $salarioExtra, 2);
    }

    private function validarSeCompleto(): void
    {
        // So valida quando todos os dados do funcionario ja foram preenchidos.
        if (
            isset($this->nome)
            && isset($this->valorHora)
            && isset($this->valorHoraExtra)
            && isset($this->qtdHoras)
            && isset($this->qtdHorasExtras)
        ) {
            $this->validarDados();
        }
    }

    private function validarDados(): void
    {
        // Nao permite nome vazio nem valores negativos.
        if (
            !isset($this->nome)
            || !isset($this->valorHora)
            || !isset($this->valorHoraExtra)
            || !isset($this->qtdHoras)
            || !isset($this->qtdHorasExtras)
        ) {
            throw new InvalidArgumentException('Os dados do funcionario devem ser informados.');
        }

        if (trim($this->nome) === '') {
            throw new InvalidArgumentException('O nome do funcionario deve ser informado.');
        }

        if ($this->valorHora < 0 || $this->valorHoraExtra < 0) {
            throw new InvalidArgumentException('Os valores das horas nao podem ser negativos.');
        }

        if ($this->qtdHoras < 0 || $this->qtdHorasExtras < 0) {
            throw new InvalidArgumentException('As quantidades de horas nao podem ser negativas.');
        }
    }
}
