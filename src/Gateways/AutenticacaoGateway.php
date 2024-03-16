<?php

namespace Autenticacao\Gateways;

if (file_exists("./src/Interfaces/Gateways/AutenticacaoGatewayInterface.php")) {
    require "./src/Interfaces/Gateways/AutenticacaoGatewayInterface.php";
} else {
    require "../Interfaces/Gateways/AutenticacaoGatewayInterface.php";
}

use Autenticacao\Interfaces\Gateways\AutenticacaoGatewayInterface;

class AutenticacaoGateway implements AutenticacaoGatewayInterface
{
    private $repositorioDados;

    public function __construct($database)
    {
        $this->repositorioDados = $database;
    }

    public function obterPorCpf($cpf)
    {
        $cpf = str_replace([".", "-"], "", $cpf);
        $dadosUsuario = $this->repositorioDados->obterPorCpf("clientes", $cpf);
        return $dadosUsuario;
    }
}
