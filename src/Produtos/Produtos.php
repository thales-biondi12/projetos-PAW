<?php
declare(strict_types=1);

namespace src\Produtos;

use InvalidArgumentException;

class Produtos
{
    private string $nome;
    private float $preco;
    private int $qtdEstoque;

    public function __construct() {}

    // Define o nome do produto.
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
        $this->validarSeCompleto();
    }

    // Retorna o nome do produto.
    public function getNome(): string
    {
        $this->validarDados();
        return $this->nome;
    }

    // Define o preco unitario do produto.
    public function setPreco(float $preco): void
    {
        $this->preco = $preco;
        $this->validarSeCompleto();
    }

    // Retorna o preco unitario do produto.
    public function getPreco(): float
    {
        $this->validarDados();
        return $this->preco;
    }

    // Define a quantidade atual em estoque.
    public function setQtdEstoque(int $qtdEstoque): void
    {
        $this->qtdEstoque = $qtdEstoque;
        $this->validarSeCompleto();
    }

    // Retorna a quantidade atual em estoque.
    public function getQtdEstoque(): int
    {
        $this->validarDados();
        return $this->qtdEstoque;
    }

    // Mantem compatibilidade com o restante do projeto.
    public function setQuantidadeEstoque(int $quantidadeEstoque): void
    {
        $this->setQtdEstoque($quantidadeEstoque);
    }

    // Mantem compatibilidade com o restante do projeto.
    public function getQuantidadeEstoque(): int
    {
        return $this->getQtdEstoque();
    }

    // Adiciona uma quantidade de itens ao estoque.
    public function adicionarItens(int $quantidade = 1): int
    {
        $this->validarDados();

        if ($quantidade < 0) {
            throw new InvalidArgumentException('A quantidade para adicionar nao pode ser negativa.');
        }

        $this->qtdEstoque += $quantidade;

        return $this->qtdEstoque;
    }

    // Remove uma quantidade de itens do estoque sem deixar negativo.
    public function removerItens(int $quantidade = 1): int
    {
        $this->validarDados();

        if ($quantidade < 0) {
            throw new InvalidArgumentException('A quantidade para remover nao pode ser negativa.');
        }

        $this->qtdEstoque -= $quantidade;

        if ($this->qtdEstoque < 0) {
            $this->qtdEstoque = 0;
        }

        return $this->qtdEstoque;
    }

    // Multiplica o preco unitario pela quantidade atual em estoque.
        public function calcularValorTotal(): float
    {
        $this->validarDados();
        return round($this->preco * $this->qtdEstoque, 2);
    }

    private function validarSeCompleto(): void
    {
        // So valida quando nome, preco e estoque ja estiverem definidos.
        if (isset($this->nome) && isset($this->preco) && isset($this->qtdEstoque)) {
            $this->validarDados();
        }
    }

    private function validarDados(): void
    {
        // Nao permite nome vazio, preco negativo ou estoque negativo.
        if (!isset($this->nome) || !isset($this->preco) || !isset($this->qtdEstoque)) {
            throw new InvalidArgumentException('Os dados do produto devem ser informados.');
        }

        if (trim($this->nome) === '') {
            throw new InvalidArgumentException('O nome do produto deve ser informado.');
        }

        if ($this->preco < 0) {
            throw new InvalidArgumentException('O preco do produto nao pode ser negativo.');
        }

        if ($this->qtdEstoque < 0) {
            throw new InvalidArgumentException('A quantidade em estoque nao pode ser negativa.');
        }
    }
}
