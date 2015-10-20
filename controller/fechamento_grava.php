<?php

session_start();

include '../model/fechamento.php';
include 'funcoes.php';

include '../controller/usuario_acesso.php';
$usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
if ($usu->financeiro != 'Sim') {
    echo '<script>alert("Você não tem permissões para essa operação.")</script>';
    echo '<script>window.open(\'../view/home.php\',\'_self\');</script>';
    exit();
}

echo '<h3>Gravando...</h3>';

$fechamento = new fechamento();

if ($_POST['id'] == 'add') {
    $id = $fechamento->inserir();
} else {
    $id = $_POST['id'];
}

$dados = array();

$dados['data'] = data_encode($_POST['data']);
$dados['proposta'] = $_POST['proposta'];
$dados['data_proposta'] = data_encode($_POST['data_proposta']);
$dados['validade_proposta'] = data_encode($_POST['validade_proposta']);
$dados['valor_proposta'] = br_us($_POST['valor_proposta']);

$dados['corretor1'] = $_POST['corretor1'];
$dados['corretor2'] = $_POST['corretor2'];
$dados['corretor3'] = $_POST['corretor3'];
$dados['corretor4'] = $_POST['corretor4'];
$dados['corretor5'] = $_POST['corretor5'];
$dados['comissao1'] = $_POST['comissao1'];
$dados['comissao2'] = $_POST['comissao2'];
$dados['comissao3'] = $_POST['comissao3'];
$dados['comissao4'] = $_POST['comissao4'];
$dados['comissao5'] = $_POST['comissao5'];
$dados['valor_comissao1'] = br_us($_POST['valor_comissao1']);
$dados['valor_comissao2'] = br_us($_POST['valor_comissao2']);
$dados['valor_comissao3'] = br_us($_POST['valor_comissao3']);
$dados['valor_comissao4'] = br_us($_POST['valor_comissao4']);
$dados['valor_comissao5'] = br_us($_POST['valor_comissao5']);

$dados['forma_pagamento1'] = $_POST['forma_pagamento1'];
$dados['forma_pagamento2'] = $_POST['forma_pagamento2'];
$dados['forma_pagamento3'] = $_POST['forma_pagamento3'];
//$dados['forma_pagamento4'] = $_POST['forma_pagamento4'];
//$dados['forma_pagamento5'] = $_POST['forma_pagamento1'];
$dados['valor_pagamento1'] = br_us($_POST['valor_pagamento1']);
$dados['valor_pagamento2'] = br_us($_POST['valor_pagamento2']);
$dados['valor_pagamento3'] = br_us($_POST['valor_pagamento3']);
//$dados['valor_pagamento4'] = br_us($_POST['valor_pagamento4']);
//$dados['valor_pagamento5'] = br_us($_POST['valor_pagamento5']);
$dados['data_pagamento1'] = data_encode($_POST['data_pagamento1']);
$dados['data_pagamento2'] = data_encode($_POST['data_pagamento2']);
$dados['data_pagamento3'] = data_encode($_POST['data_pagamento3']);
//$dados['data_pagamento4'] = data_encode($_POST['data_pagamento4']);
//$dados['data_pagamento5'] = data_encode($_POST['data_pagamento5']);

$dados['comissao_imobiliaria'] = $_POST['comissao_imobiliaria'];
$dados['valor_comissao_imobiliaria'] = br_us($_POST['valor_comissao_imobiliaria']);
$dados['data_pagamento_imobiliaria'] = data_encode($_POST['data_pagamento_imobiliaria']);

$dados['comprador'] = $_POST['comprador'];
$dados['proprietario'] = $_POST['proprietario'];
$dados['endereco'] = $_POST['endereco'];
$dados['ref'] = $_POST['ref'];
$dados['valor'] = br_us($_POST['valor']);


$fechamento->gravar($id, $dados);
echo '<script>window.open(\'../view/fechamento.php?id=' . $id . '\',\'_self\');</script>';
//echo '<a href="#" onclick="window.open(\'../view/fechamento.php?id=' . $id . '\',\'_self\');">voltar</a>';
