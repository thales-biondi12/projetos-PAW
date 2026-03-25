<?php
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
    if ($nome === '' || $peso === 0 || $altura === 0) {
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
    if ($aluno === '' || $nota1 === 0 || $nota2 === 0) {
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
    if ($nome === '' || $valorHora === 0 || $valorHoraExtra === 0 || $qtdHoras === 0 || $qtdHorasExtras === 0) {
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

// Rota para adicionar produtos no estoque
$app->get(
    '/produtos/adicionar', 
    
    function (Request $request, Response $response) {
    // Le os dados enviados pelo formulario
    $dados = $request->getQueryParams();

    $produtos = [];

    $nomes = $dados['nome'] ?? [];
    $precos = $dados['preco'] ?? [];
    $adicionar = $dados['adicionar'] ?? [];

    // Percorre os 5 produtos enviados para adicionar ao estoque
    for ($i = 1; $i <= 5; $i++) {
        $nome = $nomes[$i] ?? '';
        $preco = (float) ($precos[$i] ?? 0);
        $add = (int) ($adicionar[$i] ?? 0);

        if ($nome !== '' && $preco > 0 && $add >= 0) {
            // Cria um objeto para cada produto
            $produto = new Produtos();
            $produto->setNome($nome);
            $produto->setPreco($preco);
            $produto->setQuantidadeEstoque(0);

            // Adiciona a quantidade informada de uma vez.
            $produto->adicionarItens((int) $add);

            $produtos[] = [
                'nome' => $nome,
                'preco' => $preco,
                'adicionou' => $add,
                'quantidade_final' => $produto->getQuantidadeEstoque()
            ];
        }
    }

    $conteudo = '';
    $estoqueTotal = 0;

    // Monta os cards de cada produto no resultado
    foreach ($produtos as $p) {
        $valorTotal = $p['preco'] * $p['quantidade_final'];

        $conteudo .= "
            <div class='card mt-4 resultado-card'>
                <div class='card-header resultado-header'>
                    {$p['nome']}
                </div>
                <div class='card-body'>
                    <p class='texto-roxo'><strong>Preco unitario:</strong> R$ " . number_format($p['preco'], 2, ',', '.') . "</p>";

        if ($p['adicionou'] > 0) {
            $conteudo .= "<p class='texto-roxo'><strong>Adicionou ao estoque:</strong> {$p['adicionou']} itens</p>";
        }

        $estoqueTotal += $valorTotal;

        $conteudo .= "
                    <hr>
                    <p class='texto-roxo'><strong>Quantidade final em estoque:</strong> {$p['quantidade_final']}</p>
                    <p class='texto-lilas'><strong>Valor total em estoque:</strong> R$ " . number_format($valorTotal, 2, ',', '.') . "</p>
                </div>
            </div>";
    }

    // Card final com o valor total do estoque
    $conteudo .= "
        <div class='card mt-4 resultado-destaque'>
            <div class='card-body text-center'>
                <h4>Valor Total do Estoque Inteiro</h4>
                <h2>R$ " . number_format($estoqueTotal, 2, ',', '.') . "</h2>
            </div>
        </div>";

    // Monta a pagina HTML de resposta
    $resposta = "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link rel='stylesheet' href='/style.css'>
                <title>Adicionar Produtos</title>
            </head>
            <body class='pagina-resultado'>
                <div class='container py-5'>
                    <div class='row justify-content-center'>
                        <div class='col-md-10'>
                            <h2 class='mt-4 text-center resultado-titulo'>Adicao no Estoque</h2>
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

// Rota para remover produtos do estoque
$app->get(
    '/produtos/remover', 
    
    function (Request $request, Response $response) {
    // Le os dados enviados pelo formulario
    $dados = $request->getQueryParams();
    $nomes = $dados['nome'] ?? [];
    $precos = $dados['preco'] ?? [];
    $estoques = $dados['estoque'] ?? [];
    $remover = $dados['remover'] ?? [];
    $produtos = [];
    $estoqueTotal = 0;

    for ($i = 1; $i <= 5; $i++) {
        $nome = $nomes[$i] ?? '';
        $preco = (float) ($precos[$i] ?? 0);
        $estoqueAtual = (int) ($estoques[$i] ?? 0);
        $quantidadeRemover = (int) ($remover[$i] ?? 0);

        if ($nome === '' || $preco <= 0 || $estoqueAtual < 0 || $quantidadeRemover < 0) {
            continue;
        }

        $produto = new Produtos();
        $produto->setNome($nome);
        $produto->setPreco($preco);
        $produto->setQuantidadeEstoque($estoqueAtual);
        $quantidadeFinal = $produto->removerItens($quantidadeRemover);
        $valorTotal = $produto->calcularValorTotal();
        $estoqueTotal += $valorTotal;

        $produtos[] = [
            'nome' => $nome,
            'preco' => $preco,
            'estoque_atual' => $estoqueAtual,
            'removeu' => $quantidadeRemover,
            'quantidade_final' => $quantidadeFinal,
            'valor_total' => $valorTotal
        ];
    }

    $conteudo = '';

    // Monta os cards de cada produto no resultado
    foreach ($produtos as $p) {
        $conteudo .= "
            <div class='card mt-4 resultado-card'>
                <div class='card-header resultado-header'>
                    {$p['nome']}
                </div>
                <div class='card-body'>
                    <p class='texto-roxo'><strong>Preco unitario:</strong> R$ " . number_format((float) $p['preco'], 2, ',', '.') . "</p>
                    <p class='texto-roxo'><strong>Estoque atual:</strong> {$p['estoque_atual']}</p>";

        if ($p['removeu'] > 0) {
            $conteudo .= "<p class='texto-lilas'><strong>Removeu do estoque:</strong> {$p['removeu']} itens</p>";
        }

        $conteudo .= "
                    <hr>
                    <p class='texto-roxo'><strong>Quantidade final em estoque:</strong> {$p['quantidade_final']}</p>
                    <p class='texto-lilas'><strong>Valor total em estoque:</strong> R$ " . number_format($p['valor_total'], 2, ',', '.') . "</p>
                </div>
            </div>";
    }

    $conteudo .= "
        <div class='card mt-4 resultado-destaque'>
            <div class='card-body text-center'>
                <h4>Valor Total do Estoque Restante</h4>
                <h2>R$ " . number_format($estoqueTotal, 2, ',', '.') . "</h2>
            </div>
        </div>";

    // Monta a pagina HTML de resposta
    $resposta = "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link rel='stylesheet' href='/style.css'>
                <title>Remover Produtos</title>
            </head>
            <body class='pagina-resultado'>
                <div class='container py-5'>
                    <div class='row justify-content-center'>
                        <div class='col-md-10'>
                            <h2 class='mt-4 text-center resultado-titulo'>Remocao do Estoque</h2>
                            {$conteudo}
                            <div class='text-center mt-3'>
                                <a href='/ProdutosRemover.html' class='btn btn-roxo'>Voltar</a>
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
    if ($ladoA === 0.0 || $ladoB === 0.0 || $ladoC === 0.0) {
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
