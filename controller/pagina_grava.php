<?php

session_start();

include '../model/pagina.php';

echo '<h3>Gravando...</h3>';

$pagina = new pagina();

if ($_POST['id'] == 'add') {
    $id = $pagina->inserir();
    $dados['id'] = $id;
} else {
    $id = $_POST['id'];
}

$pagina->gravar($id, $_POST['opcao'], $_POST['titulo'], $_POST['pagina'], $_POST['xconteudo']);
echo '<script>window.open(\'../view/paginas.php\',\'_self\');</script>';
//echo '<a href="#" onclick="window.open(\'../view/pagina.php?id=' . $id . '\',\'_self\');">voltar</a>';
