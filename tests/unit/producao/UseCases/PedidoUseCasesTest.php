<?php

use Producao\External\MySqlConnection;
use PHPUnit\Framework\TestCase;
use Producao\UseCases\PedidoUseCases;
use Producao\Entities\Pedido;
use Producao\Gateways\PedidoGateway;

class PedidoUseCasesTest extends TestCase
{
    protected $dbConnection;
    protected $pedidoGateway;
    protected $pedidoUseCases;
    public function setUp(): void
    {
        parent::setUp();
        $this->dbConnection = new MySqlConnection;
        $this->pedidoGateway =  new PedidoGateway($this->dbConnection);
        $this->pedidoUseCases = new PedidoUseCases;
    }

    public function testAtualizarStatusPedidoComSucesso()
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

        $pedidoArray = json_decode($dadosPedido, true);
        $pedido = new Pedido("recebido", "42157363823", $pedidoArray["produtos"]);
        $resultado = $this->pedidoUseCases->cadastrar($this->pedidoGateway, $pedido);
        $this->assertIsInt($resultado);
        $pedidos = $this->pedidoUseCases->buscarPedidosPorCpf($this->pedidoGateway, "42157363823");
        $statusPedidoAtualizado = $this->pedidoUseCases->atualizarStatusPedido($this->pedidoGateway, $pedidos[0]["idPedido"], "finalizado");
        $this->assertTrue($statusPedidoAtualizado);
        $pedidoExcluido = $this->pedidoUseCases->excluir($this->pedidoGateway,  $pedidos[0]["idPedido"]);
        $this->assertTrue($pedidoExcluido);
    }

    public function testAtualizarStatusPedidoComCamposFaltantes()
    {
        try {
            $this->pedidoUseCases->atualizarStatusPedido($this->pedidoGateway, 0, "finalizado");
        } catch (\Exception $e) {
            $this->assertEquals("O campo id é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testAtualizarStatusPedidoComStatusInvalido()
    {
        try {
            $this->pedidoUseCases->atualizarStatusPedido($this->pedidoGateway, 999999999999999999, "encerrado");
        } catch (\Exception $e) {
            $this->assertEquals("O status informado é inválido.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testAtualizarStatusPedidoComPedidoNaoEncontrado()
    {
        try {
            $this->pedidoUseCases->atualizarStatusPedido($this->pedidoGateway, 999999999999999999, "finalizado");
        } catch (\Exception $e) {
            $this->assertEquals("Não foi encontrado um pedido com o ID informado.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
}
