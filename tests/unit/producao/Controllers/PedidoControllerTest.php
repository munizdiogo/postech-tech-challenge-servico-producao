<?php

namespace Producao\Controllers;

require "./src/Controllers/PedidoController.php";
require "./src/External/MySqlConnection.php";

use Producao\Controllers\PedidoController;
use Producao\External\MySqlConnection;
use PHPUnit\Framework\TestCase;

class PedidoControllerTest extends TestCase
{
    private $pedidoController;
    private $dbConnection;

    public function setUp(): void
    {
        parent::setUp();
        $this->pedidoController = new PedidoController();
        $this->dbConnection = new MySqlConnection;
    }

    public function testObterPedidosComPedidosExistentes()
    {
        $dadosPedido = '{
            "cpf": "42157363823",
            "produtos": [
                {
                    "id": 2,
                    "nome": "Produto 1",
                    "descricao": "Descrição do Produto 1",
                    "preco": 20.99,
                    "categoria": "lanche"
                },
                 {
                    "id": 3,
                    "nome": "Produto 2",
                    "descricao": "Descrição do Produto 2",
                    "preco": 15.99,
                    "categoria": "bebida"
                }
            ]
        }';

        $dadosValidos = json_decode($dadosPedido, true);
        $resultado = $this->pedidoController->cadastrar($this->dbConnection, $dadosValidos);
        $this->assertIsInt($resultado);
        $pedidos = $this->pedidoController->buscarPedidosPorCpf($this->dbConnection, "42157363823");
        $todosPedidos = $this->pedidoController->obterPedidos($this->dbConnection);
        $this->assertArrayHasKey("idPedido", $todosPedidos[0]);
        $pedidoExcluido = $this->pedidoController->excluir($this->dbConnection,  $pedidos[0]["idPedido"]);
        $this->assertTrue($pedidoExcluido);
    }

    public function testObterPedidosSemPedidosExistentes()
    {
        $todosPedidos = [];
        $this->assertEquals($todosPedidos, []);
    }

    public function testAtualizarStatusPedido()
    {
        $dadosPedido = '{
            "cpf": "42157363823",
            "produtos": [
                {
                    "id": 2,
                    "nome": "Produto 1",
                    "descricao": "Descrição do Produto 1",
                    "preco": 20.99,
                    "categoria": "lanche"
                },
                 {
                    "id": 3,
                    "nome": "Produto 2",
                    "descricao": "Descrição do Produto 2",
                    "preco": 15.99,
                    "categoria": "bebida"
                }
            ]
        }';

        $dadosValidos = json_decode($dadosPedido, true);
        $resultado = $this->pedidoController->cadastrar($this->dbConnection, $dadosValidos);
        $this->assertIsInt($resultado);

        $pedidos = $this->pedidoController->buscarPedidosPorCpf($this->dbConnection, "42157363823");

        $novoStatus = "em_preparacao";
        $atualizarStatus = $this->pedidoController->atualizarStatusPedido($this->dbConnection, $pedidos[0]["idPedido"], $novoStatus);
        $this->assertTrue($atualizarStatus);

        $pedidoExcluido = $this->pedidoController->excluir($this->dbConnection,  $pedidos[0]["idPedido"]);
        $this->assertTrue($pedidoExcluido);
    }
}
