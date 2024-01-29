<?php

namespace Producao\Controllers;

require "./src/Interfaces/Controllers/PedidoControllerInterface.php";
require "./src/UseCases/PedidoUseCases.php";
require "./src/Gateways/PedidoGateway.php";

use Producao\Gateways\PedidoGateway;
use Producao\Entities\Pedido;
use Producao\Interfaces\Controllers\PedidoControllerInterface;
use Producao\UseCases\PedidoUseCases;

class PedidoController implements PedidoControllerInterface
{
    public function cadastrar($dbConnection, array $dados)
    {
        $dados = $dados ?? [];
        $cpf = $dados["cpf"] ?? "";
        $produtos = $dados["produtos"] ?? [];
        $pedidoGateway = new PedidoGateway($dbConnection);
        $pedidoUseCases = new PedidoUseCases();
        $pedido = new Pedido("recebido", $cpf, $produtos);
        $idPedido = $pedidoUseCases->cadastrar($pedidoGateway, $pedido);
        return $idPedido;
    }
    public function buscarPedidosPorCpf($dbConnection, $cpf)
    {
        $cpf = $cpf ?? "";
        $pedidoGateway = new PedidoGateway($dbConnection);
        $pedidoUseCases = new PedidoUseCases();
        $resultado = $pedidoUseCases->buscarPedidosPorCpf($pedidoGateway, $cpf);
        return $resultado;
    }
    public function excluir($dbConnection, $id)
    {
        $id = $id ?? "";
        $pedidoGateway = new PedidoGateway($dbConnection);
        $pedidoUseCases = new PedidoUseCases();
        $resultado = $pedidoUseCases->excluir($pedidoGateway, $id);
        return $resultado;
    }

    public function obterPedidos($dbConnection)
    {
        $pedidoGateway = new PedidoGateway($dbConnection);
        $pedidoUseCases = new PedidoUseCases();
        $pedidos = $pedidoUseCases->obterPedidos($pedidoGateway);
        return $pedidos;
    }

    public function atualizarStatusPedido($dbConnection, $id, $status)
    {
        $pedidoGateway = new PedidoGateway($dbConnection);
        $pedidoUseCases = new PedidoUseCases();
        $resultado = $pedidoUseCases->atualizarStatusPedido($pedidoGateway, $id, $status);
        return $resultado;
    }
}
