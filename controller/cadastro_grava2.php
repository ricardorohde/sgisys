<?php

session_start();

include '../view/func.php';

include '../controller/usuario_acesso.php';
$usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
$tmp = $_REQUEST['tipo_edita'] . '_alterar';
if ($usu->$tmp != 'Sim') {
    echo '<script>alert("Você não tem permissões para essa operação.")</script>';
    echo '<script>window.close("#");</script>';
}

if (isset($_REQUEST['tipo_edita'])) {
    $tipo_edita = $_REQUEST['tipo_edita'];
}


include '../model/cadastro.php';

echo '<h3>Gravando...</h3>';

$cadastro = new cadastro();
$camps = $cadastro->campo($_REQUEST['tipo_edita']);
$tp_camp = $cadastro->tipo_campo($_REQUEST['tipo_edita']);
$dados = array();
for ($i = 0; $i < count($camps); $i++) {
    if (isset($_REQUEST[$camps[$i]])) {
        if ($_SESSION['tipo_cadastro'] == 'tabela') {
            $dados[$camps[$i]] = $_REQUEST[$camps[$i]];
        } else {
            $dados[$camps[$i]] = filtra_campo($_REQUEST[$camps[$i]]);
            if ($tp_camp[$i] == 'DECIMAL') {
                $dados[$camps[$i]] = br_us($dados[$camps[$i]]);
            } elseif ($tp_camp[$i] == 'DATE') {
                if (!empty($_REQUEST[$camps[$i]])) {
                    $dados[$camps[$i]] = data_encode($dados[$camps[$i]]);
                }
            }
        }
    } else {
        $dados[$camps[$i]] = '';
    }
}

if ($_REQUEST['id'] == 'add') {
    $id = $cadastro->inserir($_REQUEST['tipo_edita']);
    $dados['id'] = $id;
} else {
    $id = $_REQUEST['id'];
}
$cadastro->gravar($_REQUEST['tipo_edita'], $camps, $dados, $id);
echo '<script>window.open(\'../view/cadastro_edita.php?tipo_edita=' . $tipo_edita . '&campo_edita=' . $_REQUEST['campo_edita'] . '&id=' . $id . '\',\'_self\');</script>';
