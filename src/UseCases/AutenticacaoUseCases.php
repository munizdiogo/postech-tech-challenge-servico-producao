<?php

namespace Autenticacao\UseCases;

if (file_exists("./src/Interfaces/UseCases/AutenticacaoUseCasesInterface.php")) {
    require "./src/Interfaces/UseCases/AutenticacaoUseCasesInterface.php";
    require "./src/Gateways/AutenticacaoGateway.php";
} else {
    require "../Interfaces/UseCases/AutenticacaoUseCasesInterface.php";
    require "../Gateways/AutenticacaoGateway.php";
}

use Autenticacao\Gateways\AutenticacaoGateway;
use Autenticacao\Interfaces\UseCases\AutenticacaoUseCasesInterface;

class AutenticacaoUseCases implements AutenticacaoUseCasesInterface
{
    public function obterPorCpf(AutenticacaoGateway $autenticacaoGateway, $cpf)
    {
        if (empty($cpf)) {
            throw new \Exception("O CPF é obrigatório.", 400);
        }

        $resultado = $autenticacaoGateway->obterPorCpf($cpf);
        return $resultado;
    }
}
