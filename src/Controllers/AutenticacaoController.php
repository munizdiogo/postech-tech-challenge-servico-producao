<?php

namespace Autenticacao\Controllers;

if (file_exists("./src/Interfaces/Controllers/AutenticacaoControllerInterface.php")) {
    require "./src/Interfaces/Controllers/AutenticacaoControllerInterface.php";
    require "./src/UseCases/AutenticacaoUseCases.php";
} else {
    require "../Interfaces/Controllers/AutenticacaoControllerInterface.php";
    require "../UseCases/AutenticacaoUseCases.php";
}

use Autenticacao\Gateways\AutenticacaoGateway;
use Autenticacao\Interfaces\Controllers\AutenticacaoControllerInterface;
use Autenticacao\UseCases\AutenticacaoUseCases;

class AutenticacaoController implements AutenticacaoControllerInterface
{
    function obterPorCpf($dbConnection, $cpf)
    {
        $autenticacaoGateway = new AutenticacaoGateway($dbConnection);
        $autenticacaoUseCases = new AutenticacaoUseCases();
        $dadosCliente = $autenticacaoUseCases->obterPorCpf($autenticacaoGateway, $cpf);
        return $dadosCliente;
    }
}
