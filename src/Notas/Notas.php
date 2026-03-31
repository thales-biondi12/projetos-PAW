<?php
declare(strict_types=1);

namespace src\Notas;

use InvalidArgumentException;

class Notas
{
    private string $aluno;
    private float $nota1;
    private float $nota2;

    public function __construct() {}

    // Define o nome do aluno.
    public function setAluno(string $aluno): void
    {
        $this->aluno = $aluno;
        $this->validarSeCompleto();
    }

    // Define a primeira nota do aluno.
    public function setNota1(float $nota1): void
    {
        $this->nota1 = $nota1;
        $this->validarSeCompleto();
    }

    // Define a segunda nota do aluno.
    public function setNota2(float $nota2): void
    {
        $this->nota2 = $nota2;
        $this->validarSeCompleto();
    }

    // Calcula a media aritmetica das duas notas.
    public function CalcularMedia(): float
    {
        $this->validarDados();

        return round(($this->nota1 + $this->nota2) / 2, 2);
    }

    // Determina o status do aluno com base na media calculada.
    public function StatusAluno(): string
    {
        $media = $this->CalcularMedia();

        if ($media >= 6) {
            return 'Aprovado';
        }

        if ($media <= 5) {
            return 'Recuperacao';
        }

        return 'Reprovado';
    }

    private function validarSeCompleto(): void
    {
        // So valida quando os tres dados principais ja estiverem definidos.
        if (isset($this->aluno) && isset($this->nota1) && isset($this->nota2)) {
            $this->validarDados();
        }
    }

    private function validarDados(): void
    {
        // Impede nome vazio e notas fora da escala de 0 a 10.
        if (!isset($this->aluno) || !isset($this->nota1) || !isset($this->nota2)) {
            throw new InvalidArgumentException('Os dados do aluno devem ser informados.');
        }

        if (trim($this->aluno) === '') {
            throw new InvalidArgumentException('O nome do aluno deve ser informado.');
        }

        if ($this->nota1 < 0 || $this->nota1 > 10 || $this->nota2 < 0 || $this->nota2 > 10) {
            throw new InvalidArgumentException('As notas devem estar entre 0 e 10.');
        }
    }
}
