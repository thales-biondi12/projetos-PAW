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

// Rota principal dos produtos.
// Ela recebe os 5 produtos do formulario, cria os objetos, calcula o estoque
// e monta a tela de resultado com o botao para ir para a pagina de remocao.
$app->get(
    '/produtos/processar',

    function (Request $request, Response $response) {
    // Le os dados do formulario principal de produtos.
    $dados = $request->getQueryParams();

    // Organiza os dados em arrays para processar os 5 produtos.
    $nomes = $dados['nome'] ?? [];
    $precos = $dados['preco'] ?? [];
    $entradas = $dados['entrada'] ?? [];
    $saidas = $dados['saida'] ?? [];

    // Variaveis usadas para guardar objetos, resultado e totais.
    $produtos = [];
    $movimentacoes = [];
    $conteudo = '';
    $totalItens = 0;
    $valorTotalEstoque = 0;
    $camposRemocao = '';
    $formularioInvalido = false;

    // Percorre cada linha do formulario para montar os produtos.
    for ($i = 1; $i <= 5; $i++) {
        $nome = $nomes[$i] ?? '';
        $precoOriginal = $precos[$i] ?? '';
        $entradaOriginal = $entradas[$i] ?? '';
        $saidaOriginal = $saidas[$i] ?? '';
        $preco = (float) str_replace(',', '.', str_replace(['R$', ' '], '', trim((string) $precoOriginal)));
        $quantidadeEntrada = (int) trim((string) $entradaOriginal);
        $quantidadeSaida = (int) trim((string) $saidaOriginal);

        // Como os 5 produtos sao obrigatorios, nome, preco e entrada precisam ser preenchidos.
        if (
            $nome == '' ||
            $precoOriginal == '' ||
            $entradaOriginal == '' ||
            $preco <= 0
        ) {
            $formularioInvalido = true;
            break;
        }

        // Aqui o objeto Produto e criado.
        $produto = new Produtos();
        $produto->setNome($nome);
        $produto->setPreco($preco);

        // O estoque comeca em zero antes de adicionar a entrada.
        $produto->setQuantidadeEstoque(0);

        // Soma a quantidade de entrada ao estoque.
        $produto->adicionarItens($quantidadeEntrada);

        // Remove a quantidade de saida, sem deixar o estoque negativo.
        $produto->removerItens($quantidadeSaida);

        $produtos[] = $produto;
        $movimentacoes[] = [
            'entrada' => $quantidadeEntrada,
            'saida' => $quantidadeSaida
        ];

        // Prepara os campos ocultos para abrir a tela de remocao com os dados preenchidos.
        $camposRemocao .= "
            <input type='hidden' name='nome[{$i}]' value='{$produto->getNome()}'>
            <input type='hidden' name='preco[{$i}]' value='{$produto->getPreco()}'>
            <input type='hidden' name='estoque[{$i}]' value='{$produto->getQuantidadeEstoque()}'>";
    }

    // Se algum dos 5 produtos estiver errado, mostra uma unica mensagem geral de erro.
    if ($formularioInvalido || count($produtos) != 5) {
        $conteudo = "
            <div class='card mt-4 resultado-card'>
                <div class='card-header resultado-header'>
                    Erro no preenchimento
                </div>
                <div class='card-body'>
                    <p class='texto-erro'><strong>Erro:</strong> Os 5 produtos devem ser preenchidos obrigatoriamente com nome, preco e quantidade de entrada validos.</p>
                </div>
            </div>";
    // Se os 5 produtos estiverem corretos, monta os cards e o resumo do estoque.
    } elseif ($produtos != []) {
        foreach ($produtos as $indice => $produto) {
            // Pega a quantidade final que sobrou no estoque.
            $quantidadeFinal = $produto->getQuantidadeEstoque();

            // Calcula o valor total desse produto no estoque.
            $valorProduto = $produto->calcularValorTotal();
            $entrada = $movimentacoes[$indice]['entrada'];
            $saida = $movimentacoes[$indice]['saida'];

            // Soma no total geral de itens.
            $totalItens += $quantidadeFinal;

            // Soma no valor total geral do estoque.
            $valorTotalEstoque += $valorProduto;

            $conteudo .= "
                <div class='card mt-4 resultado-card'>
                    <div class='card-header resultado-header'>
                        {$produto->getNome()}
                    </div>
                    <div class='card-body'>
                        <p class='texto-roxo'><strong>Quantidade de entrada:</strong> {$entrada}</p>
                        <p class='texto-roxo'><strong>Quantidade de saida:</strong> {$saida}</p>
                        <p class='texto-roxo'><strong>Preco unitario:</strong> R$ " . number_format($produto->getPreco(), 2, ',', '.') . "</p>
                        <hr>
                        <p class='texto-roxo'><strong>Quantidade em estoque:</strong> {$quantidadeFinal}</p>
                        <p class='texto-lilas'><strong>Valor total em estoque:</strong> R$ " . number_format($valorProduto, 2, ',', '.') . "</p>
                    </div>
                </div>";
        }

        $conteudo .= "
            <div class='card mt-4 resultado-destaque'>
                <div class='card-body text-center'>
                    <h3 class='mb-3'>Resumo do Estoque</h3>
                    <div class='row justify-content-center g-3 mt-1'>
                        <div class='col-md-4'>
                            <div class='p-3'>
                                <h4>Total de itens</h4>
                                <h2>{$totalItens}</h2>
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='p-3'>
                                <h4>Valor total do estoque</h4>
                                <h2>R$ " . number_format($valorTotalEstoque, 2, ',', '.') . "</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
    }

    // Monta a pagina HTML final do estoque.
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
                            <h2 class='mt-4 text-center resultado-titulo'>Controle de Estoque</h2>
                            {$conteudo}
                            <div class='d-grid gap-2 mt-4'>
                                " . ($camposRemocao != '' ? "
                                <form action='/produtos/remover-formulario' method='get'>
                                    {$camposRemocao}
                                    <button type='submit' class='btn btn-roxo'>Remover Itens do Estoque</button>
                                </form>" : '') . "
                                <a href='/Produtos.html' class='btn btn-voltar'>Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>";

    $response->getBody()->write($resposta);
    return $response;
});

// Rota que abre a segunda tela de produtos.
// Nela os dados do array ja chegam preenchidos e o usuario digita so
// a quantidade que deseja remover de cada item.
$app->get(
    '/produtos/remover-formulario',

    function (Request $request, Response $response) {
    // Le os dados recebidos da pagina de estoque.
    $dados = $request->getQueryParams();
    $nomes = $dados['nome'] ?? [];
    $precos = $dados['preco'] ?? [];
    $estoques = $dados['estoque'] ?? [];

    // Aqui sera montado o formulario ja preenchido para remocao.
    $linhas = '';

    // Percorre os produtos recebidos e monta as linhas da tela de remocao.
    for ($i = 1; $i <= 5; $i++) {
        $nome = trim($nomes[$i] ?? '');
        $preco = $precos[$i] ?? 0;
        $estoque = $estoques[$i] ?? 0;

        

        // Mostra nome, preco e estoque bloqueados e deixa so o campo remover livre.
        $linhas .= "
            <div class='row mb-3 produto-bloco'>
                <div class='col-md-12'>
                    <h5 class='produto-titulo'>{$nome}</h5>
                </div>
                <div class='col-md-4 mb-2'>
                    <input type='text' class='form-control' value='{$nome}' readonly>
                    <input type='hidden' name='nome[{$i}]' value='{$nome}'>
                </div>
                <div class='col-md-3 mb-2'>
                    <input type='text' class='form-control' value='R$ " . number_format($preco, 2, ',', '.') . "' readonly>
                    <input type='hidden' name='preco[{$i}]' value='{$preco}'>
                </div>
                <div class='col-md-3 mb-2'>
                    <input type='text' class='form-control' value='{$estoque} em estoque' readonly>
                    <input type='hidden' name='estoque[{$i}]' value='{$estoque}'>
                </div>
                <div class='col-md-2 mb-2'>
                    <input type='text' name='remover[{$i}]' class='form-control' placeholder='Remover'>
                </div>
            </div>";
    }

    if ($linhas == '') {
        $linhas = "
            <p class='texto-erro'><strong>Erro:</strong> Nenhum produto foi enviado para a pagina de remocao.</p>";
    } else {
        $linhas = "
            <p class='text-center texto-lilas mb-4'>Os produtos ja vieram preenchidos. Agora voce so informa a quantidade que deseja remover.</p>
            <form action='/produtos/remover' method='get'>
                {$linhas}
                <div class='d-grid gap-2 mt-4'>
                    <button class='btn btn-roxo'>Confirmar Remocao</button>
                    <a href='/Produtos.html' class='btn btn-voltar'>Voltar</a>
                </div>
            </form>";
    }

    // Monta a pagina HTML onde o usuario informa quanto deseja remover.
    $resposta = "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link rel='stylesheet' href='/style.css'>
                <title>Remover Itens</title>
            </head>
            <body class='pagina-formulario'>
                <div class='container py-5'>
                    <div class='row justify-content-center'>
                        <div class='col-md-10'>
                            <div class='card form-card form-card-largo'>
                                <div class='form-header'>Sistema de Exercicios</div>
                                <div class='card-body'>
                                    <h2 class='text-center mb-4 form-title'>Remover Itens do Estoque</h2>
                                    {$linhas}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>";

    $response->getBody()->write($resposta);
    return $response;
});

// Rota final da remocao.
// Ela recebe a quantidade a remover, atualiza o estoque de cada produto
// e mostra o novo resultado com total de itens e valor total do estoque.
$app->get(
    '/produtos/remover',

    function (Request $request, Response $response) {
    // Le os dados enviados pela tela de remocao.
    $dados = $request->getQueryParams();
    $nomes = $dados['nome'] ?? [];
    $precos = $dados['preco'] ?? [];
    $estoques = $dados['estoque'] ?? [];
    $remocoes = $dados['remover'] ?? [];

    // Variaveis usadas para montar o resultado final apos remover itens.
    $conteudo = '';
    $totalItens = 0;
    $valorTotalEstoque = 0;

    // Percorre os produtos para aplicar a quantidade removida em cada um.
    for ($i = 1; $i <= 5; $i++) {
        $nome = trim((string) ($nomes[$i] ?? ''));
        $preco = (float) ($precos[$i] ?? 0);
        $estoque = (int) ($estoques[$i] ?? 0);
        $removerOriginal = trim((string) ($remocoes[$i] ?? '0'));


        if ($removerOriginal != '' && preg_match('/^[0-9]+$/', $removerOriginal) != 1) {
            $conteudo .= "
                <div class='card mt-4 resultado-card'>
                    <div class='card-header resultado-header'>{$nome}</div>
                    <div class='card-body'>
                        <p class='texto-erro'><strong>Erro:</strong> A quantidade para remover deve ser um numero inteiro.</p>
                    </div>
                </div>";
            continue;
        }

        // Converte a quantidade digitada para inteiro.
        $quantidadeRemover = (int) $removerOriginal;

        // Recria o produto com o estoque atual para aplicar a remocao.
        $produto = new Produtos();
        $produto->setNome($nome);
        $produto->setPreco($preco);
        $produto->setQuantidadeEstoque($estoque);

        // Remove a quantidade solicitada.
        $produto->removerItens($quantidadeRemover);

        // Lê a quantidade final depois da remocao.
        $quantidadeFinal = $produto->getQuantidadeEstoque();

        // Calcula quanto esse produto ainda vale no estoque.
        $valorProduto = $produto->calcularValorTotal();
        $totalItens += $quantidadeFinal;
        $valorTotalEstoque += $valorProduto;

        // Monta o card com os dados do produto depois da remocao.
        $conteudo .= "
            <div class='card mt-4 resultado-card'>
                <div class='card-header resultado-header'>{$produto->getNome()}</div>
                <div class='card-body'>
                    <p class='texto-roxo'><strong>Quantidade antes:</strong> {$estoque}</p>
                    <p class='texto-roxo'><strong>Quantidade removida:</strong> {$quantidadeRemover}</p>
                    <p class='texto-roxo'><strong>Preco unitario:</strong> R$ " . number_format($produto->getPreco(), 2, ',', '.') . "</p>
                    <p class='texto-roxo'><strong>Quantidade em estoque:</strong> {$quantidadeFinal}</p>
                    <p class='texto-lilas'><strong>Valor total em estoque:</strong> R$ " . number_format($valorProduto, 2, ',', '.') . "</p>
                </div>
            </div>";
    }

    if ($conteudo == '') {
        $conteudo = "
            <div class='card mt-4 resultado-card'>
                <div class='card-header resultado-header'>Nenhum produto processado</div>
                <div class='card-body'>
                    <p class='texto-erro'><strong>Erro:</strong> Nao foi possivel processar a remocao.</p>
                </div>
            </div>";
    } else {
        $conteudo .= "
            <div class='card mt-4 resultado-destaque'>
                <div class='card-body text-center'>
                    <h3 class='mb-3'>Estoque Apos a Remocao</h3>
                    <div class='row justify-content-center g-3 mt-1'>
                        <div class='col-md-4'>
                            <div class='p-3'>
                                <h4>Total de itens</h4>
                                <h2>{$totalItens}</h2>
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='p-3'>
                                <h4>Valor total do estoque</h4>
                                <h2>R$ " . number_format($valorTotalEstoque, 2, ',', '.') . "</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
    }

    // Monta a pagina HTML final com o resultado da remocao.
    $resposta = "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link rel='stylesheet' href='/style.css'>
                <title>Resultado da Remocao</title>
            </head>
            <body class='pagina-resultado'>
                <div class='container py-5'>
                    <div class='row justify-content-center'>
                        <div class='col-md-10'>
                            <h2 class='mt-4 text-center resultado-titulo'>Remocao do Estoque</h2>
                            {$conteudo}
                            <div class='d-grid gap-2 mt-4'>
                                <a href='/Produtos.html' class='btn btn-voltar'>Voltar para Produtos</a>
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
