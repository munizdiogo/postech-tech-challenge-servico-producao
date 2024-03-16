<?php

namespace Autenticacao\Interfaces\Gateways;


interface AutenticacaoGatewayInterface
{
    public function obterPorCpf(string $cpf);
}
