# Projeto 1º Bimestre

Sistema web feito em PHP para exercitar Programação Orientada a Objetos com formulários, classes, validações e rotas usando Slim Framework.

## Visão Geral

O projeto reúne pequenos exercícios em um único sistema:

- cálculo de IMC
- cálculo de média escolar
- cálculo de salário
- identificação e cálculo de triângulos
- controle simples de produtos/estoque

Cada funcionalidade possui:

- uma classe PHP responsável pelas regras de negócio
- um formulário HTML para entrada de dados
- uma rota em `public/index.php` para acessar o projeto
## Tecnologias Utilizadas

- PHP
- Slim Framework 4
- Slim PSR-7
- HTML5
- CSS3
- Bootstrap 5
- Composer

## Estrutura do Projeto

```bash
Projeto_1bim/
├── public/
│   ├── index.php
│   ├── index.html
│   ├── style.css
│   ├── FormularioPessoas.html
│   ├── FormularioNotas.html
│   ├── FormularioFuncionarios.html
│   ├── FormularioTrinagulo.html
│   ├── Produtos.html
│   ├── ProdutosAdicionar.html
│   └── ProdutosRemover.html
├── src/
│   ├── Pessoa/Pessoa.php
│   ├── Notas/Notas.php
│   ├── Funcionarios/Funcionario.php
│   ├── Geometria/Triangulo.php
│   └── Produtos/Produtos.php
├── vendor/
├── composer.json
└── Readme.md
```

## Funcionalidades

### 1. IMC

Classe: `src/Pessoa/Pessoa.php`

Responsabilidades:

- validar nome, altura e peso
- calcular IMC
- classificar o resultado

### 2. Média Escolar

Classe: `src/Notas/Notas.php`

Responsabilidades:

- validar nome do aluno
- validar notas entre 0 e 10
- calcular média
- informar situação do aluno

### 3. Salário

Classe: `src/Funcionarios/Funcionario.php`

Responsabilidades:

- validar nome, valores e horas
- calcular salário com horas normais e extras

### 4. Triângulo

Classe: `src/Geometria/Triangulo.php`

Responsabilidades:

- validar os lados
- verificar existência do triângulo
- identificar o tipo
- calcular perímetro
- calcular área com fórmula de Heron

### 5. Produtos

Classe: `src/Produtos/Produtos.php`

Responsabilidades:

- validar nome, preço e estoque
- adicionar itens
- remover itens
- calcular valor total em estoque

Também existem duas telas separadas para:

- adicionar produtos
- remover produtos

## Como Executar

### 1. Instalar as dependências

```bash
composer install
```

### 2. Iniciar o servidor PHP

```bash
php -S localhost:8000 -t public/

## pode variar de maquina à maquina
php -S localhost:8080 -t public/

##se o composer so estiver em seu pen-drive
C:\xamp\php\php.exe -S localhost:8080 -t public/
```

### 3. Abrir no navegador

```text
http://localhost:8000/index.html

http://localhost:8080/index.html
```

## Rotas Principais

| Rota | Descrição |
|------|-----------|
| `/Pessoas/imc` | Calcula o IMC |
| `/Notas/media` | Calcula a média do aluno |
| `/Funcionarios/salario` | Calcula o salário |
| `/triangulo/calcular` | Calcula tipo, perímetro e área do triângulo |
| `/produtos/adicionar` | Processa a adição de produtos |
| `/produtos/remover` | Processa a remoção de produtos |

## Interface

O sistema possui:

- menu inicial com navegação para os exercícios
- formulários padronizados em roxo
- páginas de resultado com layout visual consistente

## Objetivo Acadêmico

Este projeto foi desenvolvido com foco em praticar:

- orientação a objetos em PHP
- organização em classes
- encapsulamento e invariantes
- rotas com Slim
- integração entre formulário, regra de negócio e resposta HTML

## Autor
Thales Aandrade Biondi 2H colegios UNIVAP-centro

Projeto desenvolvido para fins de estudo no 1º bimestre.
