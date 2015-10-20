<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SGI Fácil : : Painél Administrativo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link href="css/fontes.css" rel="stylesheet" />
        <link href="css/base.css" rel="stylesheet" />
        <link href="css/menu.css" rel="stylesheet" />
    </head>
    <body>
        <?php
        $m = 'Cadastros';
        include 'menu.php';

        if (isset($_SESSION['tipo_cadastro'])) {
            $tipo_cadastro = $_SESSION['tipo_cadastro'];
        }
        include '../controller/tabela.php';

        if (isset($_SESSION['id_cadastro'])) {
            $tcad = json_decode(tipo_cadastro_carregar($_SESSION['id_cadastro']));
            $name_tipo_cadastro = $tcad->tipo;
            $tipo_cadastro = $tcad->tabela;
            $_SESSION['tipo_cadastro'] = $tipo_cadastro;
        }
        if (isset($_SESSION['tipo_nome'])) {
            $tipo_nome = $_SESSION['tipo_nome'];
        } else {
            $tipo_nome = '';
        }
        ?>
        <div id="conteudo">
            <h3>Relatórios de <?php echo $name_tipo_cadastro; ?></h3>
            <form action="relatorio_imprime.php" target="_blank" method="POST">
                <table align="center" style="background: #fff;border: 1px solid #000; border-radius: 5px;" width="600">
                    <tr height="40">
                        <td colspan="2"><center><b>Selecione as Opções :</b></center><img src="img/Button Close-01.png" width="15" alt="Voltar" title="Voltar" style="float: right;margin-top: -20px;margin-right: 10px;cursor: pointer;" onclick="window.open('cadastros.php?id_cadastro=1&tabelas=Não', '_self');"></td>
                    </tr>
                    <tr>
                        <td>
                    <center><input type="radio" name="tipo_relatorio" value="1" checked> Relatório TIPO 1</center>
                    </td>
                    <td>
                        <?php
                        if (!empty($tipo_nome)) {
                            echo '<center><input type="radio" name="tipo_relatorio" value="2"> Relatório TIPO 2</center>';
                        }
                        ?>
                    </td>
                    <tr>
                        <td style="marging-left: 20px;">
                            <table style="border: 1px solid #ccc; border-radius: 10px;">
                                <tr>
                                    <td><input type="radio" name="com_proprietario" value="S"> Com Proprietário
                                        <br><input type="radio" name="com_proprietario" Value="N" checked> Sem Proprietário
                                    </td>
                                </tr>
                                <?php
                                if (empty($tipo_nome)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="radio" name="com_foto" value="S"> Com Foto
                                            <br><input type="radio" name="com_foto" Value="N" checked> Sem Foto
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="radio" name="com_pagto" value="S"> Com Condições de Pagamento
                                            <br><input type="radio" name="com_pagto" Value="N" checked> Sem Condições de Pagamento
                                        </td>
                                    </tr>
                                    <?php
                                } else {
                                    echo '<tr height="40">';
                                    echo '  <td colspan="3"><center><b>Tipo : ' . $tipo_nome . '</b></center></td>';
                                    echo '</tr>';
                                }
                                ?>

                                <tr>
                                    <td>
                                        <input type="radio" name="com_espaco" value="N" checked> Com Espaço Simples
                                        <br><input type="radio" name="com_espaco" Value="S"> Com Espaço Maior
                                    </td>
                                </tr>    

                            </table>

                        </td>
                        <td>
                            <table>
                                <tr>
                                    <td>
<!--                                        <input type="radio" name="com_proprietario" value="S"> Com Proprietário
                                        <br><input type="radio" name="com_proprietario" Value="N" checked> Sem Proprietário-->
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr height="80">
                        <td colspan="2"><center><input type="submit" name="bbb" value="imprimir" class="botao"></center></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>

