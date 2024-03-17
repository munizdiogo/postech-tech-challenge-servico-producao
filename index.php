<?php

// RESPONSÁVEL POR ACOMPANHAR A PRODUÇÃO/FILA DE PEDIDOS E ATUALIZAÇÃO DE STATUS.

header('Content-Type: application/json; charset=utf-8');

require "./config.php";
require "./utils/RespostasJson.php";
require "./utils/EnviarEmail.php";
require "./src/Controllers/PedidoController.php";
require "./src/Controllers/AutenticacaoController.php";
require "./src/External/MySqlConnection.php";

use Producao\External\MySqlConnection;
use Producao\Controllers\PedidoController;
use Autenticacao\Controllers\AutenticacaoController;

$dbConnection = new MySqlConnection();
$pedidoController = new PedidoController();

if (!empty($_GET["acao"])) {
    switch ($_GET["acao"]) {

        case "obterPedidos":
            $pedidos = $pedidoController->obterPedidos($dbConnection);
            if (empty($pedidos)) {
                retornarRespostaJSON("Nenhum pedido encontrado.", 200);
                exit;
            }
            retornarRespostaJSON($pedidos, 200);
            break;

        case "atualizarStatusPedido":
            $id = !empty($_POST["id"]) ? (int)$_POST["id"] : 0;
            $cpf = !empty($_POST["cpf"]) ? str_replace([".", "-"], "", $_POST["cpf"]) : "";
            $status = $_POST["status"] ?? "";
            $atualizarStatusPedido = $pedidoController->atualizarStatusPedido($dbConnection, $id, $status);

            if (!$atualizarStatusPedido) {
                retornarRespostaJSON("Ocorreu um erro ao atualizar o status do pedido.", 500);
                exit;
            }

            $autenticacaoController = new AutenticacaoController();

            $dadosCliente = $autenticacaoController->obterPorCpf($dbConnection, $cpf);
            $destinatario = $dadosCliente["email"];
            $nome = $dadosCliente["nome"];

            switch ($status) {
                case "pronto":
                    $assunto = "Pedido: " . $id . " - Pedido Pronto";
                    $mensagem = "Seu pedido está pronto e disponível para retirada! :D";
                    break;

                case "finalizado":
                    $assunto = "Pedido: " . $id . " - Pedido Entregue";
                    $mensagem = "Seu pedido foi entregue! Te desejamos uma ótima refeição.";
                    break;
            }

            enviarEmail($destinatario, $nome, $assunto, $mensagem);

            retornarRespostaJSON("Status do pedido atualizado com sucesso.", 200);
            break;

        default:
            echo '{"mensagem": "A ação informada é inválida."}';
            http_response_code(400);
    }
}
