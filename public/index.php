<?php
// ARQUIVO ALTERADO: fluxo de produtos com remocao sem $_SESSION
require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use src\Funcionarios\Funcionario;
use src\Geometria\Triangulo;
use src\Notas\Notas;
use src\Pessoa\Pessoa;
use src\Produtos\Produtos;

$app = AppFactory::create();

// Função responsável por transformar arrays (nomes, preços e estoques)
// em um array de objetos da classe Produtos
function montarVetorProdutos(array $nomes, array $precos, array $estoques): array
{
    $produtos = [];

    // Percorre até 5 posições dos arrays
    for ($i = 1; $i <= 5; $i++) {

        // Pega os dados de cada posição (com valores padrão se não existir)
        $nome = $nomes[$i] ?? '';
        $preco = $precos[$i] ?? 0;
        $estoque = $estoques[$i] ?? 0;

        // Só cria o produto se os dados forem válidos
        if ($nome !== '' && $preco > 0 && $estoque >= 0) {

            // Cria um objeto Produto e define seus atributos
            $produto = new Produtos();
            $produto->setNome($nome);
            $produto->setPreco($preco);
            $produto->setQtdEstoque($estoque);

            // Adiciona o produto ao array final
            $produtos[] = $produto;
        }
    }

    // Retorna o array com todos os produtos válidos
    return $produtos;
}


// Função responsável por gerar inputs ocultos (hidden)
// a partir do array de objetos Produtos
function montarCamposOcultosProdutos(array $produtos): string
{
    $camposOcultos = '';

    // Percorre todos os produtos criados
    foreach ($produtos as $indice => $produto) {

        // Define a posição (começando em 1)
        $posicao = $indice + 1;

        // Obtém os dados do objeto
        $nome = $produto->getNome();
        $preco = $produto->getPreco();
        $qtdEstoque = $produto->getQtdEstoque();

        // Monta inputs hidden para enviar os dados no formulário
        $camposOcultos .= "
            <input type='hidden' name='nome[{$posicao}]' value='{$nome}'>
            <input type='hidden' name='preco[{$posicao}]' value='{$preco}'>
            <input type='hidden' name='estoque[{$posicao}]' value='{$qtdEstoque}'>";
    }

    // Retorna todos os campos ocultos em formato HTML
    return $camposOcultos;
}

// Rota para calcular IMC
$app->get(
    '/Pessoas/imc', 
    
    function (Request $request, Response $response) {
    // Le os dados enviados pelo formulario
    $dados = $request->getQueryParams();

    
    $nome = $dados['nome'] ?? '';
    $peso = $dados['peso'] ?? 0;
    $altura = $dados['altura'] ?? 0;
    
    // Valida os dados recebidos
    if ($nome == '' || $peso == 0 || $altura == 0) {
        $conteudo = "<p class='texto-erro'><strong>Erro:</strong> Informe nome, peso e altura.</p>";
    } else {
        $peso =  $peso;
        $altura = $altura;

        if ($peso <= 0 || $altura <= 0) {
            $conteudo = "<p class='texto-erro'><strong>Erro:</strong> Peso e altura devem ser maiores que zero.</p>";
        } else {
            // Cria o objeto e usa os metodos da classe
            $pessoa = new Pessoa();
            $pessoa->setNome($nome);
            $pessoa->setPeso($peso);
            $pessoa->setAltura($altura);

            $imc = $pessoa->calcularIMC();
            $situacao = $pessoa->classificarIMC();

            // Conteudo exibido dentro do card de resultado
            $conteudo = "
                <p class='texto-roxo'><strong>Nome:</strong> {$nome}</p>
                <p class='texto-roxo'><strong>IMC:</strong> {$imc}</p>
                <p class='texto-lilas'><strong>Situacao:</strong> {$situacao}</p>";
        }
    }

    // Monta a pagina HTML de resposta
    $resposta = "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link rel='stylesheet' href='/style.css'>
                <title>Resultado</title>
            </head>
            <body class='pagina-resultado'>
                <div class='container py-5'>
                    <h2 class='mt-4 text-center resultado-titulo'>Resultado do IMC</h2>

                    <div class='card mt-4 resultado-card'>
                        <div class='card-header resultado-header'>
                            Resultado do IMC
                        </div>

                        <div class='card-body'>
                            {$conteudo}
                        </div>
                    </div>

                    <div class='text-center'>
                        <a href='/FormularioPessoas.html' class='btn btn-roxo mt-3'>Voltar</a>
                    </div>
                </div>
            </body>
            </html>";

    $response->getBody()->write($resposta);
    return $response;
});

// Rota para calcular media e situacao do aluno
$app->get(
    '/Notas/media', 
    
    function (Request $request, Response $response) {
    // Le os dados enviados pelo formulario
    $dados = $request->getQueryParams();

    $aluno = $dados['nomeAluno'] ?? '';
    $nota1 = $dados['nota1'] ?? 0;
    $nota2 = $dados['nota2'] ?? 0;

    // Valida os dados recebidos
    if ($aluno == '' || $nota1 == 0 || $nota2 == 0) {
        $conteudo = "<p class='texto-erro'><strong>Erro:</strong> Informe o nome do aluno e as duas notas.</p>";
    } else {
        $nota1 =  $nota1;
        $nota2 = $nota2;

        if ($nota1 < 0 || $nota1 > 10 || $nota2 < 0 || $nota2 > 10) {
            $conteudo = "<p class='texto-erro'><strong>Erro:</strong> As notas devem estar entre 0 e 10.</p>";
        } else {
            // Cria o objeto e faz os calculos
            $nota = new Notas();
            $nota->setAluno($aluno);
            $nota->setNota1($nota1);
            $nota->setNota2($nota2);

            $media = $nota->CalcularMedia();
            $situacao = $nota->StatusAluno();

            // Conteudo exibido no resultado
            $conteudo = "
                <p class='texto-roxo'><strong>Nome:</strong> {$aluno}</p>
                <p class='texto-roxo'><strong>Media:</strong> {$media}</p>
                <p class='texto-lilas'><strong>Situacao:</strong> {$situacao}</p>";
        }
    }

    // Monta a pagina HTML de resposta
    $resposta = "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link rel='stylesheet' href='/style.css'>
                <title>Resultado</title>
            </head>
            <body class='pagina-resultado'>
                <div class='container py-5'>
                    <h2 class='mt-4 text-center resultado-titulo'>Resultado da Media</h2>

                    <div class='card mt-4 resultado-card'>
                        <div class='card-header resultado-header'>
                            Resultado do Aluno
                        </div>

                        <div class='card-body'>
                            {$conteudo}
                        </div>
                    </div>

                    <div class='text-center'>
                        <a href='/FormularioNotas.html' class='btn btn-roxo mt-3'>Voltar</a>
                    </div>
                </div>
            </body>
            </html>";

    $response->getBody()->write($resposta);
    return $response;
});

// Rota para calcular salario do funcionario
$app->get(
    '/Funcionarios/salario', 
    
    function (Request $request, Response $response) {
    // Le os dados enviados pelo formulario
    $dados = $request->getQueryParams();

    $nome = $dados['nomeFuncionario'] ?? '';
    $valorHora = $dados['valorHora'] ?? 0;
    $valorHoraExtra = $dados['valorHoraExtra'] ?? 0;
    $qtdHoras = $dados['qtdHoras'] ?? 0;
    $qtdHorasExtras = $dados['qtdHorasExtras'] ?? 0;

    // Valida os dados recebidos
    if ($nome == '' || $valorHora == 0 || $valorHoraExtra == 0 || $qtdHoras == 0 || $qtdHorasExtras == 0) {
        $conteudo = "<p class='texto-erro'><strong>Erro:</strong> Informe todos os dados do funcionario.</p>";
    } else {
        $valorHora =  $valorHora;
        $valorHoraExtra =  $valorHoraExtra;
        $qtdHoras =  $qtdHoras;
        $qtdHorasExtras =  $qtdHorasExtras;

        if ($valorHora < 0 || $valorHoraExtra < 0 || $qtdHoras < 0 || $qtdHorasExtras < 0) {
            $conteudo = "<p class='texto-erro'><strong>Erro:</strong> Os valores informados nao podem ser negativos.</p>";
        } else {
            // Cria o objeto e calcula o salario final
            $salario = new Funcionario();
            $salario->setNome($nome);
            $salario->setValorHora($valorHora);
            $salario->setValorHoraExtra($valorHoraExtra);
            $salario->setQtdHoras($qtdHoras);
            $salario->setQtdHorasExtras($qtdHorasExtras);
            $novoSalario = $salario->CalcularSalario();

            // Conteudo exibido no resultado
            $conteudo = "
                <p class='texto-roxo'><strong>Nome:</strong> {$nome}</p>
                <p class='texto-lilas'><strong>Salario:</strong> R$ {$novoSalario}</p>";
        }
    }

    // Monta a pagina HTML de resposta
    $resposta = "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link rel='stylesheet' href='/style.css'>
                <title>Resultado</title>
            </head>
            <body class='pagina-resultado'>
                <div class='container py-5'>
                    <h2 class='mt-4 text-center resultado-titulo'>Resultado do Salario</h2>

                    <div class='card mt-4 resultado-card'>
                        <div class='card-header resultado-header'>
                            Resultado do Funcionario
                        </div>

                        <div class='card-body'>
                            {$conteudo}
                        </div>
                    </div>

                    <div class='text-center'>
                        <a href='/FormularioFuncionarios.html' class='btn btn-roxo mt-3'>Voltar</a>
                    </div>
                </div>
            </body>
            </html>";

    $response->getBody()->write($resposta);
    return $response;
});

// Rota principal dos produtos:
// cria o vetor de objetos, calcula estoque e mostra o resultado
$app->get(
    '/produtos/adicionar', 
    
    function (Request $request, Response $response) {
    // Le os dados enviados pelo formulario
    $dados = $request->getQueryParams();

    $nomes = $dados['nome'] ?? [];
    $precos = $dados['preco'] ?? [];
    $entradas = $dados['entrada'] ?? [];
    $saidas = $dados['saida'] ?? [];
    $produtos = [];

    // Percorre os 5 produtos e guarda cada objeto em um vetor
    for ($i = 1; $i <= 5; $i++) {
        $nome = $nomes[$i] ?? '';
        $preco = (float) ($precos[$i] ?? 0);
        $entrada = (int) ($entradas[$i] ?? 0);
        $saida = (int) ($saidas[$i] ?? 0);

        if ($nome !== '' && $preco > 0 && $entrada >= 0 && $saida >= 0) {
            // Cria um objeto para cada produto e ajusta o estoque
            $produto = new Produtos();
            $produto->setNome($nome);
            $produto->setPreco($preco);
            $produto->setQtdEstoque(0);
            $produto->adicionarItens($entrada);
            $produto->removerItens($saida);

            $produtos[] = $produto;
        }
    }

    $conteudo = '';
    $estoqueTotal = 0;
    $totalItensEstoque = 0;
    $camposOcultos = montarCamposOcultosProdutos($produtos);

    if (count($produtos) === 0) {
        $conteudo = "<p class='texto-erro'><strong>Erro:</strong> Informe pelo menos um produto valido.</p>";
    }

    // Monta os cards usando o vetor de objetos Produtos
    foreach ($produtos as $produto) {
        $nome = $produto->getNome();
        $preco = $produto->getPreco();
        $qtdEstoque = $produto->getQtdEstoque();
        $valorTotal = $produto->calcularValorTotal();

        $totalItensEstoque += $qtdEstoque;
        $estoqueTotal += $valorTotal;
        $conteudo .= "
            <div class='card mt-4 resultado-card'>
                <div class='card-header resultado-header'>
                    {$nome}
                </div>
                <div class='card-body'>
                    <p class='texto-roxo'><strong>Nome do produto:</strong> {$nome}</p>
                    <p class='texto-roxo'><strong>Preco:</strong> R$ " . number_format($preco, 2, ',', '.') . "</p>
                    <p class='texto-roxo'><strong>Quantidade em estoque:</strong> {$qtdEstoque}</p>
                    <p class='texto-lilas'><strong>Valor total em estoque:</strong> R$ " . number_format($valorTotal, 2, ',', '.') . "</p>
                </div>
            </div>";
    }

    // Card final com o total de itens e valor total do estoque
    if (count($produtos) > 0) {
        $conteudo .= "
        <div class='card mt-4 resultado-destaque'>
            <div class='card-body text-center'>
                <h4>Total de Itens no Estoque</h4>
                <h2>{$totalItensEstoque}</h2>
                <hr>
                <h4>Valor Total do Estoque Inteiro</h4>
                <h2>R$ " . number_format($estoqueTotal, 2, ',', '.') . "</h2>
            </div>
        </div>
        <div class='card mt-4 resultado-card'>
            <div class='card-header resultado-header'>
                Etapa de Remocao do Estoque
            </div>
            <div class='card-body text-center'>
                <p class='texto-roxo mb-3'>Os produtos do vetor ja estao carregados. Agora voce pode abrir a tela e remover sem preencher tudo de novo.</p>
                <form action='/produtos/remover/form' method='get'>
                    {$camposOcultos}
                    <button class='btn btn-voltar'>Abrir Tela de Remocao</button>
                </form>
            </div>
        </div>";
    }

    // Monta a pagina HTML de resposta
    $resposta = "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link rel='stylesheet' href='/style.css'>
                <title>Resultado dos Produtos</title>
            </head>
            <body class='pagina-resultado'>
                <div class='container py-5'>
                    <div class='row justify-content-center'>
                        <div class='col-md-10'>
                            <h2 class='mt-4 text-center resultado-titulo'>Resultado dos Produtos</h2>
                            {$conteudo}
                            <div class='text-center mt-3'>
                                <a href='/ProdutosAdicionar.html' class='btn btn-roxo'>Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>";

    $response->getBody()->write($resposta);
    return $response;
});

// Rota da tela de remocao:
// recebe o mesmo vetor de produtos e ja monta nome, preco e estoque preenchidos
$app->get(
    '/produtos/remover/form',
    
    function (Request $request, Response $response) {
    $dados = $request->getQueryParams();

    $nomes = $dados['nome'] ?? [];
    $precos = $dados['preco'] ?? [];
    $estoques = $dados['estoque'] ?? [];
    $produtos = montarVetorProdutos($nomes, $precos, $estoques);

    $conteudo = "<form action='/produtos/remover' method='get'>";

    if (count($produtos) === 0) {
        $conteudo = "<p class='texto-erro'><strong>Erro:</strong> Nenhum produto encontrado para remover.</p>";
    } else {
        foreach ($produtos as $indice => $produto) {
            $nome = $produto->getNome();
            $preco = $produto->getPreco();
            $estoque = $produto->getQtdEstoque();
            $posicao = $indice + 1;

            $conteudo .= "
                <div class='row mb-3 produto-bloco'>
                    <div class='col-md-12'>
                        <h5 class='produto-titulo'>{$nome}</h5>
                    </div>
                    <div class='col-md-4 mb-2'>
                        <input type='text' name='nome[{$posicao}]' class='form-control' value='{$nome}' readonly>
                    </div>
                    <div class='col-md-2 mb-2'>
                        <input type='text' name='preco[{$posicao}]' class='form-control' value='{$preco}' readonly>
                    </div>
                    <div class='col-md-2 mb-2'>
                        <input type='text' name='estoque[{$posicao}]' class='form-control' value='{$estoque}' readonly>
                    </div>
                    <div class='col-md-2 mb-2'>
                        <input type='text' name='remover[{$posicao}]' class='form-control' placeholder='Remover'>
                    </div>
                </div>";
        }

        $conteudo .= "
            <div class='d-grid gap-2 mt-4'>
                <button class='btn btn-roxo'>Atualizar Estoque</button>
                <a href='/ProdutosAdicionar.html' class='btn btn-voltar'>Voltar</a>
            </div>
        </form>";
    }

    $resposta = "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link rel='stylesheet' href='/style.css'>
                <title>Tela de Remocao dos Produtos</title>
            </head>
            <body class='pagina-formulario'>
                <div class='container py-4'>
                    <div class='card form-card form-card-largo'>
                        <div class='form-header'>
                            Sistema de Exercicios
                        </div>
                        <div class='card-body'>
                            <h4 class='text-center mb-4 form-title'>Tela de Remocao dos Produtos</h4>
                            {$conteudo}
                        </div>
                    </div>
                </div>
            </body>
            </html>";

    $response->getBody()->write($resposta);
    return $response;
});

// Rota final da remocao:
// atualiza o vetor depois da saida informada e mostra o novo estoque
$app->get(
    '/produtos/remover',
    
    function (Request $request, Response $response) {
    $dados = $request->getQueryParams();

    $nomes = $dados['nome'] ?? [];
    $precos = $dados['preco'] ?? [];
    $estoques = $dados['estoque'] ?? [];
    $remover = $dados['remover'] ?? [];
    $produtos = montarVetorProdutos($nomes, $precos, $estoques);

    $conteudo = '';
    $estoqueTotal = 0;
    $totalItensEstoque = 0;
    $camposOcultos = '';

    if (count($produtos) === 0) {
        $conteudo = "<p class='texto-erro'><strong>Erro:</strong> Nenhum produto valido foi informado.</p>";
    }

    foreach ($produtos as $indice => $produto) {
        $saida = (int) ($remover[$indice + 1] ?? 0);
        if ($saida > 0) {
            $produto->removerItens($saida);
        }

        $nome = $produto->getNome();
        $preco = $produto->getPreco();
        $qtdEstoque = $produto->getQtdEstoque();
        $valorTotal = $produto->calcularValorTotal();
        $posicao = $indice + 1;

        $camposOcultos .= "
            <input type='hidden' name='nome[{$posicao}]' value='{$nome}'>
            <input type='hidden' name='preco[{$posicao}]' value='{$preco}'>
            <input type='hidden' name='estoque[{$posicao}]' value='{$qtdEstoque}'>";

        $totalItensEstoque += $qtdEstoque;
        $estoqueTotal += $valorTotal;

        $conteudo .= "
            <div class='card mt-4 resultado-card'>
                <div class='card-header resultado-header'>
                    {$nome}
                </div>
                <div class='card-body'>
                    <p class='texto-roxo'><strong>Nome do produto:</strong> {$nome}</p>
                    <p class='texto-roxo'><strong>Preco:</strong> R$ " . number_format($preco, 2, ',', '.') . "</p>
                    <p class='texto-roxo'><strong>Quantidade em estoque:</strong> {$qtdEstoque}</p>
                    <p class='texto-lilas'><strong>Valor total em estoque:</strong> R$ " . number_format($valorTotal, 2, ',', '.') . "</p>
                </div>
            </div>";
    }

    if (count($produtos) > 0) {
        $camposOcultos = montarCamposOcultosProdutos($produtos);
        $conteudo .= "
        <div class='card mt-4 resultado-destaque'>
            <div class='card-body text-center'>
                <h4>Total de Itens no Estoque</h4>
                <h2>{$totalItensEstoque}</h2>
                <hr>
                <h4>Valor Total do Estoque Inteiro</h4>
                <h2>R$ " . number_format($estoqueTotal, 2, ',', '.') . "</h2>
            </div>
        </div>
        <div class='text-center mt-4'>
            <form action='/produtos/remover/form' method='get'>
                {$camposOcultos}
                <button class='btn btn-voltar'>Remover Mais</button>
            </form>
        </div>";
    }

    $resposta = "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link rel='stylesheet' href='/style.css'>
                <title>Resultado dos Produtos</title>
            </head>
            <body class='pagina-resultado'>
                <div class='container py-5'>
                    <div class='row justify-content-center'>
                        <div class='col-md-10'>
                            <h2 class='mt-4 text-center resultado-titulo'>Estoque Atualizado</h2>
                            {$conteudo}
                            <div class='text-center mt-3'>
                                <a href='/ProdutosAdicionar.html' class='btn btn-roxo'>Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>";

    $response->getBody()->write($resposta);
    return $response;
});

// Rota para calcular dados do triangulo
$app->get(
    '/triangulo/calcular', 
    
    function (Request $request, Response $response) {
    // Le os lados enviados pelo formulario
    $dados = $request->getQueryParams();

    $ladoA = $dados['ladoA'] ?? 0;
    $ladoB = $dados['ladoB'] ?? 0;
    $ladoC = $dados['ladoC'] ?? 0;

    // Valida os dados recebidos
    if ($ladoA == 0.0 || $ladoB == 0.0 || $ladoC == 0.0) {
        $conteudo = "<p class='texto-erro'><strong>Erro:</strong> Informe os tres lados do triangulo.</p>";
    } else {
        if ($ladoA <= 0 || $ladoB <= 0 || $ladoC <= 0) {
            $conteudo = "<p class='texto-erro'><strong>Erro:</strong> Todos os lados devem ser maiores que zero.</p>";
        } elseif ($ladoA + $ladoB <= $ladoC || $ladoA + $ladoC <= $ladoB || $ladoB + $ladoC <= $ladoA) {
            $conteudo = "<p class='texto-erro'><strong>Erro:</strong> Os lados informados nao formam um triangulo valido.</p>";
        } else {
            // Cria o objeto e calcula os dados do triangulo
            $triangulo = new Triangulo();
            $triangulo->setLadoA($ladoA);
            $triangulo->setLadoB($ladoB);
            $triangulo->setLadoC($ladoC);
            $tipo = $triangulo->getTipo();
            $perimetro = number_format($triangulo->calcularPerimetro(), 2, ',', '.');
            $area = number_format($triangulo->calcularArea(), 2, ',', '.');

            // Conteudo exibido no resultado
            $conteudo = "
                <p class='texto-roxo'><strong>Lado A:</strong> {$ladoA}</p>
                <p class='texto-roxo'><strong>Lado B:</strong> {$ladoB}</p>
                <p class='texto-roxo'><strong>Lado C:</strong> {$ladoC}</p>
                <p class='texto-roxo'><strong>Tipo:</strong> {$tipo}</p>
                <p class='texto-roxo'><strong>Perimetro:</strong> {$perimetro}</p>
                <p class='texto-roxo'><strong>Area:</strong> {$area}</p>";
        }
    }

    // Monta a pagina HTML de resposta
    $resposta = "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link rel='stylesheet' href='/style.css'>
                <title>Resultado</title>
            </head>
            <body class='pagina-resultado'>
                <div class='container py-5'>
                    <h2 class='mt-4 text-center resultado-titulo'>Resultado do Triangulo</h2>

                    <div class='card mt-4 resultado-card'>
                        <div class='card-header resultado-header'>
                            Dados do Triangulo
                        </div>

                        <div class='card-body'>
                            {$conteudo}
                        </div>
                    </div>

                    <div class='text-center'>
                        <a href='/FormularioTrinagulo.html' class='btn btn-roxo mt-3'>Voltar</a>
                    </div>
                </div>
            </body>
            </html>";

    $response->getBody()->write($resposta);
    return $response;
});

$app->run();
