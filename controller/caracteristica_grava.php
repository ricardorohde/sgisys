<?php

session_start();

include '../model/caracteristica.php';

echo '<h3>Gravando...</h3>';

$caracteristica = new caracteristica();

$nome = $_POST['nome'];
if ($_POST['id'] == 'add') {
    $id = $caracteristica->inserir();
    $dados['id'] = $id;
} else {
    $id = $_POST['id'];
}

$caracteristica->gravar($id, $nome);
echo '<script>window.open(\'../view/caracteristicas.php\',\'_self\');</script>';
//echo '<a href="#" onclick="window.open(\'../view/caracteristica.php?id=' . $id . '\',\'_self\');">voltar</a>';
