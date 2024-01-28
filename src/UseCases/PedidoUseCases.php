<?php

namespace Producao\UseCases;

require "./src/Interfaces/UseCases/PedidoUseCasesInterface.php";
require "./src/Entities/Pedido.php";

use Producao\Entities\Pedido;
use Producao\Gateways\PedidoGateway;
use Producao\Interfaces\UseCases\PedidoUseCasesInterface;

class PedidoUseCases implements PedidoUseCasesInterface
{
    public function obterPedidos(PedidoGateway $pedidoGateway)
    {
        $pedidos = $pedidoGateway->obterPedidos();
        return $pedidos;
    }

    public function atualizarStatusPedido(PedidoGateway $pedidoGateway, int $id, string $status)
    {
        $statusPermitidos = ["recebido", "em_preparacao", "pronto", "finalizado"];
        $statusValido = in_array($status, $statusPermitidos);

        if (empty($id)) {
            throw new \Exception("O campo id é obrigatório.", 400);
        }

        if (!$statusValido) {
            throw new \Exception("O status informado é inválido.", 400);
        }

        $pedidoValido = (bool)$pedidoGateway->obterPorId($id);
        if (!$pedidoValido) {
            throw new \Exception("Não foi encontrado um pedido com o ID informado.", 400);
        }

        $resultado = $pedidoGateway->atualizarStatusPedido($id, $status);
        return $resultado;
    }

    public function cadastrar(PedidoGateway $pedidoGateway, Pedido $pedido)
    {
        if (empty($pedido->getCPF())) {
            throw new \Exception("O campo cpf é obrigatório.", 400);
        }

        if (empty($pedido->getProdutos())) {
            throw new \Exception("O campo produtos é obrigatório.", 400);
        }

        $idPedido = $pedidoGateway->cadastrar($pedido);
        return $idPedido;
    }
    public function buscarPedidosPorCpf(PedidoGateway $pedidoGateway, $cpf)
    {
        $resultado = $pedidoGateway->buscarPedidosPorCpf($cpf);
        return $resultado;
    }
    public function excluir(PedidoGateway $pedidoGateway, $id)
    {
        $resultado = $pedidoGateway->excluir($id);
        return $resultado;
    }
}
