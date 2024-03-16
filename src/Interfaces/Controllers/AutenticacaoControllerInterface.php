<?php

namespace Autenticacao\Interfaces\Controllers;

interface AutenticacaoControllerInterface
{
    public function obterPorCpf($dbConnection, $cpf);
}
