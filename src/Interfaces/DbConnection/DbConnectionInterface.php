<?php

namespace Producao\Interfaces\DbConnection;

interface DbConnectionInterface
{
    public function conectar();
    public function inserir(string $nomeTabela, array $parametros);
    public function excluir(string $nomeTabela, int $id): bool;
    public function atualizar(string $nomeTabela, int $id, array $parametros): bool;
    public function buscarPorParametros(string $nomeTabela, array $campos, array $parametros): array;
    public function buscarTodosPedidos(string $nomeTabela): array;
    public function buscarTodosPedidosPorCpf(string $nomeTabela, string $cpf): array;
}
