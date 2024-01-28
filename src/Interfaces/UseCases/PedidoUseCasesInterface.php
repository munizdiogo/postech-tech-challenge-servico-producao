<?php

namespace Producao\Interfaces\UseCases;

use Producao\Gateways\PedidoGateway;

interface PedidoUseCasesInterface
{
    public function obterPedidos(PedidoGateway $pedidoGateway);
    public function atualizarStatusPedido(PedidoGateway $pedidoGateway, int $id, string $status);
}
