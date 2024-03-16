<?php

namespace Autenticacao\Interfaces\UseCases;

use Autenticacao\Gateways\AutenticacaoGateway;

interface AutenticacaoUseCasesInterface
{
    public function obterPorCpf(AutenticacaoGateway $autenticacaoGateway, string $cpf);
}
