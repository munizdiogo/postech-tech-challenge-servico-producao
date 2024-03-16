<?php

namespace Producao\Entities;

if (file_exists("./src/Interfaces/Entities/PedidoInterface.php")) {
    require "./src/Interfaces/Entities/PedidoInterface.php";
} else {
    require "../Interfaces/Entities/PedidoInterface.php";
}

use Producao\Interfaces\Entities\PedidoInterface;

class Pedido implements PedidoInterface
{

    private string $id;
    private string $status;
    private string $cpf;
    private array $produtos;

    public function __construct(string $status, string $cpf, array $produtos = [])
    {
        $this->status = $status;
        $this->cpf = $cpf;
        $this->produtos = $produtos;
    }


    public function getStatus(): string
    {
        return $this->status;
    }


    public function getCPF(): string
    {
        return $this->cpf;
    }



    public function getProdutos(): array
    {
        return $this->produtos;
    }
}
