<?php

namespace Producao\Interfaces\Entities;

interface PedidoInterface
{
    public function getStatus(): string;
    public function getCPF(): string;
    public function getProdutos(): array;
}
