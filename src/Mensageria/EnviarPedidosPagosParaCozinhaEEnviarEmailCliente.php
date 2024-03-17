<?php
require '../../vendor/autoload.php';
require "../External/MySqlConnection.php";
require "../Controllers/AutenticacaoController.php";
require "../Controllers/PedidoController.php";
require "../../utils/EnviarEmail.php";

use Producao\External\MySqlConnection;
use Autenticacao\Controllers\AutenticacaoController;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Producao\Controllers\PedidoController;

$dbConnection = new MySqlConnection();
$autenticacaoController = new AutenticacaoController();
$pedidoController = new PedidoController();

$connection = new AMQPStreamConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USERNAME, RABBITMQ_PASSWORD);
$channel = $connection->channel();

$channel->queue_declare('pagamentos_confirmados', false, true, false, false);

$callback = function ($msg) use ($autenticacaoController, $pedidoController, $dbConnection) {
    $dadosArray = json_decode($msg->body, true);
    if (!empty($dadosArray)) {
        $cpf = str_replace([".", "-"], "", $dadosArray["cpf"] ??  $dadosArray["Cpf"]  ?? "");
        $dadosCliente = $autenticacaoController->obterPorCpf($dbConnection, $cpf);
        if (!empty($dadosCliente)) {
            $atualizarStatusPedido = $pedidoController->atualizarStatusPedido($dbConnection, $dadosArray["IdPedido"], "em_preparacao");
            if ($atualizarStatusPedido) {
                if (!empty($dadosCliente["email"])) {
                    $destinatario = $dadosCliente["email"];
                    $nome = $dadosCliente["nome"];
                    $assunto = "Pedido: " . $dadosArray["IdPedido"] . " - Em Preparacao";
                    $mensagem = "Seu pedido já está sendo preparado e em breve estará pronto.";
                    enviarEmail($destinatario, $nome, $assunto, $mensagem);
                }
            }
        }
    }
};

$channel->basic_consume('pagamentos_confirmados', '', false, true, false, false, $callback);

try {
    $channel->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}
